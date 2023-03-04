<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'constants/CustomerColumnConstant.php';

class Customer_model extends CI_model {
    private $table_name;
    
    function __construct()
    {
        parent::__construct();
		$this->table_name = 'Customers';
	}

    public function get_one_data_by($column, $value)
    {
        $this->db->where($column, $value);
        $query = $this->db->get($this->table_name);
        $results = $query->result();

        if (!$results) return FALSE;
        
        return $query->row(0);
    }

    public function get_all_data($limit = NULL, $offset = 0, $search) {
        if ($search) { $this->db->where("LOWER(fullname) LIKE LOWER('%{$search}%')"); }

        if ($limit) { $this->db->limit($limit); }
    
        $this->db->offset($offset);

        $query = $this->db->get($this->table_name);

        return $query->result();
    }

    public function get_total_all_data() {
        return $this->db->count_all_results($this->table_name);
    }

    public function insert_data($service) {
        $COLUMN_KEY = 'CustomerColumnConstant';

        $data = array(
            $COLUMN_KEY::FULLNAME => $service->post($COLUMN_KEY::FULLNAME),
            $COLUMN_KEY::ADDR => $service->post($COLUMN_KEY::ADDR),
            $COLUMN_KEY::PHONE => $service->post($COLUMN_KEY::PHONE),
        );

        $this->db->insert($this->table_name, $data);
    }

    public function update_data($service) {
        $COLUMN_KEY = 'CustomerColumnConstant';
        $id = $service->put($COLUMN_KEY::ID);
        
        $data = array(
            $COLUMN_KEY::FULLNAME => $service->put($COLUMN_KEY::FULLNAME),
            $COLUMN_KEY::ADDR => $service->put($COLUMN_KEY::ADDR),
            $COLUMN_KEY::PHONE => $service->put($COLUMN_KEY::PHONE),
        );

        $this->db->where('id', $id);
        $this->db->update($this->table_name, $data);
    }

    public function delete_data($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_name);
    }
}