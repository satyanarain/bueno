<?php namespace Bueno\Transformers;

use App\Models\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract{

  /**
   * List of resources possible to include
   *
   * @var array
   */
  protected $availableIncludes = [
    'items'
  ];

  /**
   * List of resources to automatically include
   *
   * @var array
   */
  protected $defaultIncludes = [

  ];


  /**
   * Turn this item object into a generic array
   *
   * @param Order $item
   * @return array
   */
  public function transform(Order $order)
  {
    return [
        'id'          => (int) $order->id,
        'user_id'     => $order->user_id,
        'order_no'    => $order->order_no,
        'status'      => $order->statusText->name,
        'created_at'  => $order->created_at->format('d M Y H:i')
    ];
  }

  /**
   * Turn this item object into a generic array
   *
   * @param Order $item
   * @return array
   */
  public function transformOrderDetail(Order $order)
  {
    $order_items = $order->itemsInfo;

    $item_count = 0;
        
    foreach($order_items as $oitem)
    {
      $item_count +=$oitem->quantity;
    }

    $items = [];

    foreach ($order->items as $item) {
      $item = [
        'name' => $item->itemable->name,
        'quantity' => $item->pivot->quantity,
        'price' => $item->itemable->discount_price,
      ];
      array_push($items, $item);
    }

    return [
        'id'          => (int) $order->id,
        'order_no'    => $order->order_no,  
        'status'      => $order->statusText->name,
        'created_at'  => $order->created_at->format('d M Y H:i'),
        'order_amount' => $order->invoice->where('charge_for','Order Amount')->first()->amount,
        'vat'           => $order->invoice->where('charge_for','VAT')->first()->amount,
        'service_tax'   => $order->invoice->where('charge_for','Service Tax')->first()->amount,
        'service_charge' => $order->invoice->where('charge_for','Service Charge')->first()->amount,
        'delivery_charge' => $order->invoice->where('charge_for','Delivery Charge')->first()->amount,
        'packaging_charge' => $order->invoice->where('charge_for','Packaging Charge')->first()->amount,
        'discount' => $order->invoice->where('charge_for','Discount')->first()->amount,
        'points_redeemed' => $order->invoice->where('charge_for','Points Redeemed')->first()->amount,
        'donation_amount' => $order->invoice->where('charge_for','Donation Amount')->first()->amount,
        'total_amount' => $order->invoice->where('charge_for','Total Amount')->first()->amount,
        'user_id'     => $order->user_id,
        'full_name' => $order->user->full_name,
        'email_address' => $order->user->email,
        'contact_number' => $order->user->phone,
        'is_pickup' => $order->is_pickup,
        'address' => $order->delivery_address,
        'location' => $order->area->name,
        'kitchen' => $order->kitchen->name,
        'payment_mode' => $order->paymentMode->name,
        'order_source' => $order->source->name,
        'item_count'       => $item_count,
        'items' => $items,

    ];
  }

  /**
   * include items
   *
   * @param Order $order
   * @return \League\Fractal\Resource\Collection
   */
  public function includeItems(Order $order)
  {
    return $this->collection($order->items, new OrderItemTransformer);
  }


}