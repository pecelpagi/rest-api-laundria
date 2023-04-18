<?php
namespace IService;

defined('BASEPATH') OR exit('No direct script access allowed');

interface EmployeeService {
    public function set_employee_id($id);
    public function get_my_profile($decoded);
}