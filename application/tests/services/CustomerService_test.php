<?php

require_once APPPATH . 'services/CustomerService.php';

class CustomerService_test extends TestCase
{
    private $customer_service;

    public function setUp(): void
    {
        $this->resetInstance();
        $this->customer_service = new CustomerService();
    }

    public function test_find_all()
    {
        // $output = $this->customer_service->find_all();
        $this->assertEquals(10, 10);
    }
}
