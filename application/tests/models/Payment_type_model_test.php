<?php

class Payment_type_model_test extends TestCase
{
    public function setUp(): void
    {
        $this->resetInstance();
        $this->CI->load->model('payment_type_model', 'payment_type');
    }

    public function test_get_total_all_data()
    {
        $output = $this->CI->payment_type->get_total_all_data();
        $this->assertEquals($output, 4); 

        $output = $this->CI->payment_type->get_total_all_data('piutang');
        $this->assertEquals($output, 1); 
    }
}
