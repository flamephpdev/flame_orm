<?php

namespace FlamePHPDev\FlameQuery\Interfaces;

use FlamePHPDev\FlameQuery\Enums\JoinType;

interface IBuilder
{
    public function select(string|array $field, ?IModel $model = null): IBuilder;
    public function where(string $field, mixed $value, string $operator): IBuilder;

    public function orWhere(string $field, mixed $value, string $operator): IBuilder;

    public function whereWith(IRelation|string $relation, string $field, mixed $value, string $operator): IBuilder;

    public function orWhereWith(IRelation|string $relation, string $field, mixed $value, string $operator): IBuilder;

    public function orderBy(array|string $field, $type): IBuilder;

    public function desc(array|string $field): IBuilder;

    public function asc(array|string $field): IBuilder;

    public function limit(int $limit): IBuilder;

    public function offset(int $offset): IBuilder;

    public function first(string $via = 'id', ?IModel $model = null): array|bool;

    public function latest(string $via = 'id', ?IModel $model = null): array|bool;

    public function with(IRelation|array|string $relation, JoinType $type = JoinType::INNER): IBuilder;

    public function dry(): IBuilder;

    public function get(): mixed;
}