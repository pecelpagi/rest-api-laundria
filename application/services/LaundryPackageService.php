<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'constants/ColumnConstant.php';
require_once APPPATH . 'constants/CommonConstant.php';

use chriskacerguis\RestServer\RestController;

class LaundryPackageService extends RestController {

    function __construct()
    {
        parent::__construct();

        $this->load->helper('api_service');
        $this->load->library('form_validation');
        $this->load->model('laundry_package_model', 'laundry_package');
    }

    public function create_data() {
        $PARAM_KEY = 'ColumnConstant\LaundryPackage';
        $column_constant_keys = get_column_constant_keys_from_class($PARAM_KEY);

        $form_data = $this->post();

        is_form_data_valid($form_data, $column_constant_keys);

        $this->form_validation->set_data($form_data);

        foreach($column_constant_keys as $key) {
            if ($key === $PARAM_KEY::ID) continue;

            $this->form_validation->set_rules($key, $key, 'required');
        }

        if ($this->form_validation->run() === FALSE) { throw new Exception(validation_errors_text()); }

        $this->laundry_package->insert_data($form_data);
    }

    public function update_data() {
        $column_constant_keys = get_column_constant_keys_from_class('ColumnConstant\LaundryPackage');

        $form_data = $this->put();

        is_form_data_valid($form_data, $column_constant_keys);

        $this->form_validation->set_data($form_data);

        foreach($column_constant_keys as $key) { $this->form_validation->set_rules($key, $key, 'required'); }

        if ($this->form_validation->run() === FALSE) { throw new Exception(validation_errors_text()); }

        $this->laundry_package->update_data($form_data);
    }

    public function delete_data($id) {
        if (!$id) { throw new Error("Laundry package ID is required"); }

        $this->laundry_package->delete_data($id);
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

    public function get_all_data() {
        extract($this->create_limit_offset_data());
        
        $search = $this->get(CommonConstant::SEARCH);

        $data = $this->laundry_package->get_all_data($limit, $offset, $search);

        return $data;
    }

    public function get_total_pages() {
        $PARAM_KEY = 'CommonConstant';

        if (!$this->get($PARAM_KEY::LIMIT)) { return 0; }

        $search = $this->get($PARAM_KEY::SEARCH);

        $total_data = $this->laundry_package->get_total_all_data($search);
        $total_pages = ceil($total_data / $this->get($PARAM_KEY::LIMIT));

        return $total_pages;
    }
}