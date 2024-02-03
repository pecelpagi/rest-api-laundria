<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'constants/ColumnConstant.php';

class Laundry_package_model extends CI_model {
    private $table_name;
    
    function __construct()
    {
        parent::__construct();
		$this->table_name = 'LaundryPackages';
	}

    public function get_one_data_by($column, $value) {
        $this->db->where($column, $value);
        $query = $this->db->get($this->table_name);
        $results = $query->result();

        if (!$results) return FALSE;
        
        return $query->row(0);
    }

    public function get_all_data($limit = NULL, $offset = 0, $search = NULL) {
        if ($search) {
            $this->db->where("LOWER(name) LIKE LOWER('%{$search}%')");
        }
        if ($limit) {
            $this->db->limit($limit);
        }
    
        $this->db->offset($offset);

        $query = $this->db->get($this->table_name);

        return $query->result();
    }

    public function get_total_all_data($search = NULL) {
        if ($search) { $this->db->where("LOWER(name) LIKE LOWER('%{$search}%')"); }

        return $this->db->count_all_results($this->table_name);
    }

    public function insert_data($form_data) {
        $this->db->insert($this->table_name, $form_data);
    }

    public function update_data($form_data) {
        $COLUMN_KEY = 'ColumnConstant\LaundryPackage';

        $id = $form_data[$COLUMN_KEY::ID];
        
        $filtered_form_data = array_filter(array_keys($form_data), fn($x) => ($x !== $COLUMN_KEY::ID));

        $data = array();

        foreach ($filtered_form_data as $key) { $data[$key] = $form_data[$key]; }

        $this->db->where($COLUMN_KEY::ID, $id);
        $this->db->update($this->table_name, $data);
    }

    public function delete_data($id) {
        $COLUMN_KEY = 'ColumnConstant\LaundryPackage';

        $this->db->where($COLUMN_KEY::ID, $id);
        $this->db->delete($this->table_name);
    }
}