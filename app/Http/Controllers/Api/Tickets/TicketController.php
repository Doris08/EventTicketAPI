<?php

namespace App\Http\Controllers\Api\Tickets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Refunds\CreateRequest;
use App\Http\Requests\Tickets\CheckInRequest;
use App\Services\Tickets\TicketService;

class TicketController extends Controller
{

   public function refund(Request $request)
   {
        return (new TicketService())->refund($request);
   }

   public function checkIn(Request $request)
   {
        return (new TicketService())->checkIn($request);
   }

}