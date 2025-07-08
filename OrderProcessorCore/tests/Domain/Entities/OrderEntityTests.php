<?php

namespace OrderProcessorCore\Tests\Domain\Entities;

use DateTime;
use OrderProcessorCore\Domain\Entities\OrderEntity;
use OrderProcessorCore\Domain\Entities\ProductOrderEntity;
use OrderProcessorCore\Domain\Enums\OrderStatus;
use PHPUnit\Framework\TestCase;

class OrderEntityTests extends TestCase
{
    public function test_from_array_populates_order_correctly()
    {
        $now = new DateTime();

        $data = [
            'Id' => 'order-123',
            'TotalAmount' => 100.0,
            'Status' => 1,
            'CreatedAt' => $now->format(DateTime::ATOM),
            'UpdatedAt' => $now->format(DateTime::ATOM),
            'Products' => [
                [
                    'ProductId' => 'prod-1',
                    'Quantity' => 2,
                    'Price' => 50.0
                ]
            ]
        ];

        $order = (new OrderEntity())->fromArray($data);

        $this->assertEquals('order-123', $order->toArray()['Id']);
        $this->assertEquals(100.0, $order->toArray()['TotalAmount']);
        $this->assertEquals(OrderStatus::Pending->value, $order->toArray()['Status']);
        $this->assertCount(1, $order->toArray()['Products']);
    }

    public function test_is_valid_throws_exception_on_empty_id()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Order ID cannot be empty.');

        $data = [
            'Id' => '',
            'Products' => [
                [
                    'ProductId' => 'p1',
                    'Quantity' => 1,
                    'Price' => 10.0
                ]
            ],
            'TotalAmount' => 10.0
        ];

        (new OrderEntity())->fromArray($data)->isValid();
    }

    public function test_is_valid_throws_exception_on_empty_products()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Order must contain at least one product.');

        $data = [
            'Id' => 'some-id',
            'Products' => [],
            'TotalAmount' => 10.0
        ];

        (new OrderEntity())->fromArray($data)->isValid();
    }

    public function test_get_order_to_insert_returns_correct_data()
    {
        $data = [
            'Id' => 'order-xyz',
            'TotalAmount' => 99.99,
            'Status' => 1,
            'CreatedAt' => (new DateTime())->format(DateTime::ATOM),
            'UpdatedAt' => (new DateTime())->format(DateTime::ATOM),
            'Products' => [
                [
                    'ProductId' => 'abc',
                    'Quantity' => 1,
                    'Price' => 99.99
                ]
            ]
        ];

        $order = (new OrderEntity())->fromArray($data);

        $insert = $order->getOrderToInsert();

        $this->assertEquals('order-xyz', $insert['id']);
        $this->assertEquals(99.99, $insert['total_amount']);
        $this->assertEquals(OrderStatus::Pending, $insert['status']);
    }

    public function test_get_products_to_insert_contains_order_id()
    {
        $data = [
            'Id' => 'order-456',
            'TotalAmount' => 199.99,
            'Status' => 1,
            'Products' => [
                [
                    'ProductId' => 'p-123',
                    'Quantity' => 2,
                    'Price' => 100.0
                ]
            ]
        ];

        $order = (new OrderEntity())->fromArray($data);
        $order->isValid();
        $productsToInsert = $order->getProductsToInsert();

        $this->assertEquals('order-456', $productsToInsert[0]['order_id']);
    }
}
