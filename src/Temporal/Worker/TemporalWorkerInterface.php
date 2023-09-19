<?php

declare(strict_types=1);

namespace Buyanov\SymfonyTemporalWorker\Temporal\Worker;

interface TemporalWorkerInterface
{
    public function start(): void;
    public function addActivity(string $className): void;
    public function addWorkflow(string $className): void;
}