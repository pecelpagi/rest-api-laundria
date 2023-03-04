<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'constants/SaleColumnConstant.php';

class Sale_model extends CI_model {
    private $table_name;
    
    function __construct()
    {
        parent::__construct();

        date_default_timezone_set("Asia/Jakarta");

		$this->table_name = 'Sales';
	}

    public function get_one_data_by($column, $value)
    {
        $this->db->select('Sales.*, Customers.fullname as customer_name, LaundryPackages.name as laundry_package_name, PaymentTypes.name as payment_type_name');
        $this->db->from('Sales');
        $this->db->join('Customers', 'Customers.id = Sales.customer_id');
        $this->db->join('LaundryPackages', 'LaundryPackages.id = Sales.laundry_package_id');
        $this->db->join('PaymentTypes', 'PaymentTypes.id = Sales.payment_type_id');

        $this->db->where($column, $value);
        $query = $this->db->get();
        $results = $query->result();

        if (!$results) return FALSE;
        
        return $query->row(0);
    }

    public function get_data_by_range_date($start_date, $end_date) {
        $this->db->select('Sales.*, Customers.fullname as customer_name, LaundryPackages.name as laundry_package_name');
        $this->db->from('Sales');
        $this->db->join('Customers', 'Customers.id = Sales.customer_id');
        $this->db->join('LaundryPackages', 'LaundryPackages.id = Sales.laundry_package_id');

        $this->db->where("Sales.created_at BETWEEN '{$start_date} 00:00:00' AND '{$end_date} 23:59:59'");

        $query = $this->db->get();
        return $query->result();
    }

    public function get_all_data($limit = NULL, $offset = 0, $search = '', $order_status = NULL) {
        $this->db->select('Sales.*, Customers.fullname as customer_name, LaundryPackages.name as laundry_package_name');
        $this->db->from('Sales');
        $this->db->join('Customers', 'Customers.id = Sales.customer_id');
        $this->db->join('LaundryPackages', 'LaundryPackages.id = Sales.laundry_package_id');

        if ($order_status) { $this->db->where("Sales.order_status", $order_status); }

        if ($search) {
            $this->db->where("LOWER(CONCAT(Sales.sales_number, Sales.phone, Sales.addr, LaundryPackages.name, Customers.fullname)) LIKE LOWER('%{$search}%')");
        }

        if ($limit) { $this->db->limit($limit); }
    
        $this->db->offset($offset);

        $query = $this->db->get();

        return $query->result();
    }

    public function get_total_all_data($order_status = NULL) {
        $this->db->select('Sales.*');
        $this->db->from('Sales');
        $this->db->join('Customers', 'Customers.id = Sales.customer_id');
        $this->db->join('LaundryPackages', 'LaundryPackages.id = Sales.laundry_package_id');

        if ($order_status) { $this->db->where("Sales.order_status", $order_status); }

        return $this->db->count_all_results();
    }

    public function insert_data($service, $additional_data) {
        $COLUMN_KEY = 'SaleColumnConstant';

        $reflector = new ReflectionClass($COLUMN_KEY);
        $constants = $reflector->getConstants();

        $data = array();

        foreach($constants as $constant) {
            if ($constant === $COLUMN_KEY::ID) continue;
            if ($constant === $COLUMN_KEY::LAUNDRY_PACKAGE_PRICE) continue;
            if ($constant === $COLUMN_KEY::TOTAL) continue;
            if ($constant === $COLUMN_KEY::CREATED_AT) continue;
            if ($constant === $COLUMN_KEY::UPDATED_AT) continue;

            $data[$constant] = $service->post($constant);
        }

        $current_datetime = date("Y-m-d H:i:s");
        
        $data[$COLUMN_KEY::LAUNDRY_PACKAGE_PRICE] = $additional_data[$COLUMN_KEY::LAUNDRY_PACKAGE_PRICE];
        $data[$COLUMN_KEY::TOTAL] = $service->post($COLUMN_KEY::WEIGHT) * $additional_data[$COLUMN_KEY::LAUNDRY_PACKAGE_PRICE];
        $data[$COLUMN_KEY::CREATED_AT] = $current_datetime;
        $data[$COLUMN_KEY::UPDATED_AT] = $current_datetime;
        
        $this->db->insert($this->table_name, $data);
    }

    public function update_data($service) {
        $COLUMN_KEY = 'SaleColumnConstant';
        
        $id = $service->put($COLUMN_KEY::ID);
        
        $reflector = new ReflectionClass($COLUMN_KEY);
        $constants = $reflector->getConstants();

        $data = array();

        $data[$COLUMN_KEY::PAYMENT_STATUS] = $service->put($COLUMN_KEY::PAYMENT_STATUS);
        $data[$COLUMN_KEY::ORDER_STATUS] = $service->put($COLUMN_KEY::ORDER_STATUS);

        $current_datetime = date("Y-m-d H:i:s");
        $data[$COLUMN_KEY::UPDATED_AT] = $current_datetime;

        $this->db->where('id', $id);
        $this->db->update($this->table_name, $data);
    }

    public function delete_data($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_name);
    }
}