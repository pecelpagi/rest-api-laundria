<?php
namespace IService;

defined('BASEPATH') OR exit('No direct script access allowed');

interface CommonService {
    public function find_all();
    public function find_one($id = NULL);
    public function insert_data($form_data);
    public function update_data();
    public function delete_data($id);
}