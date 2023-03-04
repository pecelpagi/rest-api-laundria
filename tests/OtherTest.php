<?php

// Path to run ./vendor/bin/phpunit --bootstrap vendor/autoload.php FileName.php
// Butuh Framework PHPUnit
use PHPUnit\Framework\TestCase;

// Class untuk run Testing.
class OtherTest extends TestCase
{
    public function test1()
    {
        $this->assertEquals(4, 4); 
    }

    public function test2()
    {
        $this->assertEquals(5, 5); 
    }
}