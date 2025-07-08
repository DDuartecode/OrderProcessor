<?php

namespace OrderProcessorCore\Domain\Entities;

use Ramsey\Uuid\Uuid;

class NewProductEntity
{
    private string $id = '';
    private string $name = '';
    private float $price = 0.0;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
    }

    public function isValid(): bool
    {
        if (empty($this->name)) {
            throw new \InvalidArgumentException('Product name cannot be empty.');
        }

        if ($this->price <= 0) {
            throw new \InvalidArgumentException('Product price must be greater than zero.');
        }

        return true;
    }

    public function fromArray(array $array): self
    {
        $this->name = $array['name'] ?? '';
        $this->price = $array['price'] ?? 0.0;

        return $this;
    }
}
