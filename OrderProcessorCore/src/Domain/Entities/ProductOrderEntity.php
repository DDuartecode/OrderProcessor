<?php

namespace OrderProcessorCore\Domain\Entities;

use DateTime;

class ProductOrderEntity
{
    private string $order_id;
    private string $product_id;
    private int $quantity;
    private float $price;
    private ?DateTime $createdAt;
    private ?DateTime $updatedAt;

    public function __construct() 
    {
        $this->order_id = '';
        $this->product_id = '';
        $this->quantity = 0;
        $this->price = 0.0;
        $this->createdAt = null;
        $this->updatedAt = null;
    }

    public function isValid(): bool
    {
        if (empty($this->id)) {
            throw new \InvalidArgumentException('Product ID cannot be empty.');
        }

        if ($this->quantity <= 0) {
            throw new \InvalidArgumentException('Quantity must be greater than zero.');
        }

        if ($this->price < 0) {
            throw new \InvalidArgumentException('Price cannot be negative.');
        }

        return true;
    }

    public function fromArray(array $array, DateTime $date): self
    {
        $this->order_id = $array['OrderId'] ?? '';
        $this->product_id = $array['ProductId'] ?? '';
        $this->quantity = $array['Quantity'] ?? 0;
        $this->price = $array['Price'] ?? 0.0;
        $this->createdAt = !empty($array['CreatedAt']) ? new DateTime($array['CreatedAt']) : $date;
        $this->updatedAt = !empty($array['UpdatedAt']) ? new DateTime($array['UpdatedAt']) : $date;        

        return $this;
    }

    public function toArray(): array
    {
        return [
            'OrderId' => $this->order_id,
            'ProductId' => $this->product_id,
            'Quantity' => $this->quantity,
            'Price' => $this->price,
            'CreatedAt' => $this->createdAt ? $this->createdAt->format(DateTime::ATOM) : null,
            'UpdatedAt' => $this->updatedAt ? $this->updatedAt->format(DateTime::ATOM) : null,            
        ];
    }

    public function toInsert(string $order_id): array
    {
        return [
            'order_id' => $order_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s')
        ];
    }
}