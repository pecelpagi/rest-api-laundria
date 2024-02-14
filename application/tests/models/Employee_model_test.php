<?php

class Employee_model_test extends TestCase
{
    public function setUp(): void
    {
        $this->resetInstance();
        $this->CI->load->model('employee_model', 'employee');
    }

    public function test_get_one_data_by()
    {
        $output = $this->CI->employee->get_one_data_by('id', 1);

        $this->assertIsNotBool($output);

        $output = $this->CI->employee->get_one_data_by('id', 666);

        $this->assertIsBool($output);
    }
}
