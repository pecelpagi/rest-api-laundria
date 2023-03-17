<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'constants/ColumnConstant.php';

use chriskacerguis\RestServer\RestController;

class CompanyProfileService extends RestController {
    function __construct()
    {
        parent::__construct();

        $this->load->helper('api_service');
        $this->load->library('form_validation');
        $this->load->model('company_profile_model', 'company_profile');
    }

    public function get_data() {
        $data = $this->company_profile->get_one_data($this);
        
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
}