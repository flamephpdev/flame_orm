<?php

namespace FlamePHPDev\FlameQuery\Data;

use FlamePHPDev\FlameQuery\DataTypes\Date;
use FlamePHPDev\FlameQuery\DataTypes\JSON;
use FlamePHPDev\FlameQuery\Utils;

class TypeTranslator {
    public function __construct(protected string $var) {}

    public function make(): array {
        $e = explode('(', $this->var);
        $type = match(strtolower($e[0])) {
            'int', 'tinyint', 'smallint', 'mediumint', 'bigint', 'serial', 'bit' => 'int',
            'decimal', 'float', 'double', 'real' => 'float',
            'boolean' => 'bool',
            'date', 'datetime', 'timestamp', 'time', 'year' => Date::class,
            'json' => JSON::class,
            default => 'string',
        };
        $len = intval(Utils::string_between($this->var, '(', ')'));
        return [$type, $len];
    }
}