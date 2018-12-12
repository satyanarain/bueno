<?php namespace Bueno\Transformers;

class KitchenTransformer{

  /**
   * @param $kitchen
   * @return array
   */
  public function transform($kitchen)
  {
    return [
        'id'                => (int) $kitchen->id,
        'kitchen_id'        => $kitchen->kitchen_id,
        'name'              => $kitchen->name,
        'unit_number'       => $kitchen->unit_number,
        'address_line1'     => $kitchen->address_line1,
        'address_line2'     => $kitchen->address_line2,
        'sub_area'          => $kitchen->sub_area,
        'latitude'          => $kitchen->latitude,
        'longitude'         => $kitchen->longitude,
        'delivery_charge'   => $kitchen->delivery_charge,
        'packaging_charge'  => $kitchen->packaging_charge,
        'vat'               => $kitchen->vat,
        'service_tax'       => $kitchen->service_tax,
        'service_charge'    => $kitchen->service_charge,
        'pickup_time'       => $kitchen->pickup_time,
    ];
  }
}