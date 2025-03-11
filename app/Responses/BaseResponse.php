<?php

namespace App\Responses;

use JsonSerializable;
use ReflectionClass;
use ReflectionProperty;

/**
 * API 응답 클래스의 최상위 클래스. 모든 응답클래스는 BaseResponse를 상속해야 함.
 */
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
