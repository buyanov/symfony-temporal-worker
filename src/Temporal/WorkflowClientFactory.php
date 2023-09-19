<?php

declare(strict_types=1);

namespace Buyanov\SymfonyTemporalWorker\Temporal;

use Temporal\Client\ClientOptions;
use Temporal\Client\GRPC\ServiceClient;
use Temporal\Client\WorkflowClient;
use Temporal\Client\WorkflowClientInterface;

class WorkflowClientFactory
{
    public static function create(string $dsn = 'localhost:7233', string $namespace = 'default'): WorkflowClientInterface
    {
        return WorkflowClient::create(
            ServiceClient::create($dsn),
            (new ClientOptions())
                ->withNamespace($namespace)
        );
    }
}
