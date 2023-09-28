<?php

namespace App\Http\Controllers\Api\Tickets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Tickets\TicketService;
use App\Http\Requests\Refunds\CreateRequest;

class TicketController extends Controller
{
    public function refund(CreateRequest $request)
    {
        return (new TicketService())->refund($request);
    }

    public function checkIn(Request $request)
    {
        return (new TicketService())->checkIn($request);
    }

}
