<?php

namespace Kuza\Krypton\Tests\Unit;

use Kuza\Krypton\Classes\ConfigurationException;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase {

    /**
     * @var \Kuza\Krypton\Config\Config
     */
    protected $config;

    /**
     * Set up the test case.
     */
    public function setUp(): void {
        require_once "./tests/LoadEnv.php";
        $this->config = new \Kuza\Krypton\Config\Config();
    }

    /**
     * test if a country exists using kenya as an example
     *
     * @throws ConfigurationException
     */
    public function testDbHost() {
       $this->assertEquals('localhost', $this->config::getDBHost());
    }
}