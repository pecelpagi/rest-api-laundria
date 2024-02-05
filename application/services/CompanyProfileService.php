<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'constants/ColumnConstant.php';
require_once APPPATH . 'service_interfaces/CommonService.php';

use chriskacerguis\RestServer\RestController;

class CompanyProfileService extends RestController implements IService\CommonService {
    function __construct()
    {
        parent::__construct();

        $this->load->helper('api_service');
        $this->load->library('form_validation');
        $this->load->model('company_profile_model', 'company_profile');
    }

    public function find_one($id = NULL) {
        $data = $this->company_profile->get_one_data();
        
        return $data;
    }

    public function update_data() {
        $column_constant_keys = get_column_constant_keys_from_class('ColumnConstant\CompanyProfile');

        $form_data = $this->put();

        is_form_data_valid($form_data, $column_constant_keys);

        $this->form_validation->set_data($form_data);

        foreach($column_constant_keys as $key) { $this->form_validation->set_rules($key, $key, 'required'); }

        if ($this->form_validation->run() === FALSE) { throw new Exception(validation_errors_text()); }

        $this->company_profile->update_data($form_data);
    }

    public function find_all() {}
    public function insert_data($form_data) {}
    public function delete_data($id) {}
}