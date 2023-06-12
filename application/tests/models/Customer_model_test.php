<?php
/**
 * Part of ci-phpunit-test
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/ci-phpunit-test
 */

class Customer_model_test extends TestCase
{
    public function setUp(): void
    {
        $this->resetInstance();
        $this->CI->load->model('customer_model', 'customer');
    }

    public function test_count_data()
    {
        $output = $this->CI->customer->get_total_all_data('heri');
        $this->assertEquals($output, 1); 
    }
}
