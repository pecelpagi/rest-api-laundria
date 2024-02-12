<?php

class Company_profile_model_test extends TestCase
{
    public function setUp(): void
    {
        $this->resetInstance();
        $this->CI->load->model('company_profile_model', 'company_profile');
    }

    public function test_get_one_data()
    {
        $output = $this->CI->company_profile->get_one_data();
        $this->assertIsNotBool($output);
    }

    public function test_update_data()
    {
        $form_data = array();
        $form_data['id'] = '1';
        $form_data['name'] = 'Unit Test';
        $form_data['addr'] = 'Address Unit Test';
        $form_data['email'] = 'test@example.com';
        $form_data['phone'] = '12345';
        $this->CI->company_profile->update_data($form_data);

        $output = $this->CI->company_profile->get_one_data();
        $this->assertEquals($output->name, 'Unit Test');
        $this->assertEquals($output->addr, 'Address Unit Test');
        $this->assertEquals($output->email, 'test@example.com');
        $this->assertEquals($output->phone, '12345');

        $form_data = array();
        $form_data['id'] = '1';
        $form_data['name'] = 'Barokah Laundry';
        $form_data['addr'] = 'Jl. MT. Haryono Gg.XXI No.23, RT.04/RW.06, Dinoyo, Kec. Lowokwaru';
        $form_data['email'] = 'galuhrmdh@gmail.com';
        $form_data['phone'] = '085732992240';
        $this->CI->company_profile->update_data($form_data);

        $output = $this->CI->company_profile->get_one_data();
        $this->assertEquals($output->name, 'Barokah Laundry');
        $this->assertEquals($output->addr, 'Jl. MT. Haryono Gg.XXI No.23, RT.04/RW.06, Dinoyo, Kec. Lowokwaru');
        $this->assertEquals($output->email, 'galuhrmdh@gmail.com');
        $this->assertEquals($output->phone, '085732992240');
    }
}
