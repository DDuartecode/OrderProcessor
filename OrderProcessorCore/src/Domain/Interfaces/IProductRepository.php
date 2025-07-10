<?php

namespace OrderProcessorCore\Domain\Interfaces;

interface IProductRepository
{
    public function getAllProducts(): array;
}