<?php

namespace OrderProcessorCore\App\UseCases\Order;

use OrderProcessorCore\Domain\Entities\OrderEntity;
use OrderProcessorCore\Domain\Interfaces\IOrderRepository;

class ListOrderUseCase
{
    private IOrderRepository $orderRepository;

    public function __construct(IOrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @return OrderEntity[]
     */
    public function handle(): array
    {
        $listOrder = $this->orderRepository->getAllOrders();

        if(!empty($listOrder)) {
            return array_map(function($order) {
                $order->isValid();

                return $order;
            }, $listOrder);
        }

        return [];
    }
}
