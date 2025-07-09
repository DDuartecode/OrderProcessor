<?php

namespace App\Infra\Repositories\Order;

use App\Models\Order;
use App\Models\ProductOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OrderProcessorCore\Domain\Entities\OrderEntity;
use OrderProcessorCore\Domain\Interfaces\IOrderRepository;

class OrderRepository implements IOrderRepository
{
	public function setOrder(OrderEntity $order) : bool
	{
        try {
            $orderToInsert = $order->getOrderToInsert();
            $productsOrderToInsert = $order->getProductsToInsert();

            DB::beginTransaction();
            
            Order::create($orderToInsert);
            ProductOrder::insert($productsOrderToInsert);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error saving order: ' . $e->getMessage(), [
                'order' => $order->toArray(),
                'exception' => $e
            ]);
            return false;
        }
	}

	public function getOrderById($id) : ?OrderEntity
	{
		return (new OrderEntity())->fromArray([
            'Id' => $id,
            'Products' => [],
            'OrderDate' => '2023-10-01 12:00:00',
            'TotalAmount' => 100.0,
            'Status' => 'Pending'
        ]);
	}

	public function getAllOrders(): array
	{
        $orders = Order::with('productOrders.product')->get();

        return $orders->toArray();
	}

	public function updateOrder($order): bool
	{
		return true; // TODO: Implement updateOrder() method.
	}
}
