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
        Schema::create('products', function (Blueprint $table) {
            $table->string('id')->primary()->comment('Unique identifier for the product');
            $table->string('name')->unique()->comment('Name of the product');
            $table->string('description')->nullable()->comment('Description of the product');
            $table->decimal('price', 10, 2)->comment('Price of the product');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
