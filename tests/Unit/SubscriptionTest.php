<?php

namespace Phelix\SafaricomSDP\Tests\Unit;


use Phelix\SafaricomSDP\Exceptions\SDPException;
use Phelix\SafaricomSDP\SDP;
use Phelix\SafaricomSDP\Subscription;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase {


    /**
     * @var Subscription $subscription
     */
    protected $subscription;

    /**
     * Set up the test case.
     */
    public function setUp(): void {

        require_once dirname(__DIR__) .DIRECTORY_SEPARATOR. "LoadEnv.php";

        try {

            $sdp = new SDP($_ENV["SFC_SDP_API_USERNAME"], $_ENV["SFC_SDP_API_PASSWORD"], $_ENV["SFC_SDP_CP_ID"]);

            $sdp->useLive()->init();

            $this->subscription = new Subscription($sdp);

        } catch (SDPException $exception) {

            print $exception->getMessage();

            $this->assertEmpty($exception->getMessage());
        }
    }

    /**
     * Test subscription
     */
    public function testActivateSubscription() {

        $response = $this->subscription->activateSubscription(1, 12, 729941254);

        print_r($response);
    }

    /**
     * Test subscription
     */
    public function testDeactivateSubscription() {

        $response = $this->subscription->deactivateSubscription(1, 12, 729941254);

        print_r($response);
    }
}