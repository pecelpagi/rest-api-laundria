<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Firebase\JWT\JWT;

require_once APPPATH . 'core/CoreController.php';
require_once APPPATH . 'services/EmployeeService.php';

class Employee extends CoreController {
	private $employee_service;

	function __construct()
    {
        parent::__construct();
		$this->employee_service = new EmployeeService();
	}

	public function index_get() {
		$decoded = $this->jwt_auth_required($this->superadmin_only);

		try {
			$employee_id = $decoded->data->id;
			$employees = $this->employee_service->get_all_data_except_id($employee_id, $this);
			$total_pages = $this->employee_service->get_total_pages($this, $employee_id);

			$additional_data = [
				'meta' => [
					'total_pages' => $total_pages,
				],
			];

			$this->set_successful_response($employees, $additional_data);
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function index_post() {
		$this->jwt_auth_required($this->superadmin_only);

		try {
			$employee = $this->employee_service->create_data();
			$this->set_successful_response("OK");
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function index_put() {
		$this->jwt_auth_required($this->superadmin_only);

		try {
			$employee = $this->employee_service->update_data();
			$this->set_successful_response("OK");
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function index_delete($key = '') {
		$this->jwt_auth_required($this->superadmin_only);

		try {
			if (!$key) { throw new Error("Employee ID is required"); }

			$employee = $this->employee_service->delete_data($key);
			$this->set_successful_response("OK");
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function login_post() {
		try {
			$employee = $this->employee_service->login();
			$data = array(
				"id" => $employee->id,
				"role" => $employee->role
			);
			$data = $this->encode_data_as_token($data);
			$this->set_successful_response($data);
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function my_profile_get() {
		$decoded = $this->jwt_auth_required();
		
		try {
			$data = $this->employee_service->get_my_profile($decoded);
			$this->set_successful_response($data);
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function my_profile_put() {
		$decoded = $this->jwt_auth_required();
		
		try {
			$data = $this->employee_service->update_data($decoded->data->id);
			$this->set_successful_response($data);
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}
}
