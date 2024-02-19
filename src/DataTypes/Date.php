<?php

namespace FlamePHPDev\FlameQuery\DataTypes;

use FlamePHPDev\FlameQuery\Interfaces\IDataType;

readonly class Date implements IDataType {
    public function __construct(private string $data) {}
    public function getType(): string {
        return "string";
    }

    public  function value(string $format = 'Y-m-d H:i:s'): string {
        return date($format, $this->data);
    }

    public function write($data): string {
        return $data;
    }
}