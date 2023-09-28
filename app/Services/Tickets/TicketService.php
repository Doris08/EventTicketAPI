<?php

namespace App\Services\Tickets;

use App\Services\BaseService;
use App\Models\OrderDetail;
use App\Models\Refund;
use App\Models\Ticket;
use Carbon\Carbon;
use DB;

class TicketService extends BaseService
{
    public function refund($request)
    {

        try {

            $ticketsQtyAvailableToRefund = OrderDetail::where('order_id', $request->order_id)
                                    ->join('tickets', 'order_details.id', '=', 'tickets.order_detail_id')
                                    ->where('ticket_type_id', $request->ticket_type_id)
                                    ->where('tickets.status', 'Sold')->count();

            $ticketsQtyInOrder = OrderDetail::where('order_id', $request->order_id)
                                    ->join('tickets', 'order_details.id', '=', 'tickets.order_detail_id')
                                    ->where('ticket_type_id', $request->ticket_type_id)->count();

            if($ticketsQtyAvailableToRefund < $request->quantity_to_refund) {
                return $this->errorResponse(null, 400, "Cannot refund tickets, quantity to refund is higher than quantity of tickets that can be refunded for this order");
            }

            $orderDetail = OrderDetail::where('order_id', $request->order_id)->where('ticket_type_id', $request->ticket_type_id)->first();
            $ticketsRefunded = Ticket::where('order_detail_id', $orderDetail->id)->where('status', 'Refunded')->count();
            $ticketsSold = Ticket::where('order_detail_id', $orderDetail->id)->where('status', 'Sold')->get();

            return $this->createRefund($request, $ticketsSold, $ticketsQtyAvailableToRefund, $ticketsQtyInOrder, $orderDetail);

        } catch (\Throwable $th) {

            return $this->errorResponse($th, 500, "Something went wrong. Tickets could not be refunded");
        }

    }

    public function checkIn($request)
    {

        try {

            $ordersQty = OrderDetail::where('order_id', $request->order_id)
                                    ->where('ticket_type_id', $request->ticket_type_id)->pluck('quantity');

            if($ordersQty[0] < $request->quantity_to_checkin) {
                return $this->errorResponse(null, 400, "Cannot check-in tickets, quantity to check-in is higher than quantity available");
            }

            $ticketsAvailable = OrderDetail::where('order_id', $request->order_id)
                                            ->join('tickets', 'order_details.id', '=', 'tickets.order_detail_id')
                                            ->where('tickets.status', 'Sold')->count();

            if($ticketsAvailable < $request->quantity_to_checkin) {
                return $this->errorResponse(null, 400, "Cannot refund, tickets are already in checked-in or refund status for this order");
            }

            $orderDetail = OrderDetail::where('order_id', $request->order_id)->where('ticket_type_id', $request->ticket_type_id)->get();
            $tickets = Ticket::where('order_detail_id', $orderDetail[0]['id'])->where('status', 'Sold')->get();

            for ($i = 0; $i < $request->quantity_to_refund; $i++) {

                DB::table('tickets')
                    ->where('id', $tickets[$i]['id'])
                    ->update(['status' => 'Checked-In']);
            }

            return $this->successResponse(null, 200, "Tickets were checked-in successfully");

        } catch (\Throwable $th) {

            return $this->errorResponse($th, 500, "Something went wrong. Tickets could not be checked in");
        }

    }

    protected function createRefund($request, $ticketsSold, $ticketsQtyAvailableToRefund, $ticketsQtyInOrder, $orderDetail)
    { 
       
        DB::beginTransaction();
        try {
            $ticketStatus = 'Refunded';
            $orderStatus = '';

            for ($i = 0; $i < $request->quantity_to_refund; $i++) {

                DB::table('tickets')->where('id', $ticketsSold[$i]['id'])->update([
                    'status' => $ticketStatus,
                ]);

                $ticketType = DB::table('ticket_types')->where('id', $request->ticket_type_id);
                $ticketType->increment('quantity_available');
                $ticketType->decrement('quantity_sold');

                $refund = new Refund([
                    'ticket_id' => $ticketsSold[$i]['id'],
                    'datetime' => date('Y-m-d H:i:s'),
                    'reason' => $request->reason
                ]);

            }

            $refund->save();

            DB::commit();

            $ticketsRefunded = Ticket::where('order_detail_id', $orderDetail->id)->where('status', 'Refunded')->count();

            if ($ticketsQtyInOrder > $ticketsRefunded) {
                $orderStatus = 'Partial Refund';
            }else{
                $orderStatus = 'Complete Refund';
            }

            DB::table('orders')->where('id', $request->order_id)->update([
                'status' => $orderStatus,
            ]);

            return $this->successResponse(null, 200, "Tickets were refunded successfully");

        } catch (\Exception $e) {
            
            DB::rollBack();
        
            return $this->errorResponse(null, 500, "Something went wrong. Refund could not be processed");
        }
       
    }
}
