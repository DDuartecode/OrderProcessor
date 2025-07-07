<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob as BaseJob;

class ProcessOrder extends BaseJob
{
    public function fire() : void
    {
        $payload = $this->payload();

        Log::info('Processing order', [
            'payload' => $payload,
        ]);
    }

    public function getName()
    {
        return '';
    }
}