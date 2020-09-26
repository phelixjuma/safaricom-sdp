<?php


/**
 * Handle response formatting
 * @author Phelix Juma <jumaphelix@kuzalab.com>
 * @copyright (c) 2020, Kuza Lab
 * @package Kuzalab
 */

namespace Phelix\SafaricomSDP;


final class Response {

    /**
     * Get response
     *
     * @param Request $response
     * @return array
     */
    public static function getResponse(Request $response) {

        return [
            "success" => $response->success,
            "statusCode" => $response->statusCode,
            "statusText" => $response->statusText,
            "errorCode" => $response->errorCode,
            "errorMessage" => $response->errorMessage,
            "data" => $response->responseBody,
            "debugTrace" => $_ENV['SFC_SDP_DEBUG'] == 1 ? [
                'request' => $response->debugRequestTrace,
                'response' => $response->debugResponseTrace
            ] : null
        ];
    }
}