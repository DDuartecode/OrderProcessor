<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use OrderProcessorCore\Domain\Entities\ProductEntity;
use OrderProcessorCore\App\UseCases\Product\ListProductUseCase;

class ListProductController extends Controller
{
    /**
     * @var ProductEntity[]
     */
    private array $products = [];

    public function handle(Request $request)
    {
        try {
            Log::info('Processing product listing');

            $this->products = app(ListProductUseCase::class)->handle();

            if(!empty($this->products)) {

                $data = array_map(function($product) {
                    return $product->toArray();
                }, $this->products);
                
                Log::info('Products listed successfully');
                
                return response()->json([
                        "message" => "Products listed successfully",
                        "data" => $data
                    ], Response::HTTP_OK
                );
            }

            Log::info('Products not found');
            return response()->json([
                "message" => "Products not found",
                "data" => []
                ], Response::HTTP_OK
            );

        } catch (\Throwable $th) {
            Log::error('Unexpected failure in the product listing process: '. $th->getMessage());
            return response()->json([
                    "message" => "Unexpected failure in the product listing process"
                ], Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
