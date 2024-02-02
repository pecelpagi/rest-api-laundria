<?php

class Customer_model_test extends TestCase
{
    public function setUp(): void
    {
        $this->resetInstance();
        $this->CI->load->model('customer_model', 'customer');
    }

    public function test_get_total_all_data()
    {
        $output = $this->CI->customer->get_total_all_data();
        $this->assertEquals($output, 9); 

        $output = $this->CI->customer->get_total_all_data('heri');
        $this->assertEquals($output, 1); 
    }

    public function test_get_all_data()
    {
        $output = $this->CI->customer->get_all_data();
        $this->assertEquals(count($output), 9);

        $limit = 5;
        $output = $this->CI->customer->get_all_data($limit);
        $this->assertEquals(count($output), 5);
        
        $search = 'heri';
        $offset = 0;
        $output = $this->CI->customer->get_all_data($limit, $offset, $search);
        $this->assertEquals(count($output), 1);
        
        $offset = 1;
        $output = $this->CI->customer->get_all_data($limit, $offset, $search);
        $this->assertEquals(count($output), 0);
    }

    public function test_insert_data()
    {
        $form_data = array();
        $form_data['fullname'] = 'Test Add Customer';
        $form_data['addr'] = '-';
        $form_data['phone'] = '085645781121';
        $last_insert_id = $this->CI->customer->insert_data($form_data);

        $output = $this->CI->customer->get_all_data();
        $this->assertEquals(count($output), 10);

        $this->CI->customer->delete_data($last_insert_id);
        $output = $this->CI->customer->get_all_data();
        $this->assertEquals(count($output), 9);
    }

    public function test_update_data()
    {
        $form_data = array();
        $form_data['fullname'] = 'Customer Unit Test 2';
        $form_data['addr'] = 'Jl. Mawar No. 8';
        $form_data['phone'] = '085645781435';
        $form_data['id'] = '18';
        $this->CI->customer->update_data($form_data);

        $search = 'customer unit test 2';
        $limit = 1;
        $offset = 0;
        $output = $this->CI->customer->get_all_data($limit, $offset, $search);
        $this->assertEquals(count($output), 1);
    }

    public function test_delete_data()
    {
        $this->CI->customer->delete_data(19);

        $output = $this->CI->customer->get_all_data();
        $this->assertEquals(count($output), 9);
    }

    public function test_get_one_data_by()
    {
        $output = $this->CI->customer->get_one_data_by('id', 18);

        $this->assertIsNotBool($output);
    }
}
