<?php


/**
 * This is main app class file
 * @author Phelix Juma <jumaphelix@kuzalab.com>
 * @copyright (c) 2020, Kuza Lab
 * @package Kuzalab
 */

namespace Phelix\SafaricomSDP;


use Phelix\SafaricomSDP\Exceptions\SDPException;

/**
 * Main application class.
 */
final class SDP {

    /**
     * Base url for the production environment
     * @var string $live_base_url
     */
    private $live_base_url = "https://dsvc.safaricom.com:9480/api/";
    /**
     * Base url for the testing environment
     * @var string $sandbox_base_url
     */
    private $sandbox_base_url = "https://dtsvc.safaricom.com:8480/api/";

    /**
     * Actual base url to be used. Set depending on environment of use
     * @var string $baseURL
     */
    private $baseURL;

    /**
     * API username for the CP - to be retrieved from the CP's SDP account
     * @var string $apiUsername
     */
    private $apiUsername;

    /**
     * API password for the CP - to be retrieved from the CP's SDP account
     * @var string $apiPassword
     */
    private $apiPassword;

    /**
     * The identifier for the CP
     * @var integer $cpId
     */
    public $cpId;

    /**
     * This ID is generated when a user requests for a service in SDP.
     * This parameter is mandatory while delivering content for ondemand services.
     * @var string $linkId
     */
    public $linkId;

    /**
     * Username allocated by the SDP to the partner after successful registration.
     * @var string $cpUsername
     */
    public $cpUsername;

    /**
     * Bearer token to use for all requests
     * @var string $token
     */
    public $token;

    public $request;


    /**
     * SDP constructor.
     * @param $apiUsername
     * @param $apiPassword
     * @param $cpId
     * @param null $linkId
     * @param null $cpUsername
     */
    public function __construct($apiUsername, $apiPassword, $cpId, $linkId=null, $cpUsername=null) {

        $this->apiUsername = $apiUsername;
        $this->apiPassword = $apiPassword;
        $this->cpId = $cpId;
        $this->linkId = $linkId;
        $this->cpUsername = $cpUsername;

        $this->baseURL = $this->sandbox_base_url; // by default we use the testing environment

        $this->request = new Request($this->baseURL);
    }

    /**
     * Switch to using live
     * @return $this
     */
    public function useLive() {
        $this->baseURL = $this->live_base_url;

        return $this;
    }

    /**
     * Switch to using sandbox
     * @return $this
     */
    public function useSandbox() {
        $this->baseURL = $this->sandbox_base_url;

        return $this;
    }

    /**
     * Initialize the app. Sets token
     * @throws Exceptions\SDPException
     */
    public function init() {
        $this->token = $this->generateToken($this->apiUsername, $this->apiPassword);
    }

    /**
     * Generate a token
     *
     * @param $username
     * @param $password
     * @return string
     * @throws SDPException
     */
    private function generateToken($username, $password) {

        $body = [
            'username' => $username,
            'password' => $password
        ];

        $response = $this->request->request("post", "auth/login", null, $body);

        if (!$response->success) {
            throw new SDPException($response->errorMessage, $response->errorCode);
        }

        $token = isset($response->responseBody['token']) ? $response->responseBody['token'] : "";

        return $token;
    }

    public function generateTimestamp() {
        return date("YYYYmmddHHmmss", time());
    }

}