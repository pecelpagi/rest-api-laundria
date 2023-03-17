<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'constants/ColumnConstant.php';
require_once APPPATH . 'constants/CommonConstant.php';

use chriskacerguis\RestServer\RestController;

class SaleService extends RestController {

    function __construct()
    {
        parent::__construct();

        $this->load->helper('api_service');
        $this->load->library('form_validation');
        $this->load->model('sale_model', 'sale');
        $this->load->model('laundry_package_model', 'laundry_package');
        $this->load->model('customer_model', 'customer');
        $this->load->model('payment_type_model', 'payment_type');
    }

    private function throw_error_if_data_to_create_is_invalid($form_data) {
        $PARAM_KEY = 'ColumnConstant\Sale';
        
        $customer_id = $form_data[$PARAM_KEY::CUSTOMER_ID];
        $customer = $this->customer->get_one_data_by($PARAM_KEY::ID, $customer_id);

        if (!$customer) { throw new Exception("Customer with ID: {$customer_id} is not found"); }

        $laundry_package_id = $form_data[$PARAM_KEY::LAUNDRY_PACKAGE_ID];
        $laundry_package = $this->laundry_package->get_one_data_by($PARAM_KEY::ID, $laundry_package_id);

        if (!$laundry_package) { throw new Exception("Laundry package with ID: {$laundry_package_id} is not found"); }

        $payment_type_id = $form_data[$PARAM_KEY::PAYMENT_TYPE_ID];
        $payment_type = $this->payment_type->get_one_data_by($PARAM_KEY::ID, $payment_type_id);

        if (!$payment_type) { throw new Exception("Payment type with ID: {$payment_type_id} is not found"); }
    }

    public function create_data() {
        $PARAM_KEY = 'ColumnConstant\Sale';
        $column_constant_keys = get_column_constant_keys_from_class($PARAM_KEY);

        $form_data = $this->post();

        is_form_data_valid($form_data, $column_constant_keys);

        $this->form_validation->set_data($form_data);

        foreach($column_constant_keys as $key) {
            if ($key === $PARAM_KEY::ID) continue;
            if ($key === $PARAM_KEY::LAUNDRY_PACKAGE_PRICE) continue;
            if ($key === $PARAM_KEY::TOTAL) continue;
            if ($key === $PARAM_KEY::CREATED_AT) continue;
            if ($key === $PARAM_KEY::UPDATED_AT) continue;

            $this->form_validation->set_rules($key, $key, 'required');
        }

        if ($this->form_validation->run() === FALSE) { throw new Exception(validation_errors_text()); }

        $this->throw_error_if_data_to_create_is_invalid($form_data);

        $laundry_package_id = $form_data[$PARAM_KEY::LAUNDRY_PACKAGE_ID];
        $laundry_package = $this->laundry_package->get_one_data_by($PARAM_KEY::ID, $laundry_package_id);

        $form_data[$PARAM_KEY::LAUNDRY_PACKAGE_PRICE] = $laundry_package->price;

        $this->sale->insert_data($form_data);
    }

    public function update_data() {
        $PARAM_KEY = 'ColumnConstant\Sale';
        $column_constant_keys = get_column_constant_keys_from_class($PARAM_KEY);

        $form_data = $this->put();

        is_form_data_valid($form_data, $column_constant_keys);

        $this->form_validation->set_data($form_data);

        $this->form_validation->set_rules($PARAM_KEY::ID, $PARAM_KEY::ID, 'required');
        $this->form_validation->set_rules($PARAM_KEY::PAYMENT_STATUS, $PARAM_KEY::PAYMENT_STATUS, 'required');
        $this->form_validation->set_rules($PARAM_KEY::ORDER_STATUS, $PARAM_KEY::ORDER_STATUS, 'required');
    
        if ($this->form_validation->run() === FALSE) { throw new Exception(validation_errors_text()); }

        $this->sale->update_data($form_data);
    }

    public function delete_data($id) {
        if (!$id) { throw new Error("Order ID is required"); }

        $this->sale->delete_data($id);
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
        $order_status = $this->get("order_status");

        $data = $this->sale->get_all_data($limit, $offset, $search, $order_status);

        return $data;
    }

    public function get_total_pages() {
        $PARAM_KEY = 'CommonConstant';

        if (!$this->get($PARAM_KEY::LIMIT)) { return 0; }

        $order_status = $this->get("order_status");

        $total_data = $this->sale->get_total_all_data($order_status);
        $total_pages = ceil($total_data / $service->get($PARAM_KEY::LIMIT));

        return $total_pages;
    }

    public function get_one_data($id) {
        $data = $this->sale->get_one_data_by('Sales.id', $id);

        return $data;
    }

    public function get_data_by_range_date() {
        $PARAM_KEY = (object)[];
        $PARAM_KEY->START_DATE = "start_date";
        $PARAM_KEY->END_DATE = "end_date";

        if (!$this->get($PARAM_KEY->START_DATE)) { throw new Exception("Start date is required"); }
        if (!$this->get($PARAM_KEY->END_DATE)) { throw new Exception("End date is required"); }

        $data = $this->sale->get_data_by_range_date($this->get($PARAM_KEY->START_DATE), $this->get($PARAM_KEY->END_DATE));

        return $data;
    }
}