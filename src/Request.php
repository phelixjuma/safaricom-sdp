<?php


/**
 * Handle requests
 * @author Phelix Juma <jumaphelix@kuzalab.com>
 * @copyright (c) 2020, Kuza Lab
 * @package Kuzalab
 */

namespace Phelix\SafaricomSDP;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;

final class Request {

    public $client;

    private $headers = [
        'Content-Type' => 'application/json',
        'X-Requested-With' => 'XMLHttpRequest'
    ];

    public $success = true;

    public $statusCode;

    public $statusText;

    public $responseBody;

    public $errorCode;

    public $errorMessage;

    public $debugRequestTrace;
    public $debugResponseTrace;

    /**
     * Request constructor.
     * @param $baseURI
     */
    public function __construct($baseURI) {
        $this->client = new Client(['base_uri' => $baseURI]);
    }

    /**
     * Make a http request
     *
     * @param $method
     * @param $uri
     * @param null $token
     * @param null $body
     * @param null $query
     * @return $this
     */
    public function request($method, $uri, $token=null, $body=null, $query=null) {

        try {

            if (!is_null($token)) {
                $this->headers['X-Authorization'] = "Bearer $token";
            }

            $options['headers'] = $this->headers;

            if (!is_null($body)) {
                $options['body'] = $body;
            }
            if (!is_null($query)) {
                $options['query'] = $query;
            }

            $response = $this->client->request($method, $uri, $options);

            $this->statusCode = $response->getStatusCode();
            $this->statusText = $response->getReasonPhrase();
            $this->responseBody = $response->getBody();

        } catch (RequestException $e) {

            $this->success = false;

            $this->debugRequestTrace = Psr7\str($e->getRequest());
            $this->debugResponseTrace = Psr7\str($e->getResponse());

            if ($e->hasResponse()) {

                // we set the response
                $response = $e->getResponse();

                $responseBody = $response->getBody();

                $this->statusCode = $response->getStatusCode();
                $this->statusText = $response->getReasonPhrase();

                $this->errorCode = isset($responseBody['errorCode']) ? $responseBody['errorCode'] : "";
                $this->errorMessage = isset($responseBody['message']) ? $responseBody['message'] : "";

            }
        } catch (GuzzleException $e) {

            $this->success = false;

            $this->statusCode = 500;
            $this->statusText = "Internal Server Error";

            $this->errorCode = $this->statusCode;
            $this->errorMessage = $this->statusText;
        }

        return $this;
    }

}