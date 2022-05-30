<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/CoreController.php';
require_once APPPATH . 'services/SaleService.php';

class Sale extends CoreController {
    private $sale_service;

	function __construct()
    {
        parent::__construct();
		$this->sale_service = new SaleService();
	}

	private function get_one_data($id) {
		try {
			$sale = $this->sale_service->get_one_data($id);

			$this->set_successful_response($sale);
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

    public function index_get($id = '') {
		$this->jwt_auth_required();

		try {
			if ($id) { $this->get_one_data($id); }

			$sales = $this->sale_service->get_all_data($this);
			$total_pages = $this->sale_service->get_total_pages($this);

			$additional_data = [
				'meta' => [
					'total_pages' => $total_pages,
				],
			];

			$this->set_successful_response($sales, $additional_data);
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function index_post() {
		$this->jwt_auth_required();

		try {
			$this->sale_service->create_data();
			$this->set_successful_response("Create order success");
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function index_put() {
		$this->jwt_auth_required();

		try {
			$this->sale_service->update_data();
			$this->set_successful_response("Update order success");
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function index_delete($key = '') {
		$this->jwt_auth_required();

		try {
			if (!$key) { throw new Error("Order ID is required"); }

			$this->sale_service->delete_data($key);
			$this->set_successful_response("Delete order success");
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}
	
	public function sales_report_get() {
		$this->jwt_auth_required();

		try {
			$data = $this->sale_service->get_data_by_range_date($this);
			$this->set_successful_response($data);
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}
}