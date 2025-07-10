<?php

namespace App\Http\Controllers\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use OrderProcessorCore\Domain\Entities\OrderEntity;
use OrderProcessorCore\App\UseCases\Order\ListOrderUseCase;

class ListOrderController extends Controller
{
    /**
     * @var OrderEntity[]
     */
    private array $orders = [];

    public function handle(Request $request)
    {
        try {
            Log::info('Processing order listing');

            $this->orders = app(ListOrderUseCase::class)->handle();

            if(!empty($this->orders)) {

                $data = array_map(function($order) {
                    return $order->toArray();
                }, $this->orders);
                
                Log::info('Orders listed successfully');
                
                return response()->json([
                        "message" => "Orders listed successfully",
                        "data" => $data
                    ], Response::HTTP_OK
                );
            }

            Log::info('Orders not found');
            return response()->json([
                "message" => "Orders not found",
                "data" => []
                ], Response::HTTP_OK
            );

        } catch (\Throwable $th) {
            Log::error('Unexpected failure in the order listing process: '.$th->getMessage());
            return response()->json([
                    "message" => "Unexpected failure in the order listing process"
                ], Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }


    }
}
