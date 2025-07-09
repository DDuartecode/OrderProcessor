<?php

namespace OrderProcessorCore\Domain\Entities;

use DateTime;
use OrderProcessorCore\Domain\Enums\OrderStatus;

class OrderEntity
{
    private string $id;
    private float $totalAmount;
    private OrderStatus $status;
    private ?DateTime $createdAt;
    private ?DateTime $updatedAt;

    /**
     * @var ProductOrderEntity[]
     */
    private array $products;

    public function __construct()
    {
        $this->id = '';
        $this->products = [];
        $this->totalAmount = 0.0;
        $this->status = OrderStatus::Pending;
        $this->createdAt = null;
        $this->updatedAt = null;
    }

    public function isValid(): bool
    {
        if (empty($this->id)) {
            throw new \InvalidArgumentException('Order ID cannot be empty.');
        }

        if (empty($this->products)) {
            throw new \InvalidArgumentException('Order must contain at least one product.');
        }

        foreach ($this->products as $product) {
            $product->setOrderId($this->id);
            $product->isValid();
        }

        if($this->totalAmount <= 0) {
            throw new \InvalidArgumentException('Total amount must be greater than zero.');
        }

        return true;
    }

    private function setProducts(array $products, DateTime $date ): array
    {
        return array_map(function ($product) use ($date) {
            return (new ProductOrderEntity())->fromArray($product, $date);
        }, $products);
    }

    private function getProducts(): array
    {
        return array_map(function ($product) {
            return $product->toArray();
        }, $this->products);
    }

    public function fromArray(array $array): Self
    {
        $date = new DateTime();

        $this->id = $array['Id'] ?? '';
        $this->products = $array['Products'] ? $this->setProducts($array['Products'], $date) : [];
        $this->totalAmount = $array['TotalAmount'] ?? 0.0;
        $this->status = !empty($array['Status']) ? OrderStatus::map($array['Status']) : OrderStatus::Pending;
        $this->createdAt = !empty($array['CreatedAt']) ? new DateTime($array['CreatedAt']) : $date;
        $this->updatedAt = !empty($array['UpdatedAt']) ? new DateTime($array['UpdatedAt']) : $date;


        return $this;
    }

    public function toArray(): array
    {
        return [
            'Id' => $this->id,
            'Products' => $this->getProducts(),
            'TotalAmount' => $this->totalAmount,
            'Status' => $this->status->value,
            'CreatedAt' => $this->createdAt ? $this->createdAt->format(DateTime::ATOM) : null,
            'UpdatedAt' => $this->updatedAt ? $this->updatedAt->format(DateTime::ATOM) : null,
        ];
    }

    public function getOrderToInsert(): array
    {
        return [
            'id' => $this->id,
            'total_amount' => $this->totalAmount,
            'status' => $this->status,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s')
        ];
    }

    public function fromDB(array $order): Self
    {
        return $this;
    }

    public function getProductsToInsert(): array
    {
        return array_map(function ($product){
            return $product->toInsert();
        }, $this->products);
    }

    public function setStatus(OrderStatus $status): Self
    {
        $this->status = $status;
        return $this;
    }
}