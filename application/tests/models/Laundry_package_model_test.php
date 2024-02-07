<?php

class Laundry_package_model_test extends TestCase
{
    public function setUp(): void
    {
        $this->resetInstance();
        $this->CI->load->model('laundry_package_model', 'laundry_package');
    }

    public function test_get_total_all_data()
    {
        $output = $this->CI->laundry_package->get_total_all_data();
        $this->assertEquals($output, 3); 

        $output = $this->CI->laundry_package->get_total_all_data('setrika');
        $this->assertEquals($output, 1); 
    }

    public function test_get_all_data()
    {
        $output = $this->CI->laundry_package->get_all_data();
        $this->assertEquals(count($output), 3);

        $limit = 5;
        $output = $this->CI->laundry_package->get_all_data($limit);
        $this->assertEquals(count($output), 3);
        
        $search = 'cuci kering';
        $offset = 0;
        $output = $this->CI->laundry_package->get_all_data($limit, $offset, $search);
        $this->assertEquals(count($output), 1);
        
        $offset = 1;
        $output = $this->CI->laundry_package->get_all_data($limit, $offset, $search);
        $this->assertEquals(count($output), 0);
    }

    public function test_get_one_data_by()
    {
        $output = $this->CI->laundry_package->get_one_data_by('id', 2);

        $this->assertIsNotBool($output);

        $output = $this->CI->laundry_package->get_one_data_by('id', 666);

        $this->assertIsBool($output);
    }

    public function test_insert_data()
    {
        $form_data = array();
        $form_data['name'] = 'unit test';
        $form_data['price'] = '1000';
        $last_insert_id = $this->CI->laundry_package->insert_data($form_data);

        $output = $this->CI->laundry_package->get_all_data();
        $this->assertEquals(count($output), 4);

        $this->CI->laundry_package->delete_data($last_insert_id);
        $output = $this->CI->laundry_package->get_all_data();
        $this->assertEquals(count($output), 3);
    }

    public function test_update_data()
    {
        $form_data = array();
        $form_data['name'] = 'laundry package unit test 2';
        $form_data['price'] = '1000';
        $form_data['id'] = '21';
        $this->CI->laundry_package->update_data($form_data);

        $search = 'laundry package unit test 2';
        $limit = 1;
        $offset = 0;
        $output = $this->CI->laundry_package->get_all_data($limit, $offset, $search);
        $this->assertEquals(count($output), 1);
    }
}
