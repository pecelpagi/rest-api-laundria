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
			$payment_types = $this->payment_type_service->get_all_data($this);
			$total_pages = $this->payment_type_service->get_total_pages($this);

			$additional_data = [
				'meta' => [
					'total_pages' => $total_pages,
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
			$this->payment_type_service->create_data();
			$this->set_successful_response("Create payment type success");
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function index_put() {
		$this->jwt_auth_required();

		try {
			$this->payment_type_service->update_data();
			$this->set_successful_response("Update payment type success");
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function index_delete($key = '') {
		$this->jwt_auth_required();

		try {
			if (!$key) { throw new Error("Payment type ID is required"); }

			$this->payment_type_service->delete_data($key);
			$this->set_successful_response("Delete payment type success");
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}
}