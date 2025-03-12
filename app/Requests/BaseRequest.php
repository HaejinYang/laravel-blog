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
    public function __construct(array $data = [])
    {
        foreach ($this->getProperties() as $property) {
            $name = $property->getName();
            if (isset($data[$name])) {
                $property->setValue($this, $data[$name]);
            } else {
                $defaultValues = $this->defaultValues();
                $this->$name = $defaultValues[$name] ?? null; // 기본값 설정
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

    /**
     * 상속받는 클래스에서 오버라이딩해서 프로퍼티의 기본값을 설정할 수 있음
     */
    protected function defaultValues(): array
    {
        return [];
    }
}
