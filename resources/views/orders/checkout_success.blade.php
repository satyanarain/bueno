@extends('layouts.master')

@section('content')

    <section class="title_sec gray-dim-bg">
        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    <div class="main-sec">
                    </div> <!-- main-sec ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->
    </section> <!-- title_sec ends -->

    <section class="paddingbottom-xlg marginbottom-xlg">

        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    <div class="col-xs-12 account_sec my_account_sec">

                        <section class="title_sec white-bg dashed_border col-xs-12 no-padding">
                            <div class="main-sec stick_lines col-xs-12">
                                <div class="col-sm-12 left-sec">
                                    <h2 class="style_header_loud">Order Successful!</h2>
                                </div> <!-- left-sec ends -->
                                <div class="col-sm-12 col-md-4 right-sec">
                                </div> <!-- right-sec ends -->
                            </div> <!-- main-sec ends -->
                        </section> <!-- title_sec ends -->

                        <div class="content col-xs-12 margintop-lg">
                            <p>Your order has been successfully placed! <br>
                                Please check your SMS for order details &amp; to be able to track your delivery</p>
                            <a href="{{ route('pages.index') }}" class="btn btn-primary margintop-md">Continue browsing</a>
                        </div> <!-- content ends -->
                        @if($order->is_pickup)
                        <input type="hidden" id="latitude" name="latitude" value="{{$order->kitchen->latitude}}">
                        <input type="hidden" id="longitude" name="longitude" value="{{$order->kitchen->longitude}}">
                        <div class="content col-xs-12 margintop-lg">
                            <h4>Pick Up Address:</h4>
                            <p>{{$order->kitchen->unit_number.' '.$order->kitchen->address_line1.' '.$order->kitchen->address_line2.' '.$order->kitchen->sub_area}}</p>
                            <div id="dc-edit-google-map">
                                <div id="lat-long-selector-map" style="height:200px; border:1px solid #ddd; text-align:center">
                                </div>
                                <p></p>
                            </div>
                        </div>
                        @endif
                    </div> <!-- my_account_sec ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- <section> ends -->

    <script>
        var amount = 0;
        amount = {{ $order->paymentInfo->amount  }};

        var items = [];

        var window.gaAddItem = window.gaAddItem || [];

        @foreach($order->items as $order_item)
            items.push({
               'sku' : {{ $order_item->id }},
                'name' : '{{$order_item->itemable->name}}',
                'category' : '{{$order_item->itemable->category->name}}',
                'price' : {{ $order_item->pivot->unit_price }},
                'quantity' : {{ $order_item->pivot->quantity }}
            });

            gaAddItem.push({
                'id' :  {{ $order_item->id }},
                'name' : '{{$order_item->itemable->name}}',
                'sku' : {{ $order_item->id }},
                'category' : '{{$order_item->itemable->category->name}}',
                'price' : {{ $order_item->pivot->unit_price }},
                'quantity' : {{ $order_item->pivot->quantity }}
            });

        @endforeach
        window.dataLayer = window.dataLayer || [];

        window.gaAddTransaction = window.gaAddTransaction || [];

        dataLayer.push({
            'transactionId': {{ $order->id }},
            'transactionAffiliation': 'Bueno Kitchen',
            'transactionTotal': {{ $order->paymentInfo->amount  }},
            'transactionTax': {{ $order->invoice->where('charge_for','Service Tax')->first()->amount + $order->invoice->where('charge_for','VAT')->first()->amount }},
            'transactionShipping': {{  $order->invoice->where('charge_for','Delivery Charge')->first()->amount  }},
            'transactionProducts': items
        });

        gaAddTransaction.push({
            'id': {{ $order->id }},
            'affiliation': 'Bueno Kitchen',
            'revenue': {{ $order->paymentInfo->amount  }},
            'shipping': {{  $order->invoice->where('charge_for','Delivery Charge')->first()->amount  }},
            'tax': {{ $order->invoice->where('charge_for','Service Charge')->first()->amount + $order->invoice->where('charge_for','Service Tax')->first()->amount + $order->invoice->where('charge_for','VAT')->first()->amount }}
        });

        fbq('track', 'Purchase', { value: amount, currency: 'INR'});
    </script>

    <script type="text/javascript">
        ga('require', 'ecommerce');
        ga('ecommerce:addTransaction', {
          'id': {{ $order->id }},                     // Transaction ID. Required.
          'affiliation': 'Bueno Kitchen',   // Affiliation or store name.
          'revenue': {{ $order->paymentInfo->amount  }},               // Grand Total.
          'shipping': {{  $order->invoice->where('charge_for','Delivery Charge')->first()->amount  }},                  // Shipping.
          'tax': {{ $order->invoice->where('charge_for','Service Charge')->first()->amount + $order->invoice->where('charge_for','Service Tax')->first()->amount + $order->invoice->where('charge_for','VAT')->first()->amount }}                     // Tax.
        });

        ga('ecommerce:addItem', {
            'id' :  {{ $order->items[0]->id }},
            'name' : '{{$order->items[0]->itemable->name}}',
            'sku' : {{ $order->items[0]->id }},
            'category' : '{{$order->items[0]->itemable->category->name}}',
            'price' : {{ $order->items[0]->pivot->unit_price }},
            'quantity' : {{ $order->items[0]->pivot->quantity }}
        });

        ga('ecommerce:send');
        </script>

        <!-- Google Code for Sale Conversion Page -->
        <script type="text/javascript">
        /* <![CDATA[ */
        var google_conversion_id = 961291556;
        var google_conversion_language = "en";
        var google_conversion_format = "3";
        var google_conversion_color = "ffffff";
        var google_conversion_label = "qTTyCKffxVgQpMqwygM";
        var google_remarketing_only = false;
        /* ]]> */
        </script>
        <script type="text/javascript"  
        src="//www.googleadservices.com/pagead/conversion.js">
        </script>
        <noscript>
        <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt=""  
        src="//www.googleadservices.com/pagead/conversion/961291556/?label=qTTyCKffxVgQpMqwygM&amp;guid=ON&amp;script=0"/>
        </div>
        </noscript>

        @if($order->is_pickup)
        <script src="http://maps.googleapis.com/maps/api/js"></script>
        @endif

@stop