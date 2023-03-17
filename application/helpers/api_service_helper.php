<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function get_column_constant_keys_from_class($classname) {
    $reflector = new ReflectionClass($classname);
    $constants = $reflector->getConstants();

    return $constants;
}

function is_form_data_valid($form_data, $column_constant_keys) {
    $form_data_keys = array_keys($form_data);

    foreach ($form_data_keys as $key) {
        $is_column_exist = array_search($key, $column_constant_keys);

        if (!$is_column_exist) { throw new Exception("unknown column:  {$key}"); }
    }
}