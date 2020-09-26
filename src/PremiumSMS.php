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
     * @param string $requestId Client generated transaction id for tracking purposes
     * @param string $offerCode This is allocated by partner or Safaricom on successful service creation from SDP
     * @param string $linkId This ID is generated when a user requests for a service in SDP.
     * @param integer $phoneNumber The phone number of the user to receive the message. Format is 2547...
     * @param string $message The content/message to send to the user
     * @return array
     */
    public function sendSMS($requestId, $offerCode, $linkId, $phoneNumber, $message) {

        $body = [
            "requestId"=> $requestId,
            "requestTimeStamp"=> $this->SDP->generateTimestamp(),
            "channel"=> "3",
            "operation"=> "SendSMS",
            "requestParam" => [
                "data"=> [
                    [
                        "name"=> "LinkId",
                        "value"=> $linkId
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

        return Utils::getResponse($response);
    }


}