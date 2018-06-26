<?php namespace Bueno\Services;

use App\Models\Order;
use App\Models\JoolehLog;
use Bueno\Loggers\JoolehLogger;
use GuzzleHttp\Client;
use Firebase\JWT\JWT;
use Bueno\Repositories\DbOrderRepository as OrderRepo;
use GuzzleHttp\Exception\ClientException;
use Exception;
use DateTime;

class RistaAPI
{ 
  /**
   * Guzzle Client Instance
   * 
   */
  protected $client,$orderRepo;

  protected $logger;



  /**
   * base url for the Jooleh API
   * 
   * @var string
   */
  protected $url = "https://api.ristaapps.com/v1";

  protected $apiKey = "2950dd16-e6d6-48c5-8080-388bc64a36e8";

  protected $accessToken = "ZqMdLUKvi14p4zhmMaJa7rE14ueb010UjcfQFtxTfSU";

  protected $apiToken;

  function __construct(Client $client,OrderRepo $orderRepo,JoolehLogger $logger)
  {
    $epocTime = time();
    $this->client = $client;
    $this->logger = $logger;
    $this->orderRepo = $orderRepo;
    $this->apiToken = JWT::encode([
        "iss" => $this->apiKey,
        "iat" => $epocTime,
        "jti" => "bueno-api-".$epocTime
      ], $this->accessToken);
  }


  /**
   * @param $order
   */
  public function createOrder(Order $order)
  {
    $request_url = $this->url . "/sale";
    try{

        $order_items = $order->itemsInfo;

        $itemCount = 0;
            
        foreach($order_items as $oitem)
        {
          $itemCount +=$oitem->quantity;
        }

        $items = $this->orderItemJsonFormat($order->items);

        $totalAmount = $order->invoice->where('charge_for','Total Amount')->first()->amount;

        $saleAmount = $order->invoice->where('charge_for','Order Amount')->first()->amount;

        $taxes = $this->orderTaxes($order, $saleAmount);

        $charges = $this->orderCharges($order, $saleAmount);  

        $discounts = $this->orderDiscounts($order, $saleAmount);

        $channel = 'Delivery';
        /*
        $saleDS = '{"branchName":"Gurgaon","branchCode":"bueno-gurgaon","invoiceType":"Sale","status":"Closed","delivery":{"name":"Pratyush  Bothra","email":"","phoneNumber":"7406204682","mode":"Delivery","address":{"addressLine":"udyog vihar phase 4, plot no 64, Udyog Vihar Phase 4, Udyog Vihar Phase 4 ,Gurgaon,Haryana PIN - 111111","city":null,"state":null,"country":"India","zip":null,"landmark":null,"label":null},"deliveryDate":"2016-11-23T00:00:39+05:30"},"customer":{"name":"Pratyush  Bothra","email":"","phoneNumber":"7406204682","id":"2V482S46"},"saleBy":"Rohan","channel":"Delivery","currency":"INR","itemCount":1,"items":[{"shortName":"Lebanese Mezze Platter","longName":"Lebanese Mezze Platter","skuCode":"Mezze Platter with Hummus","quantity":1,"unitPrice":270,"measuringUnit":"ea","itemAmount":270,"optionAmount":0,"discountAmount":0,"itemTotalAmount":270,"taxAmountIncluded":0,"taxAmountExcluded":35.438,"taxes":[{"name":"VAT","percentage":13.125,"saleAmount":270,"amount":35.438,"amountIncluded":0,"amountExcluded":35.438}]}],"itemTotalAmount":270,"discountAmount":0,"chargeAmount":0,"taxAmountIncluded":0,"taxAmountExcluded":35.44,"taxAmount":35.44,"billAmount":305.44,"roundOffAmount":0,"billRoundedAmount":305.44,"tipAmount":0,"taxes":[{"name":"VAT","percentage":13.125,"saleAmount":270,"amount":35.44,"amountIncluded":0,"amountExcluded":35.44}],"payments":[{"mode":"Credit Card","amount":305.44,"reference":"","postedDate":"2016-11-23T15:29:36+05:30"}],"totalAmount":305.44}';
        $saleArr = json_decode($saleDS,true);
        */

        $form_params = [
           'branchName' => 'Gurgaon',
           'branchCode' => 'bueno-gurgaon',
           'invoiceType' => 'Sale',
           'status' => 'Closed',
           'delivery' => [
              'name' => $order->user->full_name,
              'email' => $order->user->email,
              'phoneNumber' => $order->user_phone ? $order->user_phone : $order->user->phone,
              'mode' => 'Delivery',
              'address' => [
                'addressLine' => $order->delivery_address,
                'city' => '',
                'state' => '',
                'country' => 'India',
                'zip' => '',
                'landmark' => '',
                'label' => ''
              ],
              'deliveryDate' => $order->created_at->format(DateTime::ATOM),
            ],
           'customer' => [
              'name' => $order->user->full_name,
              'email' => $order->user->email,
              'phoneNumber' => $order->user_phone ? $order->user_phone : $order->user->phone,
              'id' => $order->user->id
           ],
           'saleBy' => 'Rohan',
           'channel' => $channel,
           'currency' => 'INR',
           'itemCount' => $itemCount,
           'items' => $items,
           'itemTotalAmount' => $saleAmount,
           'discountAmount' => $discounts['discountAmount'],
           'chargeAmount' => $charges['chargeAmount'],
           'taxAmountIncluded' => 0,
           'taxAmountExcluded' => $taxes['taxAmount'],
           'taxAmount' => $taxes['taxAmount'],
           'billAmount' => $totalAmount,
           'roundOffAmount' => 0,
           'billRoundedAmount' => $totalAmount,
           'tipAmount' => 0,
           'taxes' => $taxes['taxes'],
           'charges' => $charges['charges'],
           'discounts' => $discounts['discounts'],
           'payments' => [
              0=> [
                'mode' => $order->paymentMode->name,
                'amount' => $order->paymentInfo->amount,
                'reference' => '',
                'postedDate' => $order->created_at->format(DateTime::ATOM)
              ]
           ],
           'totalAmount' => $totalAmount
        ];
        $response = $this->client->request('POST', $request_url, [
          'headers'=> [
            'x-api-key' => $this->apiKey,
            'x-api-token' => $this->apiToken,
            'content-type' => 'application/json'
          ],
          'json' => $form_params,
          'timeout' => 10
        ]);
  }
  catch(Exception $e)
  { dd($e);
    $this->logger->log($e->getMessage());
    $response = false;
  }  
    return $response;
  }

  public function orderItemJsonFormat($items)
  {
    $counter = 0;

    $comma = 0;
    foreach($items as $item)
    {
      $items_array[] =[ 
      "shortName" => $item->itemable->name,
      "longName" => $item->itemable->name,
      "skuCode" => $item->product_sku,
      "quantity" => $item->pivot->quantity,
      "unitPrice" => $item->pivot->unit_price,
      "measuringUnit" => "ea",
      "itemAmount" => $item->pivot->unit_price * $item->pivot->quantity,
      'discountAmount' => 0,
      'itemTotalAmount' => $item->pivot->unit_price * $item->pivot->quantity,
      'taxAmountIncluded' => 0,
      'taxAmountExcluded' => 0,
      "taxes" => [],
      ];      
    }
    return $items_array;
  }

  public function orderTaxes($order, $saleAmount)
  {
    $taxes = [];
    
    $taxAmount = 0;

    $kitchen = $order->kitchen;

    $service_tax = $order->kitchen->service_tax;

    if($order->invoice->where('charge_for','VAT')->first()->amount)
    {
      $vatAmount = $order->invoice->where('charge_for','VAT')->first()->amount;

      $taxAmount = $taxAmount + $vatAmount;

      $taxVat = [
        'name' => 'VAT',
        'percentage' => $order->kitchen->vat,
        'saleAmount' => $saleAmount,
        'amount' => $vatAmount,
        'amountIncluded' => 0,
        'amountExcluded' => $vatAmount
      ];
      array_push($taxes, $taxVat);
    }

    if($order->invoice->where('charge_for','Service Tax')->first()->amount)
    {
      $serviceTax = $order->invoice->where('charge_for','Service Tax')->first()->amount;

      $taxAmount = $taxAmount + $serviceTax;

      $taxServiceTax = [
        'name' => 'Service Tax',
        'percentage' => $order->kitchen->service_tax,
        'saleAmount' => $saleAmount,
        'amount' => $serviceTax,
        'amountIncluded' => 0,
        'amountExcluded' => $serviceTax
      ];
      array_push($taxes, $taxServiceTax);
    }

    if($order->invoice->where('charge_for','Service Charge')->first()->amount)
    {
      $serviceCharge = $order->invoice->where('charge_for','Service Charge')->first()->amount;

      $taxAmount = $taxAmount + $serviceCharge;

      $taxServiceCharge = [
        'name' => 'Service Charge',
        'percentage' => $order->kitchen->service_charge,
        'saleAmount' => $saleAmount,
        'amount' => $serviceCharge,
        'amountIncluded' => 0,
        'amountExcluded' => $serviceCharge
      ];
      array_push($taxes, $taxServiceCharge);
    }

    return ['taxes' => $taxes, 'taxAmount' => $taxAmount];
  }

  public function orderCharges($order, $saleAmount)
  {
    $charges = [];

    $chargeAmount = 0;

    if($order->invoice->where('charge_for','Delivery Charge')->first()->amount)
    {
      $deliveryCharge = $order->invoice->where('charge_for','Delivery Charge')->first()->amount;

      $chargeAmount = $chargeAmount + $deliveryCharge;

      $chargeDelivery = [
        'name' => 'Delivery Charge',
        'type' => 'Absolute',
        'rate' => $deliveryCharge,
        'saleAmount' => $saleAmount,
        'amount' => $deliveryCharge,
        'taxes' => [],
      ];
      array_push($charges, $chargeDelivery);
    }

    if($order->invoice->where('charge_for','Packaging Charge')->first()->amount)
    {
      $packagingCharge = $order->invoice->where('charge_for','Packaging Charge')->first()->amount;

      $chargeAmount = $chargeAmount + $packagingCharge;

      $chargePackaging = [
        'name' => 'Packaging Charge',
        'type' => 'Absolute',
        'rate' => $packagingCharge,
        'saleAmount' => $saleAmount,
        'amount' => $packagingCharge,
        'taxes' => [],
      ];
      array_push($charges, $chargePackaging);
    }

    if($order->invoice->where('charge_for','Donation Amount')->first()->amount)
    {
      $donationCharge = $order->invoice->where('charge_for','Donation Amount')->first()->amount;

      $chargeAmount = $chargeAmount + $donationCharge;

      $chargeDonation = [
        'name' => 'Support Amount',
        'type' => 'Absolute',
        'rate' => $donationCharge,
        'saleAmount' => $saleAmount,
        'amount' => $donationCharge,
        'taxes' => [],
      ];
      array_push($charges, $chargeDonation);
    }

    return [ 'charges' => $charges, 'chargeAmount' => $chargeAmount];
  }

  public function orderDiscounts($order, $saleAmount)
  {
    
    $discounts = [];

    $discountAmount = 0;

    if($order->invoice->where('charge_for','Discount')->first()->amount)
    {
      $couponDiscount = $order->invoice->where('charge_for','Discount')->first()->amount;

      $discountAmount = $discountAmount + $couponDiscount;

      $discountCoupon = [
        'name' => 'Discount',
        'type' => 'Absolute',
        'rate' => $couponDiscount,
        'saleAmount' => $saleAmount,
        'amount' => -1 * abs($couponDiscount)
      ];
      array_push($discounts, $discountCoupon);
    }

    if($order->invoice->where('charge_for','Points Redeemed')->first()->amount)
    {
      $pointsRedeemedDiscount = $order->invoice->where('charge_for','Points Redeemed')->first()->amount;

      $discountAmount = $discountAmount + $pointsRedeemedDiscount;

      $discountPointsRedeemed = [
        'name' => 'Credits Redeem',
        'type' => 'Absolute',
        'rate' => $pointsRedeemedDiscount,
        'saleAmount' => $saleAmount,
        'amount' => -1 * abs($pointsRedeemedDiscount)
      ];
      array_push($discounts, $discountPointsRedeemed);
    }

    return [ 'discounts' => $discounts, 'discountAmount' => -1 * abs($discountAmount)];
  }

  public function updateOrder($order)
  {
    if($order->joolehLog) {
      $request_url = $this->url . "/orders/" . $order->joolehLog->oid;

      try{
        $response = $this->client->request('PUT', $request_url, [
          'headers' => [
              'X-Admin-Token' => $order->kitchen->jooleh_token,
              'X-Admin-Username' => $order->kitchen->jooleh_username,
          ],
          'form_params' => [
              'status' => config('bueno.jooleh_status.2'),
              'dispatch_status' => 'Picked'
          ],
          'timeout' => 10
      ]);
          $this->logger->log('Jooleh :: Marked order dispatched '.date('m d Y').' order-id '.$order->id.' response : '.$response->getBody()->getContents());
      }
  catch(Exception $e)
  {
    $this->logger->log($e->getMessage());
    $response = null;
  }

      return $response;
    }
    return "false";

  }

    public function cancelOrder($order)
    {
        if($order->joolehLog) {
            $request_url = $this->url . "/orders/" . $order->joolehLog->oid;

            try{
                $response = $this->client->request('DELETE', $request_url, [
                    'headers' => [
                        'X-Admin-Token' => $order->kitchen->jooleh_token,
                        'X-Admin-Username' => $order->kitchen->jooleh_username,
                    ],
                    'form_params' => [
                        'reason' => 'SystemIssue'
                    ],
                    'timeout' => 10
                ]);
                $this->logger->log('Jooleh :: Marked order cancelled '.date('m d Y').' order-id '.$order->id.' response : '.$response->getBody()->getContents());
            }
            catch(Exception $e)
            {
                $this->logger->log($e->getMessage());
                $response = null;
            }

            return $response;
        }
        return "false";

    }

  public function getOrderDetails($order)
  {
    if($order->joolehLog) {
      $request_url = $this->url . "/orders/" . $order->joolehLog->oid;

      try {
        $response = $this->client->request('GET', $request_url, [
            'headers' => [
                'X-Admin-Token' => $order->kitchen->jooleh_token,
                'X-Admin-Username' => $order->kitchen->jooleh_username,
            ],
            'timeout' => 10
        ]);
      } catch (Exception $e) {
        $this->logger->log($e->getMessage());
        $response = false;
      }
      return $response;
    }
    return false;

  }

  public function confirmOrder($order)
  {
    if($order->joolehLog){
    $request_url = $this->url ."/orders/". $order->joolehLog->oid;
    try{
    $response = $this->client->request('PUT', $request_url, [
        'headers'=> [
            'X-Admin-Token' => $order->kitchen->jooleh_token,
            'X-Admin-Username' => $order->kitchen->jooleh_username,
        ],
        'form_params' => [
            'oid' => $order->joolehLog->oid,
            'status' => config('bueno.jooleh_status.1'),
        ],
        'timeout' => 10
    ]);
  }
  catch(Exception $e)
  {
    $this->logger->log($e->getMessage());
    $response = null;
  }

    return $response;
    }

    return "false";

  }

  public function dispatchedOrder($order,$status)
  {
    $this->url .="/orders/". $order->oid;

    $response = $this->client->request('PUT', $this->url, [
        'headers'=> [
            'X-Admin-Token' => config('bueno.jooleh.admin_token'),
            'X-Admin-Username' => config('bueno.jooleh.admin_username')
        ],
        'form_params' => [
            'status' => $status
        ],
        'timeout' => 10
    ]);

    return $response;

  }



  public function getOrders()
  {
    $this->url .= "/orders?page=1&per_page=20&offset=0&status=Dispatched";
    $response = $this->client->request('GET', $this->url, [
        'headers'=> [
            'X-Admin-Token' => config('bueno.jooleh.admin_token'),
            'X-Admin-Username' => config('bueno.jooleh.admin_username'),
        ],
        'timeout' => 10
    ]);

    return $response;
  }


  /**
   *
   *
   * @return jooleh_uid
   */
  public function createDeliveryBoy($delivery_boy)
  {

    $this->url .= "/users";

    try {
      $response = $this->client->request('POST', $this->url, [
          'headers' => [
              'X-Admin-Token' => config('bueno.jooleh.admin_token'),
              'X-Admin-Username' => config('bueno.jooleh.admin_username')
          ],
          'form_params' => [
              'name' => $delivery_boy->full_name,
              'username' => $delivery_boy->phone,
              'password' => $delivery_boy->jooleh_pass,
          ],
          'timeout' => 10
      ]);
    }
  catch(ClientException $e)
   {

     $response = $this->client->request('GET', $this->url, [
         'headers'=> [
             'X-Admin-Token' => config('bueno.jooleh.admin_token'),
             'X-Admin-Username' => config('bueno.jooleh.admin_username')
         ],
         'timeout' => 10
     ]);
   }

    return $response;
  }

  /**
   *
   *
   * @return jooleh_uid
   */
  public function updateDeliveryBoy($delivery_boy)
  {
    $this->url .= "/users/".$delivery_boy->jooleh_uid;


     $response = $this->client->request('PUT', $this->url, [
         'headers'=> [
             'X-Admin-Token' => config('bueno.jooleh.admin_token'),
             'X-Admin-Username' => config('bueno.jooleh.admin_username')
         ],
         'form_params' => [
             'name' => $delivery_boy->full_name,
             'password' => $delivery_boy->jooleh_pass,
             'status' => 'Active',
         ],
         'timeout' => 10
     ]);

    return $response;
  }
}