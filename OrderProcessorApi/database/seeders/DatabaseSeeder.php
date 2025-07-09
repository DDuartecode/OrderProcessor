<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use OrderProcessorCore\Domain\Enums\OrderStatus;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        #region Create products
        Product::factory()->create([
            'id' => '8e51c4d5-93c1-4204-a356-93d9140aa35a',
            'name' => 'Produto 1',
            'description' => 'Produto de teste 1',
            'price' => 19.99,
        ]);
        Product::factory()->create([
            'id' => 'b1ff84c2-8c5e-4db2-aeb3-d0a8d82041de',
            'name' => 'Produto 2',
            'description' => 'Produto de teste 2',
            'price' => 29.99,
        ]);
        Product::factory()->create([
            'id' => '1d912ed6-4e1b-4ed4-a1fb-ecc0669e928f',
            'name' => 'Produto 3',
            'description' => 'Produto de teste 3',
            'price' => 08.99,
        ]);
        #endregion

        #region Create orders
        Order::factory()->create([
            'id' => 'd361c49d-6ce7-4d39-baf2-d04e37209c72',
            'status' => OrderStatus::Pending->value, // pending
            'total_amount' => 47.97, //p3+p3+p2
        ]);
        Order::factory()->create([
            'id' => '2e5bbf0a-f22c-4977-85ad-7815354170d5',
            'status' => OrderStatus::Processing->value, // processing
            'total_amount' => 29.99, //p2
        ]);
        Order::factory()->create([
            'id' => 'f0eb0c5d-d131-49d7-8a6f-dc6d52037d6f',
            'status' => OrderStatus::Completed->value, // completed
            'total_amount' => 19.99, //p1
        ]);
        #endregion

        #region add products to orders
        ProductOrder::factory()->create([
            'order_id' => 'd361c49d-6ce7-4d39-baf2-d04e37209c72', //o1
            'product_id' => '1d912ed6-4e1b-4ed4-a1fb-ecc0669e928f', // p3
            'price' => 8.99, 
            'quantity' => 2,
        ]);
        ProductOrder::factory()->create([
            'order_id' => 'd361c49d-6ce7-4d39-baf2-d04e37209c72', //o1
            'product_id' => 'b1ff84c2-8c5e-4db2-aeb3-d0a8d82041de', // p2
            'price' => 29.99,
            'quantity' => 1,                
        ]);
        ProductOrder::factory()->create([
            'order_id' => '2e5bbf0a-f22c-4977-85ad-7815354170d5', //o2
            'product_id' => 'b1ff84c2-8c5e-4db2-aeb3-d0a8d82041de', // p2
            'price' => 29.99,
            'quantity' => 1,    
        ]);
        ProductOrder::factory()->create([
            'order_id' => 'f0eb0c5d-d131-49d7-8a6f-dc6d52037d6f', //o3
            'product_id' => '8e51c4d5-93c1-4204-a356-93d9140aa35a', // p1
            'price' => 19.99,
            'quantity' => 1,
        ]);
        #endregion
    }
}
