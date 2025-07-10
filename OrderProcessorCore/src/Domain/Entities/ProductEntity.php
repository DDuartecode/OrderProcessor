<?php

namespace OrderProcessorCore\Domain\Entities;

use DateTime;

class ProductEntity
{
    private string $id;
    private string $name;
    private ?string $description;
    private float $price;
    private ?DateTime $createdAt;
    private ?DateTime $updatedAt;

    public function __construct()
    {
        $this->id = '';
        $this->name = '';
        $this->description = null;
        $this->price = 0.0;
        $this->createdAt = null;
        $this->updatedAt = null;
    }


    public function isValid(): bool
    {
        if (empty($this->id)) {
            throw new \InvalidArgumentException('Product ID cannot be empty.');
        }

        if (empty($this->name)) {
            throw new \InvalidArgumentException('Product name cannot be empty.');
        }

        if ($this->price <= 0) {
            throw new \InvalidArgumentException('Product price must be greater than zero.');
        }

        if (empty($this->createdAt)) {
            throw new \InvalidArgumentException('Product creation date cannot be empty.');
        }

        if (empty($this->updatedAt)) {
            throw new \InvalidArgumentException('Product update date cannot be empty.');
        }

        return true;
    }

    public function fromArray(array $array): self
    {
        $date = new DateTime();

        $this->id = $array['Id'] ?? '';
        $this->name = $array['Name'] ?? '';
        $this->description = $array['Description'] ?? null;
        $this->price = $array['Price'] ?? 0.0;
        $this->createdAt = !empty($array['CreatedAt']) ? new DateTime($array['CreatedAt']) : $date;
        $this->updatedAt = !empty($array['UpdatedAt']) ? new DateTime($array['UpdatedAt']) : $date;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'Id' => $this->id,
            'Name' => $this->name,
            'Description' => $this->description,
            'Price' => $this->price,
            'CreatedAt' => $this->createdAt ? $this->createdAt->format(DateTime::ATOM) : null,
            'UpdatedAt' => $this->updatedAt ? $this->updatedAt->format(DateTime::ATOM) : null,
        ];
    }

    #region DB
    public function fromDB(array $array): Self
    {
        $date = new DateTime();

        $this->id = $array['id'] ?? '';
        $this->name = $array['name'] ?? '';
        $this->description = $array['description'] ?? null;
        $this->price = $array['price'] ?? 0.0;
        $this->createdAt = !empty($array['created_at']) ? new DateTime($array['created_at']) : $date;
        $this->updatedAt = !empty($array['updated_at']) ? new DateTime($array['updated_at']) : $date;

        return $this;
    }
    #regionf
}