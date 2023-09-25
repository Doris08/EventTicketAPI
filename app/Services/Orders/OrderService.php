<?php
namespace App\Services\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\CreateRequest;
use App\Models\User;
use App\Http\Resources\Orders\OrderResource;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\TicketType;
use App\Models\Ticket;
use App\Services\BaseService;

class OrderService extends BaseService
{

    public function index($request)
    {
        try{

            $paginate = null;
            if (isset($request['limit'])) {
                $paginate = $request['limit'];  
            } 

            $orderResources = OrderResource::collection(Order::orderBy('purchase_date')->paginate($paginate));

            if (isset($request['search'])) {
                $search = $request['search'];
                $orders = Order::join('events', 'events.id', '=', 'orders.event_id')
                                ->join('attendees', 'attendees.id', '=', 'orders.attendee_id')
                                ->join('ticket_types', 'events.id', '=', 'ticket_types.event_id')
                                ->where('orders.purchase_date','LIKE',"%{$search}%")
                                ->orWhere('events.name','LIKE',"%{$search}%")
                                ->orWhere('attendees.name','LIKE',"%{$search}%")
                                ->orWhere('ticket_types.name','LIKE',"%{$search}%");
                $orderResources = OrderResource::collection($orders);
            }

            return $this->successResponse($orderResources, 200, "Orders founded successfully");

        } catch (\Throwable $th) {

            return $this->errorResponse(null, 500, "Something went wrong. Orders could not be founded");
        }
        
    }

    public function store(CreateRequest $request)
    {
        $order = new Order();
        $attendee = new Attendee();
        try {
            
            $attendee = Attendee::create([
                'name' => $request->attendee_name,
                'email' => $request->attendee_email
            ]);

            $order = Order::create([
                'event_id' => $request->event_id,
                'user_id' => auth()->user() ? auth()->user()->id : null,
                'payment_id' => 0,
                'purchase_date' => $request->purchase_date,
                'status' => 'Sold'
            ]);

            if ($order->exists) {
                for ($i = 0; $i < count($request->order_details); $i++) {
                    $detail = $request->order_details[$i];
                    $ticketType = TicketType::findOrFail($detail['ticket_type_id']);

                    if($detail['quantity'] > $ticketType->purchase_limit){
                        return  $this->verifyPurchaseLimit($order, $attendee, $detail, $ticketType);
                    }

                    if($detail['quantity'] > $ticketType->quantity_available){
                        return $this->verifyQtyAvailable($order, $attendee, $detail, $ticketType);
                    } 

                    $orderDetail = $this->createOrderDetail($order, $ticketType, $detail);
                    
                    $this->createTickets($orderDetail);
                }
            }

            $orderTotal = OrderDetail::where('order_id', $order->id)->sum('total');

            $payment = app('App\Http\Controllers\PaymentController')->postPayment($orderTotal);

            return $this->asignOrderAttendeePayment($attendee, $payment, $order, $request->order_details);

        } catch (\Throwable $th) {
            foreach ($order->orderDetails as $detail) {
                foreach ($detail->tickets as $ticket) {
                    $ticket->delete();
                }
                $detail->delete();
            }
            $order->delete();

            return $this->errorResponse(null, 500, "Something went wrong. Order could not be created.");
        }
    }

    public function show($id)
    {
        try {
            $orderResource = new OrderResource(Order::findOrFail($id));

            return $this->successResponse($orderResource, 200, "Order founded successfully");

         } catch (\Throwable $th) {

            return $this->errorResponse(null, 500, "Something went wrong. Order could not be founded");
        }
    }

    function createOrderDetail($order, $ticketType, $detail)
    {
        $orderDetail = OrderDetail::create([
            'order_id' => $order->id,
            'ticket_type_id' => $ticketType->id,
            'quantity' => $detail['quantity'],
            'sale_price' => $ticketType->price,
            'total' => number_format($detail['quantity'] * $ticketType->price, 2)
        ]);

        return $orderDetail;
    }

    function asignOrderAttendeePayment($attendee, $payment, $order, $orderDetails)
    {
        
        if($payment == 500){
            OrderDetail::where('order_id', $order->id)->delete();
            $order->delete();
            $attendee->delete();

            return $this->errorResponse(null, 400, "Payment could not be processed");

        }else {
            $this->updateTicketsQuantity($orderDetails);

            $order->update([    
                'attendee_id' => $attendee->id,
                'payment_id' => $payment
            ]); 

            $orderResources = new OrderResource($order);

            return $this->successResponse($orderResources, 201, "Order created successfully");
        }
    }

    function createTickets($orderDetail){
        
        for ($i=0; $i < $orderDetail->quantity; $i++) {
            Ticket::create([
                'order_detail_id' => $orderDetail->id,
                'ticket_type_id' => $orderDetail->ticket_type_id,
                'status' => 'Sold',
            ]);
        }
    }

    function updateTicketsQuantity($orderDetails){

        for ($i = 0; $i < count($orderDetails); $i++) {

           $ticketType = TicketType::findOrFail($orderDetails[$i]['ticket_type_id']);

           TicketType::where('id', $orderDetails[$i]['ticket_type_id'])->update([    
            'quantity_available' => $ticketType->quantity_available - $orderDetails[$i]['quantity'],
            'quantity_sold' => $ticketType->quantity_sold + $orderDetails[$i]['quantity']
            ]);
        }
    }

    function verifyPurchaseLimit($order, $attendee, $detail, $ticketType){
        
        OrderDetail::where('order_id', $order->id)->delete();
        $order->delete();
        $attendee->delete();

        return $this->errorResponse(null, 400, "Quantity to buy for TicketType '".$ticketType->name."' is higher tha its purchase limit");  
    }

    function verifyQtyAvailable($order, $attendee, $detail, $ticketType){
        
        OrderDetail::where('order_id', $order->id)->delete();
        $order->delete();
        $attendee->delete();

        return $this->errorResponse(null, 400, "Quantity available for TicketType '".$ticketType->name."' is ".$ticketType->quantity_available ." ticket");

    }
}