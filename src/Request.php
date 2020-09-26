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
     * @param null $query_params
     * @return $this
     */
    public function request($method, $uri, $token=null, $body=null, $query_params=null) {

        try {

            if (!is_null($token)) {
                $this->headers['X-Authorization'] = "Bearer $token";
            }

            $options['headers'] = $this->headers;
            $options['verify'] = false;

            if (!is_null($body)) {
                $options['json'] = $body;
            }
            if (!is_null($query_params)) {
                $options['query'] = $query_params;
            }

            $response = $this->client->request($method, $uri, $options);
            //$response = $this->sendCurlRequest($method, $uri, $this->headers, $body, $query_params);

            $this->statusCode = $response->getStatusCode();
            $this->statusText = $response->getReasonPhrase();
            $this->responseBody = json_decode($response->getBody()->getContents(), JSON_FORCE_OBJECT);

        } catch (RequestException $e) {

            $this->success = false;

            $this->debugRequestTrace = $e->getRequest();
            $this->debugResponseTrace = $e->getResponse();

            if ($e->hasResponse()) {

                // we set the response
                $response = $e->getResponse();

                $responseBody = json_decode($response->getBody()->getContents(), JSON_FORCE_OBJECT);

                $this->statusCode = $response->getStatusCode();
                $this->statusText = $response->getReasonPhrase();

                $this->errorCode = isset($responseBody['errorCode']) ? $responseBody['errorCode'] : "";
                $this->errorMessage = isset($responseBody['message']) ? $responseBody['message'] : "";
            } else {

                $this->statusCode = 500;
                $this->statusText = "Internal Server Error";

                $this->errorCode = $e->getCode();
                $this->errorMessage = $e->getMessage();

            }

        } catch (GuzzleException $e) {

            $this->success = false;

            $this->debugRequestTrace = $e->getTrace();

            $this->statusCode = 500;
            $this->statusText = "Internal Server Error";

            $this->errorCode = $e->getCode();
            $this->errorMessage = $e->getMessage();
        }

        return $this;
    }
}