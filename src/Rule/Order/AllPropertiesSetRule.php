<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Rule\Order;

use Exception;
use MyParcelNL\Sdk\src\Model\Fulfilment\OrderNote;
use MyParcelNL\Sdk\src\Rule\Rule;
use ReflectionClass;

class AllPropertiesSetRule extends Rule
{
    /**
     * @param $validationSubject OrderNote
     *
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function validate($validationSubject): void
    {
        $properties = (new ReflectionClass($validationSubject))->getProperties();

        array_map(function($property) use ($validationSubject) {
            $getter = 'get' . ucfirst($property->getName());

            if (
                method_exists($validationSubject, $getter)
                && null === $validationSubject->$getter()
            ) {
                $this->addError(sprintf('All properties must be set on %s', get_class($validationSubject)));
            }
        }, $properties);
    }
}
