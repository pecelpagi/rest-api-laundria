<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'constants/SaleColumnConstant.php';

use chriskacerguis\RestServer\RestController;

class SaleService extends RestController {

    function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('sale_model', 'sale');
        $this->load->model('laundry_package_model', 'laundry_package');
        $this->load->model('customer_model', 'customer');
        $this->load->model('payment_type_model', 'payment_type');
    }

    public function create_data() {
        $PARAM_KEY = 'SaleColumnConstant';

        $reflector = new ReflectionClass($PARAM_KEY);
        $constants = $reflector->getConstants();

        foreach($constants as $constant) {
            if ($constant === $PARAM_KEY::ID) continue;
            if ($constant === $PARAM_KEY::LAUNDRY_PACKAGE_PRICE) continue;
            if ($constant === $PARAM_KEY::TOTAL) continue;
            if ($constant === $PARAM_KEY::CREATED_AT) continue;
            if ($constant === $PARAM_KEY::UPDATED_AT) continue;

            $this->form_validation->set_rules($constant, $constant, 'required');
        }

        if ($this->form_validation->run() === FALSE) { throw new Exception(validation_errors_text()); }

        $customer_id = $this->post($PARAM_KEY::CUSTOMER_ID);
        $customer = $this->customer->get_one_data_by('id', $customer_id);

        if (!$customer) { throw new Exception("Customer with ID: {$customer_id} is not found"); }

        $laundry_package_id = $this->post($PARAM_KEY::LAUNDRY_PACKAGE_ID);
        $laundry_package = $this->laundry_package->get_one_data_by('id', $laundry_package_id);

        if (!$laundry_package) { throw new Exception("Laundry package with ID: {$laundry_package_id} is not found"); }

        $payment_type_id = $this->post($PARAM_KEY::PAYMENT_TYPE_ID);
        $payment_type = $this->payment_type->get_one_data_by('id', $payment_type_id);

        if (!$payment_type) { throw new Exception("Payment type with ID: {$payment_type_id} is not found"); }

        $additional_data[$PARAM_KEY::LAUNDRY_PACKAGE_PRICE] = $laundry_package->price;

        $this->sale->insert_data($this, $additional_data);
    }

    public function update_data() {
        $PARAM_KEY = 'SaleColumnConstant';

        $reflector = new ReflectionClass($PARAM_KEY);
        $constants = $reflector->getConstants();

        $this->form_validation->set_data($this->put());

        $this->form_validation->set_rules($PARAM_KEY::ID, $PARAM_KEY::ID, 'required');
        $this->form_validation->set_rules($PARAM_KEY::PAYMENT_STATUS, $PARAM_KEY::PAYMENT_STATUS, 'required');
        $this->form_validation->set_rules($PARAM_KEY::ORDER_STATUS, $PARAM_KEY::ORDER_STATUS, 'required');
    
        if ($this->form_validation->run() === FALSE) { throw new Exception(validation_errors_text()); }

        $this->sale->update_data($this);
    }

    public function delete_data($id) {
        $this->payment_type->delete_data($id);
    }

    private function create_limit_offset_data() {
        $PARAM_KEY = (object)[];
        $PARAM_KEY->PAGE = "page";
        $PARAM_KEY->LIMIT = "limit";

        $limit = NULL;
        $offset = 0;

        if ($this->get($PARAM_KEY->PAGE) && $this->get($PARAM_KEY->LIMIT)) {
            $limit = (int) $this->get($PARAM_KEY->LIMIT);
            $page = (int) $this->get($PARAM_KEY->PAGE);
            
            $offset = $limit * ($page - 1);
        } else if ($this->get($PARAM_KEY->LIMIT)) {
            $limit = (int) $this->get($PARAM_KEY->LIMIT);
        }

        $retval = (object)[];
        $retval->limit = $limit;
        $retval->offset = $offset;

        return $retval;
    }

    public function get_all_data($service) {
        $payload = $this->create_limit_offset_data();
        $limit = $payload->limit;
        $offset = $payload->offset;
        $search = $service->get("search");
        $order_status = $service->get("order_status");

        $data = $this->sale->get_all_data($limit, $offset, $search, $order_status);

        return $data;
    }

    public function get_total_pages($service) {
        $PARAM_KEY = (object)[];
        $PARAM_KEY->LIMIT = "limit";

        if (!$service->get($PARAM_KEY->LIMIT)) { return 0; }

        $order_status = $service->get("order_status");

        $total_data = $this->sale->get_total_all_data($order_status);
        $total_pages = ceil($total_data / $service->get($PARAM_KEY->LIMIT));

        return $total_pages;
    }

    public function get_one_data($id) {
        $data = $this->sale->get_one_data_by('Sales.id', $id);

        return $data;
    }

    public function get_data_by_range_date($service) {
        $PARAM_KEY = (object)[];
        $PARAM_KEY->START_DATE = "start_date";
        $PARAM_KEY->END_DATE = "end_date";

        if (!$service->get($PARAM_KEY->START_DATE)) { throw new Exception("Start date is required"); }
        if (!$service->get($PARAM_KEY->END_DATE)) { throw new Exception("End date is required"); }

        $data = $this->sale->get_data_by_range_date($service->get($PARAM_KEY->START_DATE), $service->get($PARAM_KEY->END_DATE));

        return $data;
    }
}