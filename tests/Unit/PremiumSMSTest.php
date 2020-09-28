<?php

namespace Phelix\SafaricomSDP\Tests\Unit;


use Phelix\SafaricomSDP\Exceptions\SDPException;
use Phelix\SafaricomSDP\PremiumSMS;
use Phelix\SafaricomSDP\SDP;
use Phelix\SafaricomSDP\Subscription;
use PHPUnit\Framework\TestCase;

class PremiumSMSTest extends TestCase {


    /**
     * @var PremiumSMS $premiumSMS
     */
    protected $premiumSMS;

    /**
     * Set up the test case.
     */
    public function setUp(): void {

        require_once dirname(__DIR__) .DIRECTORY_SEPARATOR. "LoadEnv.php";

        try {

            $sdp = new SDP($_ENV["SFC_SDP_API_USERNAME"], $_ENV["SFC_SDP_API_PASSWORD"], $_ENV["SFC_SDP_CP_ID"]);

            $sdp->useLive()->init();

            $this->premiumSMS = new PremiumSMS($sdp);

        } catch (SDPException $exception) {

            print $exception->getMessage();

            $this->assertEmpty($exception->getMessage());
        }
    }

    /**
     * Test subscription
     */
    public function testSendSMS() {

        $response = $this->premiumSMS->sendSMS('254722170907', '0010023000811', "1",'254722170907', "Phelix Juma");

        print_r($response);
    }
}