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

            Log::error('DB Error saving order: ' . $e->getMessage(), [
                'order' => $order->toArray(),
                'exception' => $e
            ]);
            throw $e;
        }
	}

	public function getOrderById($id) : ?OrderEntity
	{
		return new OrderEntity();
	}

    /**
     * @return OrderEntity[]
     */
	public function getAllOrders(): array
	{
        try {
            $orders = Order::with('productOrders.product')->get();

            if(!empty($orders)) {
                $orders = $orders->toArray();
                
                return array_map(function($order) {
                    return (new OrderEntity())->fromDB($order);
                }, $orders);
            }

            return [];
        } catch (\Exception $e) {
            Log::error('DB Error list orders: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            throw $e;
        }
	}

	public function updateOrder($order): bool
	{
		return true;
	}
}
