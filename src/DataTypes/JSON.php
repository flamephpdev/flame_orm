<?php

namespace FlamePHPDev\FlameQuery\DataTypes;

use FlamePHPDev\FlameQuery\Interfaces\IDataType;

readonly class JSON implements IDataType {
    public function __construct(private string $data) {}

    public function getType(): string {
        return "array";
    }

    public  function value(): array|false {
        return json_decode($this->data, true);
    }

    public function write(array $data): string {
        return json_encode($data);
    }
}

