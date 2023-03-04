<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function validation_errors_text() {
    $err = strip_tags(validation_errors());
    $err = str_replace("\n", "", $err);

    return $err;
}