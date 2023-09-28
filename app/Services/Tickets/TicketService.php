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

            $ordersQty = OrderDetail::where('order_id', $request->order_id)
                                    ->join('tickets', 'order_details.id', '=', 'tickets.order_detail_id')
                                    ->where('ticket_type_id', $request->ticket_type_id)
                                    ->where('tickets.status', 'Sold')->count();

            if($ordersQty < $request->quantity_to_refund) {
                return $this->errorResponse(null, 400, "Cannot refund tickets, quantity to refund is higher than quantity sold");
            }

            $ticketsAvailable = OrderDetail::where('order_id', $request->order_id)
                                            ->join('tickets', 'order_details.id', '=', 'tickets.order_detail_id')
                                            ->where('tickets.status', 'Sold')->count();

            if($ticketsAvailable < $request->quantity_to_refund) {
                return $this->errorResponse(null, 400, "Cannot refund, tickets are in checked-in or refund status for this order");
            }

            $orderDetail = OrderDetail::where('order_id', $request->order_id)->where('ticket_type_id', $request->ticket_type_id)->get();
            $tickets = Ticket::where('order_detail_id', $orderDetail[0]['id'])->where('status', 'Sold')->get();

            $this->createRefund($request, $tickets, $ordersQty);

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

    protected function createRefund($request, $tickets, $ordersQty){

        DB::beginTransaction();
        try {

            for ($i = 0; $i < $request->quantity_to_refund; $i++) {

                DB::table('tickets')
                    ->where('id', $tickets[$i]['id'])
                    ->update(['status' => 'Refunded']);

                $refund = new Refund([
                    'ticket_id' => $tickets[$i]['id'],
                    'date' => Carbon::now(),
                    'time' => Carbon::now()->format('H:i'),
                    'reason' => $request->reason
                ]);
            }


            if ($request->quantity_to_refund < $ordersQty) {
                
            }

            $refund->save();
            DB::commit();

            return $this->successResponse(null, 200, "Tickets were refunded successfully");
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse($th, 500, "Something went wrong. Refund could not be proccess");
        }
    }
}
