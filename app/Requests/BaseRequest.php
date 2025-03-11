<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ReflectionClass;
use ReflectionProperty;

/**
 * API 요청 클래스의 최상위 클래스. 모든 요청클래스는 BaseRequest를 상속해야 함.
 */
abstract class BaseRequest
{
    public function __construct(array $data)
    {
        foreach ($this->getProperties() as $property) {
            $name = $property->getName();
            if (isset($data[$name])) {
                $property->setValue($this, $data[$name]);
            }
        }
    }

    private function getProperties(): array
    {
        return (new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PROTECTED);
    }

    public static function fromArray(FormRequest $formRequest): static
    {
        return new static($formRequest->validated());
    }

    public function toArray(): array
    {
        $data = [];
        foreach ($this->getProperties() as $property) {
            $name = $property->getName();
            $data[$name] = $this->$name;
        }
        return $data;
    }
}
