<?php namespace Bueno\Billing\Providers;

use Exception;
use Log;
use Bueno\Billing\BillingInterface;
use Bueno\Repositories\DbOrderRepository;

/**
 * Class Mobikwik
 * @package Bueno\Billing\Providers
 */
class OlaMoney implements BillingInterface{

  /**
   * @var array
   */
  protected $inputs = [];

  /**
   * setting up config values for mobikwik based on app environment
   */
  function __construct()
  {
    $this->accessToken = env('APP_ENV') == 'production' ? config('bueno.ola_money.accessToken') : config('bueno.ola_money_testing.accessToken');
    $this->salt = env('APP_ENV') == 'production' ? config('bueno.ola_money.salt') : config('bueno.ola_money_testing.salt');
    $this->action_url = env('APP_ENV') == 'production' ? config('bueno.ola_money.action_url') : config('bueno.ola_money_testing.action_url');
  }

  /** creates an unique hash to be used by payment gateway
   *
   * @param $inputs
   * @return string
   */

  public function buildHash($inputs)
  {
    return openssl_digest(''.config('bueno.ola_money_testing.accessToken').'|'.$inputs['uniqueId'].'|'.$inputs['comments'].'|'.$inputs['udf'].'|'.$inputs['returnUrl'].'|'.$inputs['notificationUrl'].'|'.$inputs['currency'].'|'.$inputs['amount'].'|'.$inputs['couponCode'].'|'.$this->salt.'', 'sha512');
  }

  /** creates the billing string used by payment gateway
   *
   * @param $inputs
   * @return string
   */

  public function generateBill($inputs)
  {
    $hash = $this->buildHash($inputs);
    $str = '{
      "command":"debit",
      "accessToken":"'.config('bueno.ola_money_testing.accessToken').'",
      "uniqueId":"'.$inputs['uniqueId'].'",
      "comments":"'.$inputs['comments'].'",
      "udf":"'.$inputs['udf'].'",
      "hash":"'.$hash.'",
      "returnUrl":"'.$inputs['returnUrl'].'",
      "notificationUrl":"'.$inputs['notificationUrl'].'",
      "amount":"'.$inputs['amount'].'",
      "currency":"'.$inputs['currency'].'",
      "couponCode":"'.$inputs['couponCode'].'" 
    }';

    return base64_encode($str);
  }

  /**
   * creates an unique transaction ID
   *
   * @return string
   */
  public function buildTransactionID()
  {
    return substr(hash('sha256', mt_rand() . microtime()), 0, 20);
  }

  /**
   * @return array
   */
  public function setInputs($order, $inputs)
  {
    $this->inputs = [
        'amount'  => (Float) $order->paymentInfo->amount,
        'udf' => 'optional',
        'comments' => 'optional',
        'uniqueId' => $order->id,
        'accessToken' => $this->accessToken,
        'returnUrl' => route('checkout.success.post', 9),
        'notificationUrl' => route('olamoneynotify.success.post', 9),
        'amount' => number_format((float)$order->paymentInfo->amount, 2, '.', ''),
        'currency' => 'INR',
        'couponCode' => 'NA',
        'command' => 'debit',
    ];
    return $this->inputs;
  }


  /**
   * builds form, checksum and submits form to mobikwik
   * @param array $inputs
   */
  public function buildFormAndSubmit($order, $inputs = [])
  {
    $inputs = $this->setInputs($order, $inputs);

    echo '<form action="' . $this->action_url . '" name="olamoney_prepareForm" method="GET">';
    echo '<input type="hidden" name="bill" value="' . $this->generateBill($inputs) . '">';
    echo '</form>';
    echo '<script>document.olamoney_prepareForm.submit();</script>';
  }


  /**
   * @param $checksum
   * @param $all
   * @param $secret
   * @return int
   */
  function verifyChecksum($secret, $all, $checksum)
  {
    $cal_checksum = $this->calculateChecksum($secret, $all);
    $bool = 0;
    if($checksum == $cal_checksum)	{
      $bool = 1;
    }

    return $bool;
  }

  /**
   * @param $secret_key
   * @param $all
   * @return string
   */
  function calculateChecksum($secret_key, $all)
  {
    $hash = hash_hmac('sha256', $all , $secret_key);
    $checksum = $hash;
    return $checksum;
  }

  /**
   * // This function makes check-status api call // it is master function
   * @param $MerchantId
   * @param $OrderId
   * @param $Amount
   * @param $WorkingKey
   * @return array
   */
  function verifyTransaction($MerchantId, $OrderId, $Amount, $WorkingKey) {
    //error_log("entered in verif function");
    $version = '2'; // version value
    $return = array();
    $checksum = $this->calculateWalletChecksum($MerchantId, $WorkingKey, $OrderId);
    $return['calculated_checksum'] = $checksum;
    $url = $this->url;        // you can change this url to point to mobikwik checkstatus api url
    $fields = "mid=$MerchantId&orderid=$OrderId&checksum=$checksum&ver=2";

    //error_log("curl check");
    // is cURL installed yet?
    if (!function_exists('curl_init')) {
      die('Sorry cURL is not installed!');
    }
    // then let's create a new cURL resource handle
    $ch = curl_init();

    // Now set some options (most are optional)
    // Set URL to hit
    curl_setopt($ch, CURLOPT_URL, $url);

    // Include header in result? (0 = yes, 1 = no)
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_setopt($ch, CURLOPT_POST, 1);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    // Should cURL return or print out the data? (true = return, false = print)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Timeout in seconds
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Download the given URL, and return output
    $outputXml = curl_exec($ch);
    //error_log("excecuted");
    // Close the cURL resource, and free system resources
    curl_close($ch);

    $outputXmlObject = simplexml_load_string($outputXml);

    error_log("The response received is = " . $outputXml);
    //error_log("before function");

    $recievedChecksum = $this->validateChecksumMobikwik(
        $outputXmlObject->statuscode, $outputXmlObject->orderid, $outputXmlObject->refid, $outputXmlObject->amount, $outputXmlObject->statusmessage, $outputXmlObject->ordertype, $WorkingKey);
    //error_log("before condition");

    error_log("values , our order = {$OrderId} & xml order = {$outputXmlObject->orderid} , our amount = {$Amount} & xml amount = {$outputXmlObject->amount} , our checksum = {$recievedChecksum} & xml checksum = {$outputXmlObject->checksum}");
    if (($OrderId == $outputXmlObject->orderid) && ($outputXmlObject->amount == $Amount) && ($outputXmlObject->checksum == $recievedChecksum)) {
      //error_log("entered in verifcation final box");
      $return['statuscode'] = $outputXmlObject->statuscode;
      $return['orderid'] = $outputXmlObject->orderid;
      $return['refid'] = $outputXmlObject->refid;
      $return['amount'] = $outputXmlObject->amount;
      $return['statusmessage'] = $outputXmlObject->statusmessage;
      $return['ordertype'] = $outputXmlObject->ordertype;
      $return['checksum'] = $outputXmlObject->checksum;
      $return['flag'] = true;
      //error_log("condition satisfy : status code = {$return['statuscode']} , orderid = {$return['orderid'] } , refid = {$return['refid']} , msg = {$return['statusmessage']} , ordertype = {$return['ordertype']}");
    }
    //error_log("sending return = " . print_r($return));
    return $return;
  }

  /**
   * @param $merchantId
   * @param $secretKey
   * @param $orderId
   * @return string
   */
  function calculateWalletChecksum($merchantId, $secretKey, $orderId) {
    $algo = 'sha256';
    $checksum_string = "'{$merchantId}''{$orderId}'";
    $checksum = hash_hmac($algo, $checksum_string, $secretKey);
    return $checksum;
  }

  /**
   * This function is used to make checksum to verify checksum received from check-status api call
   *
   * @param $statuscode
   * @param $orderid
   * @param $refid
   * @param $amount
   * @param $statusmessage
   * @param $ordertype
   * @param $WorkingKey
   * @return string
   */
  function validateChecksumMobikwik($statuscode, $orderid, $refid, $amount, $statusmessage, $ordertype, $WorkingKey) {
    $action = "gettxnstatus"; // fixed value
    $algo = 'sha256';
    $checksum_string = "'{$statuscode}''{$orderid}''{$refid}''{$amount}''{$statusmessage}''{$ordertype}'";
    $checksum = hash_hmac($algo, $checksum_string, $WorkingKey);
    return $checksum;
  }

  /**
   * handle success event
   *
   * @param $inputs
   * @return mixed
   */
  public function handleNotify($inputs)
  {
     
     $message = json_encode($inputs);
     $filename = 'olamoney-'.rand().'.json';
     file_put_contents(public_path() . "/".$filename, $message);
   if(!$this->checkSuccessHash($inputs)) return $this->handleFailure();

    $response = json_encode($inputs);

    $orderRepo = new DbOrderRepository;

    $order = $orderRepo->updateOrderPayment($inputs['merchantBillId'], $response);
  }

  public function handleSuccess($inputs)
  {
    if(!$this->checkSuccessHash($inputs)) return $this->handleFailure();

    $orderRepo = new DbOrderRepository;

    $order = $orderRepo->getOrderById($inputs['merchantBillId']);

    return view('orders.checkout_success', compact('order'));
  }

  /**
   * handles failure event
   *
   * @return mixed
   */
  public function handleFailure()
  {
    return view('orders.checkout_failure');
  }

  /**
   * checks for hash value after the success callback
   * @param $inputs
   * @return mixed
   */
  public function checkSuccessHash($inputs)
  {
    $hash = $this->buildSuccessHash($inputs);

    if($hash != $inputs['hash']) return false;

    return true;
  }

  /**
   * builds the hash for success event
   *
   * @param $inputs
   * @return string
   */
  public function buildSuccessHash($inputs)
  {
    return openssl_digest(''.$inputs['type'].'|'.$inputs['status'].'|'.$inputs['merchantBillId'].'|'.$inputs['transactionId'].'|'.$inputs['amount'].'|'.$inputs['comments'].'|'.$inputs['udf'].'|'.$inputs['isCashbackAttempted'].'|'.$inputs['isCashbackSuccessful'].'|'.$inputs['timestamp'].'|'.$this->salt.'', 'sha512');
  }

  /**
   * verify checksum in old api
   *
   * @param $inputs
   * @return $this
   */
  public function verifyChecksumAPI($inputs)
  {
    $orderid= $inputs['orderId'];
    $amount = 0;
    $status = "1";
    $statusMsg = $inputs['responseDescription'];

    $checksum = $inputs['checksum'];

    $allNonEmptyParamValueExceptChecksum = "";
    $orderId = $inputs['orderId'];
    $responseCode = $inputs['responseCode'];
    $responseDescription = $inputs['responseDescription'];
    $checksum = $inputs['checksum'];

    $paymentMethod = $inputs['paymentMethod'];
    $cardhashid = $inputs['cardhashid'];

    if('wallet' == $paymentMethod){

        if(!empty($inputs['amount'])){
          $amount= $inputs['amount'] ;
          $amounts = $inputs['amount'];
        }
        //{amount=10000, responseCode=0, responseDescription=Transaction completed successfully, paymentMethod=wallet, orderId=346345645}
        $checksumInput = "'".$orderId."''".$amounts."''".$responseCode."''".$responseDescription."'";

        if ($checksum!=null)
        {
          $MOBIKWIK_SECRET_KEY= $this->secret ;
          $isChecksumValid= $this->verifyChecksum($MOBIKWIK_SECRET_KEY, $checksumInput, $checksum);
        }

        if(( $isChecksumValid ) && ($responseCode == '0'))
        {
          $status="0";
        }
      }

    $string = '<paymentResponse>
         <orderid>'.$orderId.'</orderid>
        <amount>'.$amount.'</amount>
        <status>'.$status.'</status>
        <statusMsg>'.$responseDescription.'</statusMsg>
        </paymentResponse>';

    return response()->make($string, '200')->header('Content-Type', 'text/xml');
  }

  /**
   * builds checksum for old api
   *
   * @param $inputs
   * @return $this
   */
  public function buildChecksumAPI($inputs)
  {
    $orderid = $inputs['orderid'];
    $amount = $inputs['amount'];
    $mid = $inputs['mid'];

    $allNonEmptyParamValueExceptChecksum = "'".$amount."''".$orderid."''".$mid."'";

    $hash = $this->calculateChecksum( $this->secret, $allNonEmptyParamValueExceptChecksum);

    $response = '<checksum><status>SUCCESS</status><checksumValue>'.$hash.'</checksumValue></checksum>';

    return response()->make($response, '200')->header('Content-Type', 'text/xml');
  }

  /**
   * handles payment in api
   *
   * @param $inputs
   * @return mixed
   */
  public function handlePaymentAPI($order, $inputs)
  {
    $orderid = $inputs['orderid'];
    $amount = $inputs['amount'];
    $mid = $inputs['mid'];

    $allNonEmptyParamValueExceptChecksum = "'".$amount."''".$orderid."''".$mid."'";

    $hash = $this->calculateChecksum( $this->secret, $allNonEmptyParamValueExceptChecksum);

    $response = '<checksum><status>SUCCESS</status><checksumValue>'.$hash.'</checksumValue></checksum>';

    return response()->make($response, '200')->header('Content-Type', 'text/xml');
  }

  /**
   * @param $order
   * @param $inputs
   * @return $this
   */
  public function handleSuccessAPI($order, $inputs)
  {
    $orderid= $inputs['orderId'];
    $amount = 0;
    $status = "1";
    $statusMsg = $inputs['responseDescription'];

    $checksum = $inputs['checksum'];

    $allNonEmptyParamValueExceptChecksum = "";
    $orderId = $inputs['orderId'];
    $responseCode = $inputs['responseCode'];
    $responseDescription = $inputs['responseDescription'];
    $checksum = $inputs['checksum'];

    $paymentMethod = $inputs['paymentMethod'];
    $cardhashid = $inputs['cardhashid'];

    if('wallet' == $paymentMethod){

      if(!empty($inputs['amount'])){
        $amount= $inputs['amount'] ;
        $amounts = $inputs['amount'];
      }
      //{amount=10000, responseCode=0, responseDescription=Transaction completed successfully, paymentMethod=wallet, orderId=346345645}
      $checksumInput = "'".$orderId."''".$amounts."''".$responseCode."''".$responseDescription."'";

      if ($checksum!=null)
      {
        $MOBIKWIK_SECRET_KEY= $this->secret ;
        $isChecksumValid= $this->verifyChecksum($MOBIKWIK_SECRET_KEY, $checksumInput, $checksum);
      }

      if(( $isChecksumValid ) && ($responseCode == '0'))
      {
        $status="0";
      }
    }

    $string = '<paymentResponse>
         <orderid>'.$orderId.'</orderid>
        <amount>'.$amount.'</amount>
        <status>'.$status.'</status>
        <statusMsg>'.$responseDescription.'</statusMsg>
        </paymentResponse>';

    return response()->make($string, '200')->header('Content-Type', 'text/xml');
  }

  /**
   * @param $param
   * @return mixed
   */
  public function sanitizedParam($param) {
    $pattern[0] = "%,%";
    $pattern[1] = "%#%";
    $pattern[2] = "%\(%";
    $pattern[3] = "%\)%";
    $pattern[4] = "%\{%";
    $pattern[5] = "%\}%";
    $pattern[6] = "%<%";
    $pattern[7] = "%>%";
    $pattern[8] = "%`%";
    $pattern[9] = "%!%";
    $pattern[10] = "%\\$%";
    $pattern[11] = "%\%%";
    $pattern[12] = "%\^%";
    $pattern[13] = "%=%";
    $pattern[14] = "%\+%";
    $pattern[15] = "%\|%";
    $pattern[16] = "%\\\%";
    $pattern[17] = "%:%";
    $pattern[18] = "%'%";
    $pattern[19] = "%\"%";
    $pattern[20] = "%;%";
    $pattern[21] = "%~%";
    $pattern[22] = "%\[%";
    $pattern[23] = "%\]%";
    $pattern[24] = "%\*%";
    $pattern[25] = "%&%";
    $sanitizedParam = preg_replace($pattern, "", $param);
    return $sanitizedParam;
  }

  /**
   * @param $param
   * @return mixed
   */
  public function sanitizedURL($param) {
    $pattern[0] = "%,%";
    $pattern[1] = "%\(%";
    $pattern[2] = "%\)%";
    $pattern[3] = "%\{%";
    $pattern[4] = "%\}%";
    $pattern[5] = "%<%";
    $pattern[6] = "%>%";
    $pattern[7] = "%`%";
    $pattern[8] = "%!%";
    $pattern[9] = "%\\$%";
    $pattern[10] = "%\%%";
    $pattern[11] = "%\^%";
    $pattern[12] = "%\+%";
    $pattern[13] = "%\|%";
    $pattern[14] = "%\\\%";
    $pattern[15] = "%'%";
    $pattern[16] = "%\"%";
    $pattern[17] = "%;%";
    $pattern[18] = "%~%";
    $pattern[19] = "%\[%";
    $pattern[20] = "%\]%";
    $pattern[21] = "%\*%";
    $sanitizedParam = preg_replace($pattern, "", $param);
    return $sanitizedParam;
  }
}