<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'constants/EmployeeColumnConstant.php';

use chriskacerguis\RestServer\RestController;

class EmployeeService extends RestController {

    function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('employee_model', 'employee');
    }

    public function login() {
        $PARAM_KEY = 'EmployeeColumnConstant';

        $this->form_validation->set_rules($PARAM_KEY::USERNAME, 'Username', 'required');
        $this->form_validation->set_rules($PARAM_KEY::PASSWD, 'Password', 'required');

        if ($this->form_validation->run() === FALSE) { throw new Exception(validation_errors_text()); }

        $username = $this->post($PARAM_KEY::USERNAME);
        $passwd = $this->post($PARAM_KEY::PASSWD);
        
        $employee = $this->employee->get_one_data_by('username', $username);

        if (!$employee) { throw new Exception('Username tidak ditemukan'); }

        $is_password_valid = password_verify($passwd, $employee->passwd);
    
        if (!$is_password_valid) { throw new Exception("Periksa kembali password anda"); }

        return $employee;
    }

    public function create_data() {
        $PARAM_KEY = 'EmployeeColumnConstant';

        $reflector = new ReflectionClass($PARAM_KEY);
        $constants = $reflector->getConstants();

        foreach($constants as $constant) {
            if ($constant === $PARAM_KEY::ID) continue;
            if ($constant === $PARAM_KEY::PASSWD) continue;
            if ($constant === $PARAM_KEY::ROLE) continue;

            $this->form_validation->set_rules($constant, $constant, 'required');
        }

        $this->form_validation->set_rules($PARAM_KEY::EMAIL, $PARAM_KEY::EMAIL, 'required|valid_email');

        if ($this->form_validation->run() === FALSE) { throw new Exception(validation_errors_text()); }

        $username = $this->post($PARAM_KEY::USERNAME);
        $email = $this->post($PARAM_KEY::EMAIL);

        $employee_by_username = $this->employee->get_one_data_by('username', $username);
        
        if ($employee_by_username) { throw new Exception('Username sudah digunakan'); }

        $employee_by_email = $this->employee->get_one_data_by('email', $email);

        if ($employee_by_email) { throw new Exception('Email sudah digunakan'); }

        $this->employee->insert_data($this);
    }

    public function update_data($employee_id = NULL) {
        $PARAM_KEY = 'EmployeeColumnConstant';

        $reflector = new ReflectionClass($PARAM_KEY);
        $constants = $reflector->getConstants();

        $this->form_validation->set_data($this->put());

        foreach($constants as $constant) {
            if ($constant === $PARAM_KEY::PASSWD) continue;
            if ($constant === $PARAM_KEY::ROLE) continue;

            $this->form_validation->set_rules($constant, $constant, 'required');
        }

        $this->form_validation->set_rules($PARAM_KEY::EMAIL, $PARAM_KEY::EMAIL, 'required|valid_email');

        if ($this->form_validation->run() === FALSE) { throw new Exception(validation_errors_text()); }

        $is_username_exist = FALSE;
        $is_email_exist = FALSE;
        
        $id = !$employee_id ? $this->put($PARAM_KEY::ID) : $employee_id;
        $username = $this->put($PARAM_KEY::USERNAME);
        $email = $this->put($PARAM_KEY::EMAIL);
        
        $employee_by_id = $this->employee->get_one_data_by('id', $id);
        
        if (!$employee_by_id) { throw new Exception("Employee with ID: {$id} is not found"); }
        
        $employee_by_username = $this->employee->get_one_data_by('username', $username);
        
        if ($employee_by_username) $is_username_exist = !!($employee_by_username->id !== $id);
        if ($is_username_exist) { throw new Exception('Username sudah digunakan'); }
        
        $employee_by_email = $this->employee->get_one_data_by('email', $email);

        if ($employee_by_email) $is_email_exist = !!($employee_by_email->id !== $id);
        if ($is_email_exist) { throw new Exception('Email sudah digunakan'); }

        $this->employee->update_data($this);
    }

    public function delete_data($id) {
        $this->employee->delete_data($id);
    }

    public function get_my_profile($decoded) {
        $data = $this->employee->get_one_data_by('id', $decoded->data->id);

        return $data;
    }

    private function create_limit_offset_data($service) {
        $PARAM_KEY = (object)[];
        $PARAM_KEY->PAGE = "page";
        $PARAM_KEY->LIMIT = "limit";

        $limit = NULL;
        $offset = 0;

        if ($service->get($PARAM_KEY->PAGE) && $service->get($PARAM_KEY->LIMIT)) {
            $limit = (int) $this->get($PARAM_KEY->LIMIT);
            $page = (int) $this->get($PARAM_KEY->PAGE);
            
            $offset = $limit * ($page - 1);
        } else if ($PARAM_KEY->LIMIT) {
            $limit = (int) $this->get($PARAM_KEY->LIMIT);
        }

        $retval = (object)[];
        $retval->limit = $limit;
        $retval->offset = $offset;
        
        return $retval;
    }

    private function reformat_all_data($data) {
        $remove_passwd_func = function(object $row): object {
            unset($row->passwd);
            return $row;
        };

        return array_map($remove_passwd_func, $data);
    }

    public function get_all_data($service) {
        $payload = $this->create_limit_offset_data($service);
        $limit = $payload->limit;
        $offset = $payload->offset;

        $data = $this->employee->get_all_data($limit, $offset);

        return $this->reformat_all_data($data);
    }

    public function get_all_data_except_id($employee_id, $service) {
        $payload = $this->create_limit_offset_data($service);
        $limit = $payload->limit;
        $offset = $payload->offset;

        $data = $this->employee->get_all_data_except_id($employee_id, $limit, $offset);

        return $this->reformat_all_data($data);
    }

    public function get_total_pages($service, $except_id = NULL) {
        $PARAM_KEY = (object)[];
        $PARAM_KEY->LIMIT = "limit";

        if (!$service->get($PARAM_KEY->LIMIT)) { return 0; }

        $total_data = $this->employee->get_total_all_data($except_id);
        $total_pages = ceil($total_data / $service->get($PARAM_KEY->LIMIT));

        return $total_pages;
    }
}