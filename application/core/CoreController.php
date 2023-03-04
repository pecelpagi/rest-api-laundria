<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'constants/EmployeeRoleConstant.php';

use chriskacerguis\RestServer\RestController;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class CoreController extends RestController {
    protected $superadmin_only;

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->superadmin_only = true;
	}

    protected function encode_data_as_token($data) {
		$key = getenv('JWT_SECRET_KEY');
		$issuedAt = time();
		// jwt valid for 10 years (60 seconds * 60 minutes * 24 hours * 10 years)
		$expirationTime = $issuedAt + 60 * 60 * 24 * (3652.5);
		$payload = array(
			"data" => $data,
			"iat" => $issuedAt,
			"exp" => $expirationTime
		);
		$jwt = JWT::encode($payload, $key, 'HS256');

		return $jwt;
	}

    private function verify_token($jwt) {
        $key = getenv('JWT_SECRET_KEY');
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

        return $decoded;
    }

    private function process_checking_employee_role($employee_role) {
        if ($employee_role !== EmployeeRoleConstant::SUPERADMIN) throw new Exception("superadmin only");
    }

    protected function jwt_auth_required($only_superadmin = FALSE) {
        try {
            $JWT_BEARER = "Bearer ";
            $headers = $this->input->request_headers();
            $key = "authorization";

            if (!isset($headers[$key])) $key = "Authorization";

            if (!isset($headers[$key])) { throw new Exception("JWT authentication required"); }
            if (strpos($headers[$key], $JWT_BEARER) === false) { throw new Exception("Invalid Bearer"); }

            $token = str_replace($JWT_BEARER, "", $headers[$key]);

            $decoded = $this->verify_token($token);

            if ($only_superadmin) $this->process_checking_employee_role($decoded->data->role);

            return $decoded;
        } catch(Exception $e) {
            $this->set_forbidden_response($e->getMessage()); 
        }
    }

    protected function set_successful_response($data, $additional_data = NULL) {
        $response_data = [
            'status' => true,
            'data' => $data,
        ];

        if ($additional_data) { $response_data = array_merge($response_data, $additional_data); }

        $this->response($response_data, 200);
    }

    protected function set_error_response($err) {
        $this->response([
            'status' => false,
            'error' => $err,
        ], 500);
    }

    protected function set_forbidden_response($msg) {
        $this->response([
            'status' => false,
            'error' => $msg,
        ], 403);
    }
}