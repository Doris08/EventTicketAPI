<?php
namespace App\Services\Tickets;

use App\Http\Controllers\Controller;
use App\Services\BaseService;
use App\Http\Requests\Refunds\CreateRequest;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Refund;
use App\Models\Ticket;
use Carbon\Carbon;
use DB;

class TicketService extends BaseService
{
    public function refund($request)
        {
        $ordersQty = OrderDetail::where('order_id', $request->order_id)
        ->where('ticket_type_id', $request->ticket_type_id)->pluck('quantity');

        if($ordersQty[0] < $request->quantity_to_refund)
        {
            return (new Controller())->errorResponse(null, 400, "Cannot Refund Tickets, Quantity to refund is higher than Quantity Sold");
        }

        $ticketsAvailable = OrderDetail::where('order_id', $request->order_id)
        ->join('tickets', 'order_details.id', '=', 'tickets.order_detail_id')
        ->where('tickets.status', 'Sold')->count();

        if($ticketsAvailable < $request->quantity_to_refund)
        {
            return (new Controller())->errorResponse(null, 400, "Cannot Refund, Tickets are in Checked-In or Refund Status for this order");
        }

        $orderDetail = OrderDetail::where('order_id', $request->order_id)->where('ticket_type_id',  $request->ticket_type_id)->get();
        $tickets = Ticket::where('order_detail_id', $orderDetail[0]['id'])->where('status', 'Sold')->get();

        for ($i=0; $i < $request->quantity_to_refund; $i++) { 
                
            DB::table('tickets')
            ->where('id', $tickets[$i]['id'])
            ->update(['status' => 'Refunded']);

            $refund = Refund::create([
                'ticket_id' => $tickets[$i]['id'],
                'date' => Carbon::now(),
                'time' => "10:20",
                'reason' => $request->reason
            ]);
        }
        
        return (new Controller())->successResponse(null, 200, "Tickets were refunded Successfully");
    }

    public function checkIn($request)
    {
        $ordersQty = OrderDetail::where('order_id', $request->order_id)
        ->where('ticket_type_id', $request->ticket_type_id)->pluck('quantity');

        if($ordersQty[0] < $request->quantity_to_checkin)
        {
            return (new Controller())->errorResponse(null, 400, "Cannot Check-In Tickets, Quantity to checkIn is higher than Quantity available");
        }

        $ticketsAvailable = OrderDetail::where('order_id', $request->order_id)
        ->join('tickets', 'order_details.id', '=', 'tickets.order_detail_id')
        ->where('tickets.status', 'Sold')->count();

        if($ticketsAvailable < $request->quantity_to_checkin)
        {
            return (new Controller())->errorResponse(null, 400, "Cannot Refund, Tickets are already in Checked-In or Refund Status for this order");
        }

        $orderDetail = OrderDetail::where('order_id', $request->order_id)->where('ticket_type_id',  $request->ticket_type_id)->get();
        $tickets = Ticket::where('order_detail_id', $orderDetail[0]['id'])->where('status', 'Sold')->get();

        for ($i=0; $i < $request->quantity_to_refund; $i++) { 
            
            DB::table('tickets')
            ->where('id', $tickets[$i]['id'])
            ->update(['status' => 'Checked-In']);
        }

        return (new Controller())->successResponse(null, 200, "Tickets were Checked-In Successfully");
    }
}