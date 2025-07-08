<?php

namespace OrderProcessorCore\Domain\Interfaces;

use OrderProcessorCore\Domain\Entities\OrderEntity;

interface IOrderRepository
{
    public function setOrder(OrderEntity $order): bool;
    public function getOrderById(string $id): ?OrderEntity;
    public function getAllOrders(): array;
    public function updateOrder(OrderEntity $order): bool;
}