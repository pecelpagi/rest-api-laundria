<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'constants/CompanyProfileColumnConstant.php';

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

    public function update_data($service) {
        $COLUMN_KEY = 'CompanyProfileColumnConstant';
        $id = $service->put($COLUMN_KEY::ID);
        
        $data = array(
            $COLUMN_KEY::NAME => $service->put($COLUMN_KEY::NAME),
            $COLUMN_KEY::ADDR => $service->put($COLUMN_KEY::ADDR),
            $COLUMN_KEY::PHONE => $service->put($COLUMN_KEY::PHONE),
            $COLUMN_KEY::EMAIL => $service->put($COLUMN_KEY::EMAIL),
        );

        $this->db->where('id', $id);
        $this->db->update($this->table_name, $data);
    }
}