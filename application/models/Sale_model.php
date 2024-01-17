<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'constants/ColumnConstant.php';

class Sale_model extends CI_model {
    private $table_name;
    
    function __construct()
    {
        parent::__construct();

        date_default_timezone_set("Asia/Jakarta");

		$this->table_name = 'Sales';
	}

    public function get_daily_transaction_total_grouped_by_date($start_date, $end_date)
    {
        $sql = "SELECT COUNT(CAST(created_at as DATE)) as daily_transaction_total, CAST(created_at as DATE) as date_created_at FROM Sales GROUP BY date_created_at HAVING date_created_at >= ? AND date_created_at <= ? ORDER BY date_created_at DESC";
        $query = $this->db->query($sql, array($start_date, $end_date));

        return $query->result();
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
            $sql = "LOWER(
                        CONCAT(
                            Sales.sales_number, Sales.phone, Sales.addr, 
                            LaundryPackages.name, Customers.fullname
                        )
                    ) LIKE LOWER('%{$search}%')";
            $this->db->where($sql);
        }
        if ($limit) { $this->db->limit($limit); }
    
        $this->db->offset($offset);

        $query = $this->db->get();

        return $query->result();
    }

    public function get_total_all_data($order_status = NULL, $search = NULL) {
        $this->db->select('Sales.*');
        $this->db->from('Sales');
        $this->db->join('Customers', 'Customers.id = Sales.customer_id');
        $this->db->join('LaundryPackages', 'LaundryPackages.id = Sales.laundry_package_id');

        if ($order_status) { $this->db->where("Sales.order_status", $order_status); }
        if ($search) {
            $sql = "LOWER(
                        CONCAT(
                            Sales.sales_number, Sales.phone, Sales.addr, 
                            LaundryPackages.name, Customers.fullname
                        )
                    ) LIKE LOWER('%{$search}%')";
            $this->db->where($sql);
        }

        return $this->db->count_all_results();
    }

    public function insert_data($form_data) {
        $COLUMN_KEY = 'ColumnConstant\Sale';

        $current_datetime = date("Y-m-d H:i:s");
        
        $form_data[$COLUMN_KEY::TOTAL] = $form_data[$COLUMN_KEY::WEIGHT] * $form_data[$COLUMN_KEY::LAUNDRY_PACKAGE_PRICE];
        $form_data[$COLUMN_KEY::CREATED_AT] = $current_datetime;
        $form_data[$COLUMN_KEY::UPDATED_AT] = $current_datetime;

        $this->db->insert($this->table_name, $form_data);
    }

    public function update_data($form_data) {
        $COLUMN_KEY = 'ColumnConstant\Sale';
        
        $id = $form_data[$COLUMN_KEY::ID];
        
        $filtered_form_data = array_filter(array_keys($form_data), fn($x) => ($x !== $COLUMN_KEY::ID));

        $data = array();

        foreach ($filtered_form_data as $key) { $data[$key] = $form_data[$key]; }

        $current_datetime = date("Y-m-d H:i:s");
        $data[$COLUMN_KEY::UPDATED_AT] = $current_datetime;

        $this->db->where($COLUMN_KEY::ID, $id);
        $this->db->update($this->table_name, $data);
    }

    public function delete_data($id) {
        $COLUMN_KEY = 'ColumnConstant\Sale';

        $this->db->where($COLUMN_KEY::ID, $id);
        $this->db->delete($this->table_name);
    }
}