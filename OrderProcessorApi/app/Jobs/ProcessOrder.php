<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use OrderProcessorCore\Domain\Entities\OrderEntity;
use OrderProcessorCore\App\UseCases\Order\CreateOrderUseCase;

class ProcessOrder implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public $payload;

    public function handle(): void
    {
        try {
            $order = new OrderEntity();
            $order->fromArray($this->payload);

            Log::info('Processing order: ' . json_encode($order->toArray()));

            app(CreateOrderUseCase::class)->handle($order);

            Log::info('Order processed successfully: ' . json_encode($order->toArray()));
        } catch (\Throwable $e) {
            Log::error('Error processing order: ' . $e->getMessage());
            throw $e;
        }
    }
}
