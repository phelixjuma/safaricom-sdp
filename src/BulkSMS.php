<?php


/**
 * Bulk SMS
 *
 * @author Phelix Juma <jumaphelix@kuzalab.com>
 * @copyright (c) 2020, Kuza Lab
 * @package Kuzalab
 */

namespace Phelix\SafaricomSDP;



final class BulkSMS {

    /**
     * @var SDP
     */
    private $SDP;

    /**
     * Subscription constructor.
     * @param SDP $SDP
     */
    public function __construct(SDP $SDP) {
        $this->SDP = $SDP;
    }

    /**
     * Send Bulk SMS to a group of users
     *
     * @param $requestId
     * @param $packageId
     * @param $originatingAddress
     * @param $recipients
     * @param $message
     * @param $callback
     * @return array
     */
    public function sendSMS($requestId, $packageId, $originatingAddress, $recipients, $message, $callback) {

        $body =  [
            "timeStamp" => $this->SDP->generateTimestamp(),
            "dataSet"   => [
                [
                    "userName"          => $this->SDP->cpUsername,
                    "channel"           => "sms",
                    "packageId"         => $packageId,
                    "oa"                => $originatingAddress,
                    "msisdn"            => implode(",", $recipients),
                    "message"           => $message,
                    "uniqueId"          => $requestId,
                    "actionResponseURL" => $callback
                ]
            ]
        ];

        $response = $this->SDP->request->request("post", "public/CMS/bulksms", $this->SDP->token, $body);

        return Response::getResponse($response);
    }


}