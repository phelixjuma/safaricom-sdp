<?php


/**
 * Premium SMS
 *
 * @author Phelix Juma <jumaphelix@kuzalab.com>
 * @copyright (c) 2020, Kuza Lab
 * @package Kuzalab
 */

namespace Phelix\SafaricomSDP;



final class PremiumSMS {

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
     * Send a premium message to a user
     *
     * @param $requestId
     * @param $offerCode
     * @param $phoneNumber
     * @param $message
     * @return array
     */
    public function sendSMS($requestId, $offerCode, $phoneNumber, $message) {

        $body = [
            "requestId"=> $requestId,
            "requestTimeStamp"=> $this->SDP->generateTimestamp(),
            "channel"=> "3",
            "operation"=> "SendSMS",
            "requestParam" => [
                "data"=> [
                    [
                        "name"=> "LinkId",
                        "value"=> $this->SDP->linkId
                    ],
                    [
                        "name"=> "Msisdn",
                        "value"=> $phoneNumber
                    ],
                    [
                        "name"=> "Content",
                        "value"=> $message
                    ],
                    [
                        "name"=> "OfferCode",
                        "value"=> $offerCode
                    ],
                    [
                        "name"=> "CpId",
                        "value"=> $this->SDP->cpId
                    ]
                ]
            ]
        ];

        $response = $this->SDP->request->request("post", "public/SDP/sendSMSRequest", $this->SDP->token, $body);

        return Response::getResponse($response);
    }


}