Safaricom SDP SDK by Phelix Juma
================================================

This is a PHP SDK wrapper for Safaricom SDP. Safaricom SDK allows PRSP content providers to integrate their systems 
with Safaricom's platform. 

Included SDP Services 
=======================
- Token
   - Get Token API : getting a token
   - Refresh Token API : getting a refresh token
- Subscription API
   - Activate API  : for subscribing a new user
   - Deactivate API : for deactivating a user from the subscription
- Premium SMS
    - SendSMS API : Sending SMS to a user in a premium service
- Bulk SMS
    - Bulk SMS API : Sending Bulk SMS to a set of users


Requirements
============

* PHP >= 7.1
* ext-json
* ext-openssl
* ext-mbstring
* ext-openssl
* ext-iconv
* ext-curl
* guzzlehttp/guzzle: "^7.1"
    

Installation
============

    composer require phelix/safaricom-sdp


How To test
===========

To test the package, copy the file "LoadEnv.php.example" in src/tests directory to "LoadEnv.php" and fill in the
configuration values required and then run the following command

    phpunit test


# Documentation

The docs folder has the technical documentation of each of the classes,methods, properties, namespaces et al. In 
order for you to make references to know what a class does or what a function does or what each of the method 
parameters mean, then the docs have an elaborate description for each of them. 


## 1. Send Premium SMS

This is used when sending a premium SMS service to a user; typically an MT message.

```php

<?php 

    use Phelix\SafaricomSDP\SDP;
    use Phelix\SafaricomSDP\PremiumSMS;
    use Phelix\SafaricomSDP\Exceptions\SDPException;

    try {
        
        // We instantiate the SDP class; passing to it, the api username, api password and the cp id
        $sdp = new SDP($_ENV["SFC_SDP_API_USERNAME"], $_ENV["SFC_SDP_API_PASSWORD"], $_ENV["SFC_SDP_CP_ID"]);
    
        // By default, SDP will use the sandbox apis (test bed), call useLive() method to use production APIs
        $sdp->useLive()->init();
    
        // We instantiate the premium SMS class and pass to it the SDP class
        $premiumSMS = new PremiumSMS($sdp);
        
        // We send SMS
        $requestId = "1234"; // Generate an id that you can use for tracking/logging purposes
        $offerCode = "23456"; // The service for which the sms is being sent
        $linkId = "233348438989"; // This ID is generated when a user requests for a service in SDP.
        $phoneNumber = "254712345678"; // The phone number of the user to receive the message. Format is 2547...
        $message = "This is a test message";
        
        $response = $premiumSMS->sendSMS($requestId, $offerCode, $linkId, $phoneNumber, $message);
        
        // You can check the response and do logging as necessary. Ensure that the status is correctly logged to
        // help in tracking if the request was successful or failed
        if ($response['success']) {
            // Request sent successfully to SDP. Should not be confused to mean the SMS has been successfully 
            // delivered to the user
            
            // We can now check the status of the sent message
            if (isset($response['data']['responseParam'])) {
                
                if ($response['data']['responseParam']['status'] == 1) {
                    // failed to send SMS
                    print("SMS sending failed with status code" . $response['data']['responseParam']['statusCode']. "Error says: ". $response['data']['responseParam']['description']);
                } else {
                    // SMS sending a success. Confirm with the status code and the description and also log them for tracking purposes
                    print($response['data']['responseParam']['description']);
                }
            } else {
                // something wrong seems to have happened. No response param sent back. Handle this for tracking
                print("Seems the response hasn't been sent. Best to assume it failed to send the sms");
            }
            
        } else {
            // Failed to send the request. Could be network, authentication, authorization errors et al 
            print("Sending request failed with message" . $response['errorMessage']);
        }
        
    
    } catch (SDPException $ex) {
        
        // You can do any error logging operations at this point. Exceptions here would most likely occur 
        // at the point of token generation
        
        print $ex->getMessage();
        
    }
```

## 1. Activate a Subscription

This is used when subscribing a new user to a service

```php

<?php 

    use Phelix\SafaricomSDP\SDP;
    use Phelix\SafaricomSDP\Subscription;
    use Phelix\SafaricomSDP\Exceptions\SDPException;

    try {
        
        // We instantiate the SDP class; passing to it, the api username, api password and the cp id
        $sdp = new SDP($_ENV["SFC_SDP_API_USERNAME"], $_ENV["SFC_SDP_API_PASSWORD"], $_ENV["SFC_SDP_CP_ID"]);
    
        // By default, SDP will use the sandbox apis (test bed), call useLive() method to use production APIs
        $sdp->useLive()->init();
    
        // We instantiate the premium SMS class and pass to it the SDP class
        $subscription = new Subscription($sdp);
        
        // We send SMS
        $requestId = "1234"; // Generate an id that you can use for tracking/logging purposes
        $offerCode = "23456"; // The service for which the sms is being sent
        $phoneNumber = "254712345678"; // The phone number of the user to receive the message. Format is 2547...
        
        $response = $subscription->activateSubscription($requestId, $offerCode, $phoneNumber);
        
        // You can check the response and do logging as necessary. Ensure that the status is correctly logged to
        // help in tracking if the request was successful or failed
        if ($response['success']) {
            // Request sent successfully to SDP. Should not be confused to mean the activation has been successfully done 
            
            // We can now check the status of the sent message
            if (isset($response['data']['responseParam'])) {
                
                if ($response['data']['responseParam']['status'] == 1) {
                    // failed to activate subscription
                    print("Activation failed" . $response['data']['responseParam']['statusCode']. "Error says: ". $response['data']['responseParam']['description']);
                } else {
                    // Activation a success. Confirm with the status code and the description and also log them for tracking purposes
                    // Not advisable to assume that this is a success. Always confirm with status code.
                    print($response['data']['responseParam']['description']);
                }
            } else {
                // something wrong seems to have happened. No response param sent back. Handle this for tracking
                print("Seems the response hasn't been sent. Best to assume it failed to activate the user");
            }
            
        } else {
            // Failed to send the request. Could be network, authentication, authorization errors et al 
            print("Failed to send the request" . $response['errorMessage']);
        }
        
    
    } catch (SDPException $ex) {
        
        // You can do any error logging operations at this point. Exceptions here would most likely occur 
        // at the point of token generation
        
        print $ex->getMessage();
        
    }
```

## 3. Deactivate a Subscription

This is used when unsubscribing a user from a service

```php

<?php 

    use Phelix\SafaricomSDP\SDP;
    use Phelix\SafaricomSDP\Subscription;
    use Phelix\SafaricomSDP\Exceptions\SDPException;

    try {
        
        // We instantiate the SDP class; passing to it, the api username, api password and the cp id
        $sdp = new SDP($_ENV["SFC_SDP_API_USERNAME"], $_ENV["SFC_SDP_API_PASSWORD"], $_ENV["SFC_SDP_CP_ID"]);
    
        // By default, SDP will use the sandbox apis (test bed), call useLive() method to use production APIs
        $sdp->useLive()->init();
    
        // We instantiate the premium SMS class and pass to it the SDP class
        $subscription = new Subscription($sdp);
        
        // We send SMS
        $requestId = "1234"; // Generate an id that you can use for tracking/logging purposes
        $offerCode = "23456"; // The service for which the sms is being sent
        $phoneNumber = "254712345678"; // The phone number of the user to receive the message. Format is 2547...
        
        $response = $subscription->deactivateSubscription($requestId, $offerCode, $phoneNumber);
        
        // You can check the response and do logging as necessary. Ensure that the status is correctly logged to
        // help in tracking if the request was successful or failed
        if ($response['success']) {
            // Request sent successfully to SDP. Should not be confused to mean the deactivation has been successfully done 
            
            // We can now check the status of the sent message
            if (isset($response['data']['responseParam'])) {
                
                if ($response['data']['responseParam']['status'] == 1) {
                    // failed to activate subscription
                    print("Activation failed" . $response['data']['responseParam']['statusCode']. "Error says: ". $response['data']['responseParam']['description']);
                } else {
                    // Activation a success. Confirm with the status code and the description and also log them for tracking purposes
                    // Not advisable to assume that this is a success. Always confirm with status code.
                    print($response['data']['responseParam']['description']);
                }
            } else {
                // something wrong seems to have happened. No response param sent back. Handle this for tracking
                print("Seems the response hasn't been sent. Best to assume it failed to deactivate the user");
            }
            
        } else {
            // Failed to send the request. Could be network, authentication, authorization errors et al 
            print("Failed to send the request" . $response['errorMessage']);
        }
        
    
    } catch (SDPException $ex) {
        
        // You can do any error logging operations at this point. Exceptions here would most likely occur 
        // at the point of token generation
        
        print $ex->getMessage();
        
    }
```

## 4. Send Bulk SMS

This is used when sending bulk sms to users 

```php

<?php 

    use Phelix\SafaricomSDP\SDP;
    use Phelix\SafaricomSDP\BulkSMS;
    use Phelix\SafaricomSDP\Exceptions\SDPException;

    try {
        
        // We instantiate the SDP class; passing to it, the api username, api password and the cp id
        $sdp = new SDP($_ENV["SFC_SDP_API_USERNAME"], $_ENV["SFC_SDP_API_PASSWORD"], $_ENV["SFC_SDP_CP_ID"]);
    
        // By default, SDP will use the sandbox apis (test bed), call useLive() method to use production APIs
        $sdp->useLive()->init();
    
        // We instantiate the premium SMS class and pass to it the SDP class
        $bulkSMS = new BulkSMS($sdp);
        
        // We send SMS
        $requestId = "1234"; // Generate an id that you can use for tracking/logging purposes
        $username = ""; // Username allocated by the SDP to the partner after successful registration.
        $packageId = ""; // The id of the campaign package as issued upon successful registration
        $originatingAddress = ""; // Originating address assigned to partner after successful registration.
        $recipients = [723345678, 789923456, 79098734];
        $message = "This is a bulk sms";
        $callback = "https://your_call_back_url.com/callback";
        
        $response = $bulkSMS->sendSMS($requestId, $username, $packageId, $originatingAddress, $recipients, $message, $callback);
        
        // You can check the response and do logging as necessary. Ensure that the status is correctly logged to
        // help in tracking if the request was successful or failed
        if ($response['success']) {
            // Request sent successfully to SDP. Should not be confused to mean the bulk has been successfully done 
            
            // We can now check the status of the sent message
            if (isset($response['data'])) {
                
                if ($response['data']['status'] == "SUCCESS") {
                    // Bulk SMS successfully dispatched. This does not mean sent to users; this can only be confirmed from the callback
                    print("Messages successfully dispatched to SDP");
                } else {
                    // Error dispatching bulk SMS to SDP/Safaricom
                    print("Failed to response code ". $response['data']['statusCode']);
                }
            } else {
                // something wrong seems to have happened. No response param sent back. Handle this for tracking
                print("Seems the response hasn't been sent. Best to assume it failed to dispatch the bulk SMS to the users");
            }
            
        } else {
            // Failed to send the request. Could be network, authentication, authorization errors et al 
            print("Failed to send request. Error says " . $response['errorMessage']);
        }
        
    
    } catch (SDPException $ex) {
        
        // You can do any error logging operations at this point. Exceptions here would most likely occur 
        // at the point of token generation
        
        print $ex->getMessage();
        
    }
```

## 5. Bulk SMS Callback

This is used when getting and handling bulk sms callback

```php

<?php 

    // put this code in your callback route (controller)
    
    use Phelix\SafaricomSDP\Utils;

    $response = Utils::getCallback();
    
    // get the request id. At this point, you can query your database to get the request so as to be able to update its status
    $requestId = isset($response['requestId']) ? $response['requestId'] : "";
        
    // check status of the callback
    if ($response['success'] == false) {
        // callback received has an error
        
        // update to failed delivery with the error message as the reason
        
        print($response['errorMessage']);
        
    } else {
        
        // correct callback data. 
        $data = $response['data'];
        
        // check the delivery status
 
        $status = $data['responseParam']['status'];
        
        if ($status != 0) {
            // failed to deliver message eg when someone has no airtime or is not subscribed
            print($data['responseParam']['description']);
        } else {
            // message successfully delivered. 
            
            // update request as being a succss
            print("Request $requestId successfully delivered");
        }
    }
    
            
```

## 6. SDK General Response Structure

```php

    $response = [
        "success" => true|false,
        "statusCode" => "",
        "statusText" => "",
        "errorCode" => "",
        "errorMessage" => "",
        "data" => [],
        "debugTrace" => [
            'request' => [],
            'response' => []
        ]
    ];
```
- **success**: Can be true | false. It implies success in submitting request to SDP. should not be confused to mean success of the operation eg sending sms
- **statusCode**: Http status code from SDP eg 200,400,401,500. Standard definitions apply
- **statusText**: Http status text corresponding to the status code. standard definitions apply
- **errorCode**: Error code from SDP. This depends on the SDP error codes for the specific SDP API eg sendSMS, bulkSMS, subscription APIs could each have their own error codes denoting different meanings
- **errorMessage**: The message from SDP describing the error
- **data**: Has the response data from SDP in case of a success. If "success" is false, this value will be null. The data comes in different formats depending on the specific API being used
- **debugTrace**: This contains a request and response trace. If 'SFC_SDP_DEBUG' is set to 1, this value will be set and can help debug an error. If the config value is 0, then this field will be null

## 7. Sample Response Examples

- All the response structures described below apply for the "data" key of the response structure described in section 6.

#### i.Get Token API

```php
    {
        "msg": "You have been Authenticated to access this protected API System.",
        "token": "eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJ0ZXN0dXNlciIsImF1ZCI6IkFDQ0VTUyIsInNjb3BlcyI6IkFETUlOIi wiaXNzIjoiaHR0cDovL3NpeGRlZS5jb20iLCJpYXQiOjE1Njk0OTc1MjksImV4cCI6MTU3NDI5NzUyOX0.- u2Db8OSDhtITMoFqIZYTgs6u4Ib_voynEA6k7ZwiqJqaPQ1_CnUaARxeaoSpC_BC-78_k- rzOr3v2Jdb9_KaA",
        "refreshToken": "eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJ0ZXN0dXNlciIsImF1ZCI6IlJFRlJFU0giLCJzY29wZXMiOiJBRE1J TiIsImlzcyI6Imh0dHA6Ly9zaXhkZWUuY29tIiwianRpIjoiZGIzOTk4OTYtMTU0ZS00ZDFjLTg1NmYtNTUy MDE2MDU3MDVkIiwiaWF0IjoxNTY5NDk3NTI5LCJleHAiOjE1ODAyOTc1Mjl9.uD7fvaMigBI0a2GC00fte qtTx79Elil1CFxRtXz5CTs1qRhJYUVsD0ZjF5Q13J9btY-5ppuzFDqDFkFfUpZAMw"
    }
``` 
    
#### ii.Refresh Token API
    
```php
    {
        "token": "eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJ0ZXN0dXNlciIsImF1ZCI6IkFDQ0VTUyIsInNjb3BlcyI6IkFETUlOI iwiaXNzIjoiaHR0cDovL3NpeGRlZS5jb20iLCJpYXQiOjE1Njk0OTc3NTgsImV4cCI6MTU3NDI5Nzc1OH 0.okOMCxGRFd1qt2OLVFFF4eDJ6aPZpLDhkNLA9STVMt9zH7fiMYaNz0S56_tJSXAtxYYq02PoQyG O4WBs716tCg"
    }
```
#### iii.Activate Subscription API
    
```php
     {
        "requestId":"17",
        "responseId":"cp2910183038077087336761",
        "responseTimeStamp":"20191104092806",
        "channel":"SMS",
        "operation":"ACTIVATE",
        "requestParam":{
           "data":[
              {
                 "name":"OfferCode",
                 "value":"350032100559"
              },
              {
                 "name":"Msisdn",
                 "value":"795421629"
              },
              {
                 "name":"Language",
                 "value":"1"
              },
              {
                 "name":"CpId",
                 "value":"321"
              }
           ]
        },
        "responseParam":{
           "status":"1",
           "statusCode":"0816",
           "description":"Thank you, your activation of service 5000_Promotional is not processed."
        }
     }
```
#### iv.Deactivate Subscription API
    
```php
     {
        "requestId":"17",
        "responseId":"10189519962937756186",
        "responseTimeStamp":"20190924161246",
        "channel":"3",
        "sourceAddress":"224.223.10.27",
        "operation":"DEACTIVATE",
        "requestParam":{
           "data":[
              {
                 "name":"OfferCode",
                 "value":"1001"
              },
              {
                 "name":"Msisdn",
                 "value":"716848648"
              },
              {
                 "value":"10"
              }
           ]
        },
        "responseParam":{
           "status":"0",
           "statusCode":"302",
           "description":"Dear subscriber,You have cancelled your subscription to LOCAL CHANNEL Pack. Thank you for using our service."
        }
     }
```

#### v.Send SMS
  
```php
    // Success. Note responseParam['status']= 0
     {
        "requestId":"17",
        "responseId":"10189519182688287792",
        "responseTimeStamp":"20190924155948",
        "channel":"3",
        "sourceAddress":"224.223.10.27",
        "operation":"SendSMS",
        "requestParam":{
           "data":[
              {
                 "name":"LinkId",
                 "value":"00010310189519161781865526"
              },
              {
                 "name":"Msisdn",
                 "value":"254795421629"
              },
              {
                 "value":"Thank You for Ondemand Subscription SAFRI TEST TUN Subscption test Send sms"
              },
              {
                 "name":"OfferCode",
                 "value":"1003"
              },
              {
                 "value":"10"
              }
           ]
        },
        "responseParam":{
           "status" : 0,
           "statusCode" : 768,
           "description" : "Mesage for xyz sent for processing..."
        }
     }
     
     // Fail. Note responseParam['status']= 1
          {
             "requestId":"17",
             "responseId":"10189519182688287792",
             "responseTimeStamp":"20190924155948",
             "channel":"3",
             "sourceAddress":"224.223.10.27",
             "operation":"SendSMS",
             "requestParam":{
                "data":[
                   {
                      "name":"LinkId",
                      "value":"00010310189519161781865526"
                   },
                   {
                      "name":"Msisdn",
                      "value":"254795421629"
                   },
                   {
                      "value":"Thank You for Ondemand Subscription SAFRI TEST TUN Subscption test Send sms"
                   },
                   {
                      "name":"OfferCode",
                      "value":"1003"
                   },
                   {
                      "value":"10"
                   }
                ]
             },
             "responseParam":{
                "status" : 1,
                "statusCode" : 23,
                "description" : "Subscription does not exists for the subscriber 7299412..."
             }
          }
```
        
#### vi.Bulk SMS
    
```php
     {
        "keyword":"Bulk",
        "status":"SUCCESS",
        "statusCode":"SC0000"
     }
```
        
#### vii. Bulk SMS Callback
    
```php
     {
        "requestId":"",
        "requestTimeStamp":"",
        "channel":"",
        "operation" :"",
        "traceID": "".
        "requestParam": {
            "data" : [
                {
                    "name" : "Msisdn",
                    "value":""
                },
                {
                    "name" : "CpId",
                    "value":""
                },
                {
                    "name" : "CorrelatorId",
                    "value":""
                },
                {
                    "name" : "Description",
                    "value":""
                },
                {
                    "name" : "DeliveryStatus",
                    "value":""
                },
                {
                    "name" : "Type",
                    "value":""
                },
                {
                    "name" : "CampaignId",
                    "value":""
                }
            
            ]
        }
     }
```

Credits
=======

- Phelix Juma from Kuza Lab Ltd (jumaphelix@kuzalab.com)
