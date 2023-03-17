<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'constants/ColumnConstant.php';
require_once APPPATH . 'constants/EmployeeRoleConstant.php';

class Employee_model extends CI_Model {
    private $table_name;
    
    function __construct() {
        parent::__construct();
		$this->table_name = 'Employees';
	}

    public function get_one_data_by($column, $value) {
        $this->db->where($column, $value);
        $query = $this->db->get($this->table_name);
        $results = $query->result();

        if (!$results) return FALSE;
        
        return $query->row(0);
    }

    public function insert_data($form_data) {
        $COLUMN_KEY = 'ColumnConstant\Employee';

        $form_data[$COLUMN_KEY::PASSWD] = password_hash("123456", PASSWORD_BCRYPT);
        $form_data[$COLUMN_KEY::ROLE] = 2;

        $this->db->insert($this->table_name, $form_data);
    }

    public function update_data($form_data) {
        $COLUMN_KEY = 'ColumnConstant\Employee';

        $id = $form_data[$COLUMN_KEY::ID];

        if ($id == EmployeeRoleConstant::SUPERADMIN) return;

        $this->db->where('id', $id);
        $this->db->update($this->table_name, $form_data);
    }

    public function delete_data($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_name);
    }

    public function get_all_data($limit = NULL, $offset = 0, $search) {
        if ($search) { $this->db->where("LOWER(fullname) LIKE LOWER('%{$search}%')"); }
        if ($limit) { $this->db->limit($limit); }
    
        $this->db->offset($offset);

        $query = $this->db->get($this->table_name);

        return $query->result();
    }

    public function get_all_data_except_id($id, $limit = NULL, $offset = 0, $search) {
        $this->db->where('id !=', $id);
        
        return $this->get_all_data($limit, $offset, $search);
    }

    public function get_total_all_data($except_id = NULL, $search) {
        if ($search) { $this->db->where("LOWER(fullname) LIKE LOWER('%{$search}%')"); }
        if ($except_id) { $this->db->where('id !=', $except_id); }
        
        return $this->db->count_all_results($this->table_name);
    }
}