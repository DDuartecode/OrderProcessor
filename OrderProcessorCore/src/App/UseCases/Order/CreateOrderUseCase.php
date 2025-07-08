<?php

namespace OrderProcessorCore\App\UseCases\Order;

use OrderProcessorCore\Domain\Entities\OrderEntity;
use OrderProcessorCore\Domain\Interfaces\IOrderRepository;

class CreateOrderUseCase
{
    private IOrderRepository $orderRepository;

    public function __construct(IOrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function handle(OrderEntity $order): bool
    {
        $order->isValid();

        return $this->orderRepository->setOrder($order);
    }
}