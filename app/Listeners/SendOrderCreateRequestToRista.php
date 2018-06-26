<?php

namespace App\Listeners;

use App\Events\OrderWasCreatedByUser;
use Bueno\Services\RistaAPI as Rista;
use Bueno\Loggers\JoolehLogger;
//use Illuminate\Queue\InteractsWithQueue;
//use Illuminate\Contracts\Queue\ShouldQueue;
use Bueno\Repositories\DbOrderRepository as OrderRepo;

class SendOrderCreateRequestToRista
{

  protected  $rista,$orderRepo;
  protected $logger;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Rista $rista,OrderRepo $orderRepo,JoolehLogger $logger)
    {
        $this->rista = $rista;
        $this->orderRepo = $orderRepo;
        $this->logger = $logger;
    }

    /**
     * Handle the event.
     *
     * @param  OrderWasCreated  $event
     * @return void
     */
    public function handle(OrderWasCreatedByUser $event)
    {
      $response = $this->rista->createOrder($event->order);
      if($response && $response->getBody())
      {
      $response = $response->getBody()->getContents();
      //$this->orderRepo->newJoolehOrder($response,$event->order->id);
      //$this->jooleh->confirmOrder($event->order);
      }
      $this->logger->log('Rista '.date('m d Y').' order-id '.$event->order->id.' response : '.$response);
    }
}
