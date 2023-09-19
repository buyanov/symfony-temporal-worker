<?php

declare(strict_types=1);

namespace Buyanov\SymfonyTemporalWorker\Temporal;

use Temporal\Client\ClientOptions;
use Temporal\Client\GRPC\ServiceClient;
use Temporal\Client\WorkflowClient;
use Temporal\Client\WorkflowClientInterface;

class WorkflowClientFactory
{
    public static function create(string $dsn, array $options): WorkflowClientInterface
    {
        return WorkflowClient::create(
            ServiceClient::create($dsn),
            (new ClientOptions())
                ->withNamespace($options['namespace'])
        );
    }
}