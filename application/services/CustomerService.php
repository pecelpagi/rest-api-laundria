<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'constants/CustomerColumnConstant.php';

use chriskacerguis\RestServer\RestController;

class CustomerService extends RestController {

    function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('customer_model', 'customer');
    }

    public function create_data() {
        $PARAM_KEY = 'CustomerColumnConstant';

        $reflector = new ReflectionClass($PARAM_KEY);
        $constants = $reflector->getConstants();

        foreach($constants as $constant) {
            if ($constant === $PARAM_KEY::ID) continue;

            $this->form_validation->set_rules($constant, $constant, 'required');
        }

        if ($this->form_validation->run() === FALSE) { throw new Exception(validation_errors_text()); }

        $this->customer->insert_data($this);
    }

    public function update_data() {
        $PARAM_KEY = 'CustomerColumnConstant';

        $reflector = new ReflectionClass($PARAM_KEY);
        $constants = $reflector->getConstants();

        $this->form_validation->set_data($this->put());

        foreach($constants as $constant) { $this->form_validation->set_rules($constant, $constant, 'required'); }

        if ($this->form_validation->run() === FALSE) { throw new Exception(validation_errors_text()); }

        $this->customer->update_data($this);
    }

    public function delete_data($id) {
        $this->customer->delete_data($id);
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

    public function get_all_data($service) {
        $payload = $this->create_limit_offset_data($service);
        $limit = $payload->limit;
        $offset = $payload->offset;
        $search = $this->get("search");

        $data = $this->customer->get_all_data($limit, $offset, $search);

        return $data;
    }

    public function get_total_pages($service) {
        $PARAM_KEY = (object)[];
        $PARAM_KEY->LIMIT = "limit";

        if (!$service->get($PARAM_KEY->LIMIT)) { return 0; }

        $total_data = $this->customer->get_total_all_data();
        $total_pages = ceil($total_data / $service->get($PARAM_KEY->LIMIT));

        return $total_pages;
    }
}