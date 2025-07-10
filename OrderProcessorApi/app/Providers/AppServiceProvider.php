<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('OrderProcessorCore\Domain\Interfaces\IOrderRepository', 'App\Infra\Repositories\Order\OrderRepository');
        $this->app->bind('OrderProcessorCore\Domain\Interfaces\IProductRepository', 'App\Infra\Repositories\Product\ProductRepository');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
