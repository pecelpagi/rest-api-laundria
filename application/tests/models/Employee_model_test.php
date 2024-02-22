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

    public function test_insert_data()
    {
        $form_data = array();
        $form_data['fullname'] = 'Employee Unit Test';
        $form_data['username'] = 'unittest';
        $form_data['email'] = 'unittest@example.com';
        $form_data['addr'] = 'test address';
        $form_data['phone'] = '123456';
        $last_insert_id = $this->CI->employee->insert_data($form_data);

        $output = $this->CI->employee->get_one_data_by('id', $last_insert_id);
        $this->assertIsNotBool($output);

        $this->CI->employee->delete_data($last_insert_id);

        $output = $this->CI->employee->get_one_data_by('id', $last_insert_id);
        $this->assertIsBool($output);
    }
}
