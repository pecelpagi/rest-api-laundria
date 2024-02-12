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

    public function test_get_all_data()
    {
        $output = $this->CI->payment_type->get_all_data();
        $this->assertEquals(count($output), 4);

        $limit = 5;
        $output = $this->CI->payment_type->get_all_data($limit);
        $this->assertEquals(count($output), 4);
        
        $search = 'piutang';
        $offset = 0;
        $output = $this->CI->payment_type->get_all_data($limit, $offset, $search);
        $this->assertEquals(count($output), 1);
        
        $offset = 1;
        $output = $this->CI->payment_type->get_all_data($limit, $offset, $search);
        $this->assertEquals(count($output), 0);
    }

    public function test_get_one_data_by()
    {
        $output = $this->CI->payment_type->get_one_data_by('id', 3);

        $this->assertIsNotBool($output);

        $output = $this->CI->payment_type->get_one_data_by('id', 666);

        $this->assertIsBool($output);
    }

    public function test_insert_data()
    {
        $form_data = array();
        $form_data['name'] = 'unit test';
        $last_insert_id = $this->CI->payment_type->insert_data($form_data);

        $output = $this->CI->payment_type->get_all_data();
        $this->assertEquals(count($output), 5);

        $this->CI->payment_type->delete_data($last_insert_id);
        $output = $this->CI->payment_type->get_all_data();
        $this->assertEquals(count($output), 4);
    }

    public function test_update_data()
    {
        $form_data = array();
        $form_data['name'] = 'unit test';
        $last_insert_id = $this->CI->payment_type->insert_data($form_data);

        $form_data = array();
        $form_data['name'] = 'update unit test';
        $form_data['id'] = $last_insert_id;
        $this->CI->payment_type->update_data($form_data);

        $search = 'update unit test';
        $limit = 1;
        $offset = 0;
        $output = $this->CI->payment_type->get_all_data($limit, $offset, $search);
        $this->assertEquals(count($output), 1);

        $this->CI->payment_type->delete_data($last_insert_id);
    }

}
