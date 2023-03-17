<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/CoreController.php';
require_once APPPATH . 'services/CustomerService.php';

class Customer extends CoreController {
    private $customer_service;

	function __construct()
    {
        parent::__construct();
		$this->customer_service = new CustomerService();
	}

    public function index_get() {
		$this->jwt_auth_required();

		try {
			$customers = $this->customer_service->get_all_data();
			$total_pages = $this->customer_service->get_total_pages();

			$additional_data = [
				'meta' => [
					'total_pages' => $total_pages,
				],
			];

			$this->set_successful_response($customers, $additional_data);
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function index_post() {
		$this->jwt_auth_required();

		try {
			$customer = $this->customer_service->create_data();
			$this->set_successful_response("OK");
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function index_put() {
		$this->jwt_auth_required();

		try {
			$customer = $this->customer_service->update_data();
			$this->set_successful_response("OK");
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function index_delete($key = '') {
		$this->jwt_auth_required();

		try {
			$customer = $this->customer_service->delete_data($key);
			$this->set_successful_response("OK");
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}
}