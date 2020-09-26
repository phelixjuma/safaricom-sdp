<?php

namespace Kuza\Krypton\Tests\Unit;

use Kuza\Krypton\Classes\Data;
use PHPUnit\Framework\TestCase;

class DataTest extends TestCase {

    /**
     * @var \Kuza\Krypton\Config\Config
     */
    protected $config;

    /**
     * Set up the test case.
     */
    public function setUp(): void {
    }


    /**
     * test mapping array to an object
     */
    public function testMapArrayToObject() {

        $array = [
            "a" => "users"
        ];

        $x = (object) [
            'a' => '',
            'b' => '',
            'c' => ''
        ];

        Data::mapArrayToObject($x, $array);

        $this->assertEquals('users', $x->a);

    }
}