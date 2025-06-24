<?php

namespace App\Services;

use CyberSource\Model\Ptsv2paymentsClientReferenceInformation;
use CyberSource\Model\Ptsv2paymentsProcessingInformation;
use CyberSource\Model\Ptsv2paymentsPaymentInformationCard;
use CyberSource\Model\Ptsv2paymentsPaymentInformation;
use CyberSource\Model\Ptsv2paymentsOrderInformationAmountDetails;
use CyberSource\Model\Ptsv2paymentsOrderInformationBillTo;
use CyberSource\Model\Ptsv2paymentsOrderInformation;
use CyberSource\Model\CreatePaymentRequest;
use CyberSource\ApiClient;
use CyberSource\Api\PaymentsApi;
use Cybersource\ApiException;

use function PHPUnit\Framework\isNull;

class CyberSourcePaymentService
{
   private $firstName;
   private $lastName;
   private $address;
   private $city;
   private $province;
   private $postalCode;
   private $country;
   private $email;
   private $phone;
   private $cardNumber;
   private $expirationMonth;
   private $expirationYear;
   private $securityCode;
   private $amount;
 
   public function __construct($fullName,$address, $city, $postalCode, $country, $email, $phone, $cardNumber, $securityCode,$expiration, $amount) {
      $fullName = explode(' ', $fullName);
      $expiration = explode('/', $expiration);
      $this->firstName = $fullName[0];
      $this->lastName = $fullName[1];
      $this->address = $address;
      $this->city = $city;
      $this->province = 'n/a';
      $this->postalCode = $postalCode;
      $this->country = $country;
      $this->email = $email;
      $this->phone = $phone;
      $this->cardNumber = $cardNumber;
      $this->expirationMonth = $expiration[0];
      $this->expirationYear = $expiration[1];
      $this->securityCode = $securityCode;
      $this->amount = $amount;
   }
    public function createPayment()
    {
        $commonElement = new ExternalConfiguration();
        $config = $commonElement->ConnectionHost();
        $merchantConfig = $commonElement->merchantConfigObject();
        $apiClient = new ApiClient($config, $merchantConfig);
        $apiInstance = new PaymentsApi($apiClient);
        try {
            $apiResponse = $apiInstance->createPayment($this->createRequest());
            $results = $apiResponse[0];
            $output = json_decode(($results)->__toString(), true);
            if ($output['status'] == "DECLINED" ){
                return  ['status' => 'ERROR', 'message' =>  $output["errorInformation"]['message']]; 
            }
            return $output;
        } catch (ApiException $e) {
            return ['status' => 'ERROR', 'message' =>  $this->processErrors($e)];
        }
    }

    private function getBillTo(){
        return new Ptsv2paymentsOrderInformationBillTo([
            "firstName" => $this->firstName,
            "lastName" => $this->lastName,
            "address1" => $this->address,
            "locality" => $this->city,
            "administrativeArea" => $this->province,
            "postalCode" => $this->postalCode,
            "country" => $this->country,
            "email" => $this->email,
            "phoneNumber" => $this->phone
        ]);
    }

    private function getCardInformation(){
        $paymentInformationCard = new Ptsv2paymentsPaymentInformationCard([
                "number" => $this->cardNumber,
                "expirationMonth" => $this->expirationMonth,
                "expirationYear" => $this->expirationYear,
                "securityCode" => $this->securityCode
        ]);

        return  new Ptsv2paymentsPaymentInformation([
                "card" => $paymentInformationCard
        ]);
    }

    private function getOrderInformation(){
        return new Ptsv2paymentsOrderInformationAmountDetails([
                "totalAmount" => $this->amount,
                "currency" => "USD"
        ]);

    }

    private function createRequest(){
        $clientReferenceInformation = new Ptsv2paymentsClientReferenceInformation([
			"code" => "TC50171_3"
        ]);

        $processingInformation = new Ptsv2paymentsProcessingInformation([
                "capture" => true
        ]);

        $orderInformationArr = [
                "amountDetails" => $this->getOrderInformation(),
                "billTo" => $this->getBillTo(),
        ];
        $orderInformation = new Ptsv2paymentsOrderInformation($orderInformationArr);

        return new CreatePaymentRequest([
                "clientReferenceInformation" => $clientReferenceInformation,
                "processingInformation" => $processingInformation,
                "paymentInformation" => $this->getCardInformation(),
                "orderInformation" => $orderInformation
        ]);
    }

    private function processErrors(ApiException $e){
        if ($e->getLine()== 403){
            return 'Connection refused, check you internet connection and try again';
        };

        $errorBody = $e->getResponseBody();
        if (!isset($errorBody->details) && isset($errorBody->message)){
            return $errorBody->message;
        }

        if (!isset($errorBody->details) && !isset($errorBody->message)){
            return 'Its not you its us reach out for help';
        }

        $errors = $errorBody->details[0];
        if (is_object($errors)){
            $data = explode('.',$errors->field);
           return 'Invalid input data,  check your '. ucwords(preg_replace('/(?<!\ )[A-Z]/', ' $0', $data[0])) . ' '. $this->getRelatableFields($data[2]);
        }

        if (is_array($errors) && $errors['status'] == "DECLINED" ){
            return $errors["errorInformation"]['message']; 
        }

        //add logging here

        return 'Payment failed';
    }

    private function getRelatableFields(string $label)
    {
        return match ($label) {
            'expirationMonth' => ', Month on Expiry date on card page needs to be corrected',
            'expirationYear' => ', Year on Expiry date on card page needs to be corrected',
            'lastName' => ', Surname on fullname of card page needs to be corrected',
            'firstName' => ', First name on fullname of card page needs to be corrected',
            'securityCode' => ', CVV on the card page needs to be corrected',
            'country' => ', Country on the card page needs to be corrected',
            'totalAmount' => ', Amount on the first invoice page needs to be corrected',
            'phoneNumber' => ', Phonenumber on card page needs to be corrected',
            'administrativeArea' => ', State on card page needs to be corrected',
            'locality' => ', City on card page needs to be corrected',
            'postalCode' => ', Postal Code on card page needs to be corrected',
            'number' => ', Card Number on card page needs to be corrected',
            'email' => ', Email on card page needs to be corrected',
            'address1' => ', Address on card page needs to be corrected',
            'currency' => ', Not yet Added needs to be corrected',
            'type' => ', Not yet Added needs to be corrected',
            default => $label,
        };
    }
}