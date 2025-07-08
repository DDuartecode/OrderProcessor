<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products_orders', function (Blueprint $table) {
            $table->string('order_id')->comment('ID of the order');
            $table->string('product_id')->comment('ID of the product');
            $table->integer('quantity')->default(1)->comment('Quantity of the product in the order');
            $table->decimal('price', 10, 2)->comment('Price of the product at the time of the order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_order');
    }
};
