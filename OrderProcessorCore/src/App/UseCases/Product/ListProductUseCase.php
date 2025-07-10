<?php

namespace OrderProcessorCore\App\UseCases\Product;

use OrderProcessorCore\Domain\Entities\ProductEntity;
use OrderProcessorCore\Domain\Interfaces\IProductRepository;

class ListProductUseCase
{
    private IProductRepository $producRepository;

    public function __construct(IProductRepository $producRepository)
    {
        $this->producRepository = $producRepository;
    }

    /**
     * @return ProductEntity[]
     */
    public function handle(): array
    {
        $listProduct = $this->producRepository->getAllProducts();

        if(!empty($listProduct)) {
            return array_map(function($product) {
                $product->isValid();

                return $product;
            }, $listProduct);
        }
        
        return [];
    }
}
