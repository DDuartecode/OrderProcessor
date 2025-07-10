<?php

namespace App\Infra\Repositories\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OrderProcessorCore\Domain\Entities\ProductEntity;
use OrderProcessorCore\Domain\Interfaces\IProductRepository;

class ProductRepository implements IProductRepository
{
    /**
     * @return ProductEntity[]
     */
    public function getAllProducts(): array
    {
        try {
            $products = Product::all();

            if(!empty($products)) {
                $products = $products->toArray();
                
                return array_map(function($order) {
                    return (new ProductEntity())->fromDB($order);
                }, $products);
            }
            
            return [];
        } catch (\Exception $e) {
            Log::error('DB Error list products: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            throw $e;
        }
    }
}
