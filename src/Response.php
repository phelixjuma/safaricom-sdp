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
            "errorCode" => $response->errorCode,
            "errorMessage" => $response->errorMessage,
            "data" => $response->responseBody,
            "debugTrace" => [
                'request' => $response->debugRequestTrace,
                'response' => $response->debugResponseTrace
            ]
        ];
    }
}