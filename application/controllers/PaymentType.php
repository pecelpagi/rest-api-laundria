<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/CoreController.php';
require_once APPPATH . 'services/PaymentTypeService.php';

class PaymentType extends CoreController {
    private $payment_type_service;

	function __construct()
    {
        parent::__construct();
		$this->payment_type_service = new PaymentTypeService();
	}

    public function index_get() {
		$this->jwt_auth_required();

		try {
			$payment_types = $this->payment_type_service->find_all();
			$number_of_pages = $this->payment_type_service->count_number_of_pages();
			$number_of_all_rows = $this->payment_type_service->count_number_of_all_rows();

			$additional_data = [
				'meta' => [
					'current_number_of_rows' => count($payment_types),
					'number_of_pages' => $number_of_pages,
					'number_of_all_rows' => $number_of_all_rows
				],
			];

			$this->set_successful_response($payment_types, $additional_data);
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function index_post() {
		$this->jwt_auth_required();

		try {
			$this->payment_type_service->insert_data();
			$this->set_successful_response("OK");
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function index_put() {
		$this->jwt_auth_required();

		try {
			$this->payment_type_service->update_data();
			$this->set_successful_response("OK");
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function index_delete($key = '') {
		$this->jwt_auth_required();

		try {
			$this->payment_type_service->delete_data($key);
			$this->set_successful_response("OK");
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}
}