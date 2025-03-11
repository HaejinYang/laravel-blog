<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ReflectionClass;
use ReflectionProperty;

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
