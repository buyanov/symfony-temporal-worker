<?php

namespace Buyanov\SymfonyTemporalWorker\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ActivityInboundInterceptor
{
    public const TAG = 'temporal.activity_inbound_interceptor';
}
