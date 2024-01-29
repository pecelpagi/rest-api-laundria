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
        $this->assertEquals($output, 10); 

        $output = $this->CI->customer->get_total_all_data('heri');
        $this->assertEquals($output, 1); 
    }

    public function test_get_all_data()
    {
        $output = $this->CI->customer->get_all_data();
        $this->assertEquals(count($output), 10);

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
}
