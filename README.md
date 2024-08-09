Для регистрации активити и воркфлоу нужно использовать 

```php
#[AutoconfigureTag('temporal.activity')]

#[AutoconfigureTag('temporal.workflow')]
```

соответственно.

Пример активити

```php

<?php

namespace App\Temporal\Activity;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;

#[AutoconfigureTag('temporal.activity')]
#[ActivityInterface('ProductProperty.')]
interface ProductPropertyActivityInterface
{
    #[ActivityMethod('createOrUpdateProductProperty')]
    public function createOrUpdateProductProperty(string $productGuid, string $propertyNameGuid, string $value): string;
}
```
