<?php

namespace App\Http\Controllers\BoltBite;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BoltBite\OrderController;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function process(Request $request)
    {
        $orderController = app(OrderController::class);
        return $orderController->store($request);
    }
}
