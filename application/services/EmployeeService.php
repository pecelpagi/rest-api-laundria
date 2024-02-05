<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'constants/ColumnConstant.php';
require_once APPPATH . 'constants/CommonConstant.php';
require_once APPPATH . 'service_interfaces/CommonService.php';
require_once APPPATH . 'service_interfaces/MetadataService.php';
require_once APPPATH . 'service_interfaces/EmployeeService.php';

use chriskacerguis\RestServer\RestController;

class EmployeeService extends RestController 
implements IService\CommonService, IService\MetadataService, IService\EmployeeService {
    private $employee_id;

    function __construct()
    {
        parent::__construct();

        $this->employee_id = NULL;

        $this->load->helper('api_service');
        $this->load->library('form_validation');
        $this->load->model('employee_model', 'employee');
    }

    public function set_employee_id($id) {
        $this->employee_id = $id;
    }

    private function throw_error_if_login_invalid($username, $passwd) {
        $employee = $this->employee->get_one_data_by('username', $username);
        if (!$employee) { throw new Exception('Username tidak ditemukan'); }

        $is_password_valid = password_verify($passwd, $employee->passwd);
        if (!$is_password_valid) { throw new Exception("Periksa kembali password anda"); }
    }

    public function login() {
        $PARAM_KEY = 'ColumnConstant\Employee';

        $form_data = $this->post();

        $this->form_validation->set_data($form_data);

        $this->form_validation->set_rules($PARAM_KEY::USERNAME, 'Username', 'required');
        $this->form_validation->set_rules($PARAM_KEY::PASSWD, 'Password', 'required');

        if ($this->form_validation->run() === FALSE) { throw new Exception(validation_errors_text()); }

        $username = $form_data[$PARAM_KEY::USERNAME];
        $passwd = $form_data[$PARAM_KEY::PASSWD];
        
        $this->throw_error_if_login_invalid($username, $passwd);

        $employee = $this->employee->get_one_data_by('username', $username);

        return $employee;
    }

    private function throw_error_if_employee_data_duplicate($error_msg, $column, $key, $id = NULL) {
        $employee = $this->employee->get_one_data_by($column, $key);
        $exist = !!$employee;

        if (!$exist) return;

        if ($id) { $exist = ($employee->id !== $id); }
        if ($exist) { throw new Exception($error_msg); }
    }

    private function throw_error_if_employee_username_duplicate($username, $id = NULL) {
        $this->throw_error_if_employee_data_duplicate('Username sudah digunakan', 'username', $username, $id);
    }

    private function throw_error_if_employee_email_duplicate($email, $id = NULL) {
        $this->throw_error_if_employee_data_duplicate('Email sudah digunakan', 'email', $email, $id);
    }

    public function get_my_profile($decoded) {
        $data = $this->employee->get_one_data_by(ColumnConstant\Employee::ID, $decoded->data->id);

        return $data;
    }

    private function create_limit_offset_data() {
        $PARAM_KEY = 'CommonConstant';

        $limit = NULL;
        $offset = 0;

        if ($this->get($PARAM_KEY::PAGE) && $this->get($PARAM_KEY::LIMIT)) {
            $limit = (int) $this->get($PARAM_KEY::LIMIT);
            $page = (int) $this->get($PARAM_KEY::PAGE);
                        
            $offset = $limit * ($page - 1);
        } else if ($PARAM_KEY::LIMIT) { $limit = (int) $this->get($PARAM_KEY::LIMIT); }

        $retval = array();
        $retval[$PARAM_KEY::LIMIT] = $limit;
        $retval[$PARAM_KEY::OFFSET] = $offset;
        
        return $retval;
    }

    private function reformat_all_data($data) {
        $remove_passwd_func = function(object $row): object {
            unset($row->passwd);
            return $row;
        };

        return array_map($remove_passwd_func, $data);
    }

    public function count_number_of_pages() {
        $except_id = $this->employee_id;

        $PARAM_KEY = 'CommonConstant';

        if (!$this->get($PARAM_KEY::LIMIT)) { return 0; }

        $search = $this->get($PARAM_KEY::SEARCH);

        $total_data = $this->employee->get_total_all_data($except_id, $search);
        $total_pages = ceil($total_data / $this->get($PARAM_KEY::LIMIT));

        return $total_pages;
    }

    public function count_number_of_all_rows() {
        $except_id = $this->employee_id;
        $total_data = $this->employee->get_total_all_data($except_id);
        
        return $total_data;
    }

    public function find_all() {
        if (!$this->employee_id) { throw new Exception('Employee ID is required'); }

        extract($this->create_limit_offset_data());

        $search = $this->get(CommonConstant::SEARCH);

        $data = $this->employee->get_all_data_except_id($this->employee_id, $limit, $offset, $search);

        return $this->reformat_all_data($data);
    }

    public function find_one($id = NULL) {}

    public function insert_data($form_data) {
        $PARAM_KEY = 'ColumnConstant\Employee';

        $column_constant_keys = get_column_constant_keys_from_class($PARAM_KEY);

        is_form_data_valid($form_data, $column_constant_keys);

        $this->form_validation->set_data($form_data);

        foreach($column_constant_keys as $key) {
            if ($key === $PARAM_KEY::ID) continue;
            if ($key === $PARAM_KEY::PASSWD) continue;
            if ($key === $PARAM_KEY::ROLE) continue;
            if ($key === $PARAM_KEY::EMAIL) continue;

            $this->form_validation->set_rules($key, $key, 'required');
        }

        $this->form_validation->set_rules($PARAM_KEY::EMAIL, $PARAM_KEY::EMAIL, 'required|valid_email');

        if ($this->form_validation->run() === FALSE) { throw new Exception(validation_errors_text()); }

        extract($form_data);
        
        $this->throw_error_if_employee_username_duplicate($username);
        $this->throw_error_if_employee_email_duplicate($email);

        $this->employee->insert_data($form_data);
    }

    public function update_data() {
        $employee_id = $this->employee_id;

        $PARAM_KEY = 'ColumnConstant\Employee';

        $column_constant_keys = get_column_constant_keys_from_class($PARAM_KEY);

        $form_data = $this->put();

        is_form_data_valid($form_data, $column_constant_keys);

        $this->form_validation->set_data($form_data);

        foreach($column_constant_keys as $key) {
            if ($key === $PARAM_KEY::PASSWD) continue;
            if ($key === $PARAM_KEY::ROLE) continue;
            if ($key === $PARAM_KEY::EMAIL) continue;

            $this->form_validation->set_rules($key, $key, 'required');
        }

        $this->form_validation->set_rules($PARAM_KEY::EMAIL, $PARAM_KEY::EMAIL, 'required|valid_email');

        if ($this->form_validation->run() === FALSE) { throw new Exception(validation_errors_text()); }

        $id = !$employee_id ? $form_data[$PARAM_KEY::ID] : $employee_id;
        
        extract($form_data);
        
        $employee = $this->employee->get_one_data_by('id', $id);
        
        if (!$employee) { throw new Exception("Employee with ID: {$id} is not found"); }
        
        $this->throw_error_if_employee_username_duplicate($username, $id);
        $this->throw_error_if_employee_email_duplicate($email, $id);

        $this->employee->update_data($form_data);
    }

    public function delete_data($id) {
        $this->employee->delete_data($id);
    }
}