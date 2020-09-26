<?php

namespace Kuza\Krypton\Tests\Unit;

use Kuza\Krypton\Exceptions\CustomException;
use Kuza\Krypton\Exceptions\JWTTokenException;
use PHPUnit\Framework\TestCase;

class AuthenticationTest extends TestCase {

    /**
     * @var \Kuza\Krypton\Classes\Authentication
     */
    protected $authentication;

    /**
     * Set up the test case.
     */
    public function setUp(): void {

        require_once "./tests/LoadEnv.php";

        try {

            $jwt = new \Kuza\Krypton\Classes\JWT();

            $jwt->setPublicKeyFile("../keys/jwt-rsa-public.key");
            $jwt->setPrivateKeyFile("../keys/jwt-rsa-private.key");

            $this->authentication = new \Kuza\Krypton\Classes\Authentication($jwt);

        } catch (CustomException $exception) {
            print $exception->getMessage();
        }
    }

    /**
     * test if a country exists using kenya as an example
     *
     * @throws \Kuza\Krypton\Exceptions\JWTTokenException
     */
    public function testInvalidToken() {
        $token = "qvbg";

        $this->expectException(JWTTokenException::class);

        $this->authentication->JWTAuthentication($token);
    }
}