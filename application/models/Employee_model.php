<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'constants/EmployeeColumnConstant.php';

class Employee_model extends CI_Model {
    public function get_one_data_by($column, $value)
    {
        $this->db->where($column, $value);
        $query = $this->db->get('Employees');
        $results = $query->result();

        if (!$results) return FALSE;
        
        return $query->row(0);
    }

    public function insert_data($service) {
        $data = array(
            EmployeeColumnConstant::FULLNAME => $service->post(EmployeeColumnConstant::FULLNAME),
            EmployeeColumnConstant::USERNAME => $service->post(EmployeeColumnConstant::USERNAME),
            EmployeeColumnConstant::EMAIL => $service->post(EmployeeColumnConstant::EMAIL),
            EmployeeColumnConstant::ADDR => $service->post(EmployeeColumnConstant::ADDR),
            EmployeeColumnConstant::PHONE => $service->post(EmployeeColumnConstant::PHONE),
            EmployeeColumnConstant::PASSWD => password_hash("123456", PASSWORD_BCRYPT),
            EmployeeColumnConstant::ROLE => 2,
        );

        $this->db->insert('Employees', $data);
    }

    public function update_data($service) {
        $id = $service->put(EmployeeColumnConstant::ID);
        
        $data = array(
            EmployeeColumnConstant::FULLNAME => $service->put(EmployeeColumnConstant::FULLNAME),
            EmployeeColumnConstant::USERNAME => $service->put(EmployeeColumnConstant::USERNAME),
            EmployeeColumnConstant::EMAIL => $service->put(EmployeeColumnConstant::EMAIL),
            EmployeeColumnConstant::ADDR => $service->put(EmployeeColumnConstant::ADDR),
            EmployeeColumnConstant::PHONE => $service->put(EmployeeColumnConstant::PHONE),
        );

        if ($id == '1') return;

        $this->db->where('id', $id);
        $this->db->update('Employees', $data);
    }

    public function delete_data($id) {
        $this->db->where('id', $id);
        $this->db->delete('Employees');
    }

    public function get_all_data($limit = NULL, $offset = 0) {
        if ($limit) { $this->db->limit($limit); }
    
        $this->db->offset($offset);

        $query = $this->db->get('Employees');

        return $query->result();
    }

    public function get_all_data_except_id($id, $limit = NULL, $offset = 0) {
        $this->db->where('id !=', $id);
        
        return $this->get_all_data($limit, $offset);
    }

    public function get_total_all_data($except_id = NULL) {
        if ($except_id) { $this->db->where('id !=', $except_id); }
        
        return $this->db->count_all_results('Employees');
    }
}