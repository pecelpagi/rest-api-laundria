<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'constants/ColumnConstant.php';

class Company_profile_model extends CI_model {
    private $table_name;
    
    function __construct()
    {
        parent::__construct();
		$this->table_name = 'CompanyProfiles';
	}

    public function get_one_data()
    {
        $query = $this->db->get($this->table_name);
        $results = $query->result();

        if (!$results) return FALSE;
        
        return $query->row(0);
    }

    public function update_data($form_data) {
        $COLUMN_KEY = 'ColumnConstant\CompanyProfile';

        $id = $form_data[$COLUMN_KEY::ID];

        $filtered_form_data = array_filter(array_keys($form_data), fn($x) => ($x !== $COLUMN_KEY::ID));

        $data = array();

        foreach ($filtered_form_data as $key) { $data[$key] = $form_data[$key]; }

        $this->db->where($COLUMN_KEY::ID, $id);
        $this->db->update($this->table_name, $data);
    }
}