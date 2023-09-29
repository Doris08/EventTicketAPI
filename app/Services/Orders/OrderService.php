<?php

namespace App\Services\Orders;

use App\Http\Requests\Orders\CreateRequest;
use App\Http\Resources\Orders\OrderResource;
use App\Models\Attendee;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\TicketType;
use App\Models\Ticket;
use App\Services\BaseService;
use Stripe;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderCompleteNotification;
use Illuminate\Database\Eloquent\Builder;
use DB;

class OrderService extends BaseService
{
    public function index($request)
    {

        $userId = auth()->user()->id;
        try {

            $paginate = null;
            if (isset($request['limit'])) {
                $paginate = $request['limit'];
            }

            $orderResources = OrderResource::collection(Order::where('user_id', $userId)
                                                        ->orderBy('purchase_date')->paginate($paginate));

            if (isset($request['search'])) {
                $search = $request['search'];
                $orders = Order::where('user_id', $userId)
                                ->where('purchase_date', 'LIKE', '%' . $search . '%')
                                ->orWhereHas('attendee', function (Builder $query) use ($search) {
                                    $query->where('name', 'like', '%' . $search . '%');
                                })
                                ->orWhereHas('event', function (Builder $query) use ($search) {
                                    $query->where('name', 'like', '%' . $search . '%');
                                })->get();
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

        DB::beginTransaction();

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

                    if($detail['quantity'] > $ticketType->purchase_limit) {
                        return $this->errorResponse(null, 400, "Quantity to buy for TicketType '".$ticketType->name."' is higher tha its purchase limit");
                    }

                    if($detail['quantity'] > $ticketType->quantity_available) {
                        return $this->errorResponse(null, 400, "Quantity available for TicketType '".$ticketType->name."' is ".$ticketType->quantity_available ." ticket");
                    }

                    $orderDetail = $this->createOrderDetail($order, $ticketType, $detail);

                    $this->createTickets($orderDetail);
                }
            }

            $orderTotal = OrderDetail::where('order_id', $order->id)->sum('total');

            $payment = $this->payment((double)$orderTotal);

            return $this->asignOrderAttendeePayment($attendee, $payment, $order, $request->order_details);


        } catch (\Throwable $th) {

            DB::rollBack();

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

    public function createOrderDetail($order, $ticketType, $detail)
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

    public function asignOrderAttendeePayment($attendee, $payment, $order, $orderDetails)
    {

        if($payment == 500) {

            return $this->errorResponse(null, 400, "Payment could not be processed");

        } else {
            $this->updateTicketsQuantity($orderDetails);

            $order->update([
                'attendee_id' => $attendee->id,
                'payment_id' => $payment
            ]);

            $order->save();

            $orderResources = new OrderResource($order);

            DB::commit();

            $this->sendOrderConfirmationEmail($order, $orderDetails);

            return $this->successResponse($orderResources, 201, "Order created successfully");
        }
    }

    protected function createTickets($orderDetail)
    {

        for ($i = 0; $i < $orderDetail->quantity; $i++) {
            Ticket::create([
                'order_detail_id' => $orderDetail->id,
                'ticket_type_id' => $orderDetail->ticket_type_id,
                'status' => 'Sold',
            ]);
        }
    }

    protected function updateTicketsQuantity($orderDetails)
    {
        for ($i = 0; $i < count($orderDetails); $i++) {

            $ticketType = TicketType::findOrFail($orderDetails[$i]['ticket_type_id']);
            $ticketType->increment('quantity_sold', $orderDetails[$i]['quantity']);
            $ticketType->decrement('quantity_available', $orderDetails[$i]['quantity']);
        }
    }

    protected function payment($orderTotal)
    {
        try {
            $stripe = new \Stripe\StripeClient(
                env('STRIPE_SECRET')
            );

            Stripe\Stripe::setApiKey(
                env('STRIPE_SECRET')
            );

            $response = $stripe->paymentIntents->create([
                'amount' => 500,
                'currency' => 'usd',
                'payment_method' => 'pm_card_visa',
            ]);

            return $response->id;

        } catch (\Throwable $th) {

            return 500;
        }
    }

    protected function sendOrderConfirmationEmail($order, $orderDetail)
    {
        $confirmationMessage = [
            "attendeeName" => $order->attendee->name,
            "attendeeEmail" => $order->attendee->email,
            "orderId" => $order->id,
            "purchaseDate" => $order->purchase_date,
        ];

        try {

            Notification::route('mail', 'doris.aquino@elaniin.com')->notify(new OrderCompleteNotification($confirmationMessage));

        }catch (\Exception $ex) {
            return $ex;
        }
    }

}
