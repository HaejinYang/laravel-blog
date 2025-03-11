<?php

namespace App\Responses;

use JsonSerializable;
use ReflectionClass;
use ReflectionProperty;

class BaseResponse implements JsonSerializable
{
    public function jsonSerialize(): array
    {
        $data = [];
        $reflection = new ReflectionClass($this);

        foreach ($reflection->getProperties(ReflectionProperty::IS_PRIVATE | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PUBLIC) as $property) {
            $data[$property->getName()] = $property->getValue($this);
        }

        return $data;
    }
}
