<?php
namespace IService;

defined('BASEPATH') OR exit('No direct script access allowed');

interface MetadataService {
    public function count_number_of_pages();
    public function count_number_of_all_rows();
}