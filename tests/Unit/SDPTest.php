<?php

namespace Phelix\SafaricomSDP\Tests\Unit;


use Phelix\SafaricomSDP\Exceptions\SDPException;
use Phelix\SafaricomSDP\SDP;
use PHPUnit\Framework\TestCase;

class SDPTest extends TestCase {


    /**
     * Set up the test case.
     */
    public function setUp(): void {

        require_once dirname(__DIR__) .DIRECTORY_SEPARATOR. "LoadEnv.php";

    }

    /**
     *
     */
    public function testInitializingSDP() {

        try {

            $sdp = new SDP($_ENV["SFC_SDP_API_USERNAME"], $_ENV["SFC_SDP_API_PASSWORD"], $_ENV["SFC_SDP_CP_ID"],
                $_ENV["SFC_SDP_LINK_ID"], $_ENV["SFC_SDP_CP_USERNAME"]);

            $sdp->useLive()->init();

            print $sdp->token;

            $this->assertNotEmpty($sdp->token);

        } catch (SDPException $exception) {

            print $exception->getMessage();

            $this->assertEmpty($exception->getMessage());
        }

    }
}