<?php

namespace FlamePHPDev\FlameQuery\Interfaces;

interface IModel {
    public static function select(array|string $fields): IBuilder;
    public static function delete(?int $id): bool;
    public static function update(array $data): bool;
    public static function insert(array $data): bool;
    public function getRealFields(): array;
    public function getTable(): string;
}