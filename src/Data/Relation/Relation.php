<?php

namespace FlamePHPDev\FlameQuery\Data\Relation;

use FlamePHPDev\FlameQuery\Interfaces\IBuilder;
use FlamePHPDev\FlameQuery\Interfaces\IModel;
use FlamePHPDev\FlameQuery\Interfaces\IRelation;

readonly class Relation implements IRelation {
    public function __construct(
        public IModel $base,
        public IModel $related,
        public string $baseField,
        public string $relatedField = 'id',
        public bool   $multiQuery = false
    ) {}

    public function related(): IModel {
        return $this->related;
    }
}