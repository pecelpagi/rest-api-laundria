<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/CoreController.php';
require_once APPPATH . 'services/LaundryPackageService.php';

class LaundryPackage extends CoreController {
    private $laundry_package_service;

	function __construct()
    {
        parent::__construct();
		$this->laundry_package_service = new LaundryPackageService();
	}

    public function index_get() {
		$this->jwt_auth_required();

		try {
			$laundry_packages = $this->laundry_package_service->find_all();
			$number_of_pages = $this->laundry_package_service->count_number_of_pages();
			$number_of_all_rows = $this->laundry_package_service->count_number_of_all_rows();

			$additional_data = [
				'meta' => [
					'current_number_of_rows' => count($laundry_packages),
					'number_of_pages' => $number_of_pages,
					'number_of_all_rows' => $number_of_all_rows
				],
			];

			$this->set_successful_response($laundry_packages, $additional_data);
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function index_post() {
		$this->jwt_auth_required();

		try {
			$this->laundry_package_service->insert_data();
			$this->set_successful_response("OK");
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function index_put() {
		$this->jwt_auth_required();

		try {
			$this->laundry_package_service->update_data();
			$this->set_successful_response("OK");
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function index_delete($key = '') {
		$this->jwt_auth_required();

		try {
			$this->laundry_package_service->delete_data($key);
			$this->set_successful_response("OK");
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}
}