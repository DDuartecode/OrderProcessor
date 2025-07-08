<?php

namespace App\Jobs;

use Illuminate\Support\Str;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob as BaseJob;

class RabbitMQJob extends BaseJob
{
    protected $bodyDecoded;

    public function getRawBody(): string
    {
        if ($this->bodyDecoded) {
            return json_encode($this->bodyDecoded);
        }

        $bodyOriginal = json_decode(parent::getRawBody(), true);

        // Monta um payload compatível com o laravel
        // para que o job seja processado corretamente
        $this->bodyDecoded = [
            'uuid' => (string) Str::uuid(),
            'displayName' => 'App\\Jobs\\ProcessOrder',
            'job' => 'App\\Jobs\\ProcessOrder@handle',
            'maxTries' => null,
            'timeout' => null,
            'data' => [
                'payload' => $bodyOriginal,
            ],
        ];

        return json_encode($this->bodyDecoded);
    }

    public function payload(): array
    {
        return json_decode($this->getRawBody(), true);
    }

    public function fire(): void
    {
        $payload = $this->payload();

        $class = \App\Jobs\ProcessOrder::class;
        $method = 'handle';

        // cria instância do job, injeta o payload
        $job = $this->resolve($class);
        $job->payload = $payload['data']['payload'];

        $this->instance = $job;

        $job->{$method}();

        $this->delete();
    }
}
