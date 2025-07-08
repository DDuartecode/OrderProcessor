<?php

namespace OrderProcessorCore\Tests\Domain\Entities;

use DateTime;
use OrderProcessorCore\Domain\Entities\ProductOrderEntity;
use PHPUnit\Framework\TestCase;

class ProductOrderEntityTests extends TestCase
{
    public function test_from_array_populates_properties_correctly()
    {
        $date = new DateTime();
        $data = [
            'OrderId' => 'order-123',
            'ProductId' => 'product-456',
            'Quantity' => 3,
            'Price' => 49.90,
            'CreatedAt' => $date->format(DateTime::ATOM),
            'UpdatedAt' => $date->format(DateTime::ATOM),
        ];

        $productOrder = (new ProductOrderEntity())->fromArray($data, $date);
        $result = $productOrder->toArray();

        $this->assertEquals('order-123', $result['OrderId']);
        $this->assertEquals('product-456', $result['ProductId']);
        $this->assertEquals(3, $result['Quantity']);
        $this->assertEquals(49.90, $result['Price']);
        $this->assertEquals($date->format(DateTime::ATOM), $result['CreatedAt']);
        $this->assertEquals($date->format(DateTime::ATOM), $result['UpdatedAt']);
    }

    public function test_to_insert_returns_proper_database_format()
    {
        $date = new DateTime();
        $data = [
            'OrderId' => 'order-xyz',
            'ProductId' => 'prod-abc',
            'Quantity' => 1,
            'Price' => 100.00,
        ];

        $productOrder = (new ProductOrderEntity())->fromArray($data, $date);
        $productOrderToInsert = $productOrder->toInsert();

        $this->assertEquals('order-xyz', $productOrderToInsert['order_id']);
        $this->assertEquals('prod-abc', $productOrderToInsert['product_id']);
        $this->assertEquals(1, $productOrderToInsert['quantity']);
        $this->assertEquals(100.00, $productOrderToInsert['price']);
        $this->assertEquals($date->format('Y-m-d H:i:s'), $productOrderToInsert['created_at']);
        $this->assertEquals($date->format('Y-m-d H:i:s'), $productOrderToInsert['updated_at']);
    }

    public function test_is_valid_returns_true_for_valid_data()
    {
        $productOrder = (new ProductOrderEntity())->fromArray([
            'ProductId' => 'valid-prod',
            'Quantity' => 2,
            'Price' => 10.0
        ], new DateTime());

        $this->assertTrue($productOrder->isValid());
    }

    public function test_is_valid_throws_exception_for_empty_product_id()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product ID cannot be empty.');

        (new ProductOrderEntity())->fromArray([
            'ProductId' => '',
            'Quantity' => 1,
            'Price' => 10.0
        ], new DateTime())->isValid();
    }

    public function test_is_valid_throws_exception_for_zero_quantity()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Quantity must be greater than zero.');

        (new ProductOrderEntity())->fromArray([
            'ProductId' => 'prod-id',
            'Quantity' => 0,
            'Price' => 5.0
        ], new DateTime())->isValid();
    }

    public function test_is_valid_throws_exception_for_negative_price()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Price cannot be negative.');

        (new ProductOrderEntity())->fromArray([
            'ProductId' => 'prod-id',
            'Quantity' => 2,
            'Price' => -1.0
        ], new DateTime())->isValid();
    }
}
