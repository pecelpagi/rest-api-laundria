<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class SummaryService extends RestController {
    function __construct()
    {
        parent::__construct();

        $this->load->model('employee_model', 'employee');
        $this->load->model('customer_model', 'customer');
        $this->load->model('sale_model', 'sale');
    }

    public function get_summary_data() {
        $total_data_customer = $this->customer->get_total_all_data();
        $total_data_employee = $this->employee->get_total_all_data("1");
        $total_data_orders = $this->sale->get_total_all_data();
        $total_data_new_orders = $this->sale->get_total_all_data("1");

        $retval = (object)[];
        $retval->total_data_customer = $total_data_customer;
        $retval->total_data_employee = $total_data_employee;
        $retval->total_data_orders = $total_data_orders;
        $retval->total_data_new_orders = $total_data_new_orders;
        
        return $retval;
    }
}