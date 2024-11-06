<?php

namespace CpmsCommonTest\Mock;

use CpmsCommon\Utility\AmountFormatterTrait;
use Laminas\EventManager\EventManagerAwareInterface;
use Laminas\EventManager\EventManagerAwareTrait;

class SimpleServiceMock implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;
    use AmountFormatterTrait;

    public function fireEvent(string $suffix = 'pre'): void
    {

        $this->getEventManager()->trigger(__METHOD__ . '.' . $suffix, $this, compact('suffix'));
    }
}
