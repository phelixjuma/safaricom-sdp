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
     * @param null $linkId
     * @return array
     */
    public function sendSMS($requestId, $offerCode, $phoneNumber, $message, $linkId=null) {

        $data = [
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
        ];

        if (!is_null($linkId)) {
            $data[] = [
                "name"=> "LinkId",
                "value"=> $linkId
            ];
        }

        $body = [
            "requestId"=> $requestId,
            //"requestTimeStamp"=> $this->SDP->generateTimestamp(),
            "channel"=> "APIGW",
            "operation"=> "SendSMS",
            "requestParam" => [
                "data"=> $data
            ]
        ];

        $response = $this->SDP->request->request("post", "public/SDP/sendSMSRequest", $this->SDP->token, $body);

        return Utils::getResponse($response, $this->SDP->debugLevel);
    }


}