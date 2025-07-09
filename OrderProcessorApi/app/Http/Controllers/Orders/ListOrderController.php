<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OrderProcessorCore\App\UseCases\Order\ListOrderUseCase;

class ListOrderController extends Controller
{
    public function handle(Request $request)
    {
        return app(ListOrderUseCase::class)->handle();
    }
}
