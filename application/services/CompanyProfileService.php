<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class CompanyProfileService extends RestController {
    function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('company_profile_model', 'company_profile');
    }

    public function get_data() {
        $data = $this->company_profile->get_one_data($this);
        
        return $data;
    }

    public function update_data() {
        $PARAM_KEY = 'CompanyProfileColumnConstant';

        $reflector = new ReflectionClass($PARAM_KEY);
        $constants = $reflector->getConstants();

        $this->form_validation->set_data($this->put());

        foreach($constants as $constant) { $this->form_validation->set_rules($constant, $constant, 'required'); }

        if ($this->form_validation->run() === FALSE) { throw new Exception(validation_errors_text()); }

        $this->company_profile->update_data($this);
    }
}