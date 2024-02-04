<?php

class Laundry_package_model_test extends TestCase
{
    public function setUp(): void
    {
        $this->resetInstance();
        $this->CI->load->model('laundry_package_model', 'laundry_package');
    }

    public function test_get_all_data()
    {
        $output = $this->CI->laundry_package->get_all_data();
        $this->assertEquals(count($output), 2);

        $limit = 5;
        $output = $this->CI->laundry_package->get_all_data($limit);
        $this->assertEquals(count($output), 2);
        
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
}
