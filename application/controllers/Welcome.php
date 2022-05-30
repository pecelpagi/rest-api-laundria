<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Firebase\JWT\JWT;

require_once APPPATH . 'core/CoreController.php';
require_once APPPATH . 'services/SummaryService.php';
require_once APPPATH . 'services/CompanyProfileService.php';

class Welcome extends CoreController {

	function __construct()
    {
        parent::__construct();
		$this->summary_service = new SummaryService();
		$this->company_profile_service = new CompanyProfileService();
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index_get()
	{
		$this->set_successful_response("API Laundria is UP");
	}

	public function dashboard_summary_get($id = '') {
		$this->jwt_auth_required();

		try {
			if ($id) { $this->get_one_data($id); }

			$data = $this->summary_service->get_summary_data();

			$this->set_successful_response($data);
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

	public function company_profile_get() {
		try {
			$data = $this->company_profile_service->get_data();
			$this->set_successful_response($data);
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}
	
	public function company_profile_put() {
		$this->jwt_auth_required($this->superadmin_only);

		try {
			$this->company_profile_service->update_data();
			$this->set_successful_response("Update company profile success");
		} catch (Throwable $e) {
			$this->set_error_response($e->getMessage());
		}
	}

}
