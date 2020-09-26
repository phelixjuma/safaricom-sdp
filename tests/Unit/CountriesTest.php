<?php

use PHPUnit\Framework\TestCase;

class CountriesTest extends TestCase {

    /**
     * @var \Kuza\Krypton\Classes\Countries
     */
    protected $countries;

    /**
     * Set up the test case.
     */
    public function setUp(): void {

        $this->countries = new \Kuza\Krypton\Classes\Countries();
    }

    /**
     * test if a country exists using kenya as an example
     *
     * @throws Exception
     */
    public function testCountryExists() {
        $this->assertArrayHasKey('KE', $this->countries::getCountries());
    }

    public function testCountryName() {
        $country = $this->countries::getCountries()['KE']; // get the country kenya

        $this->assertEquals("KENYA", $country['name']);

        $this->assertEquals('254', $country['code']);
    }
}