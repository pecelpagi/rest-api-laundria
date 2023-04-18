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
			$this->employee_service->set_employee_id($decoded->data->id);

			$employees = $this->employee_service->find_all();
			$number_of_pages = $this->employee_service->count_number_of_pages();
			$number_of_all_rows = $this->employee_service->count_number_of_all_rows();

			$additional_data = [
				'meta' => [
					'current_number_of_rows' => count($employees),
					'number_of_pages' => $number_of_pages,
					'number_of_all_rows' => $number_of_all_rows
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
			$employee = $this->employee_service->insert_data();
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
			$this->employee_service->set_employee_id($decoded->data->id);
			$data = $this->employee_service->update_data();

			$this->set_successful_response($data);
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}
}
