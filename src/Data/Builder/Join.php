<?php

namespace FlamePHPDev\FlameQuery\Data\Builder;

class Join {
    public function __construct(protected array $relations, protected string $base) {}

    public function generate(): string {
        $first = true;
        $query = '';
        foreach($this->relations as $related => $info) {
            if(!$first && !str_ends_with($query, ' ')) $query .= ' ';
            else $first = false;
            [$baseField, $relatedField, $type] = $info;
            $query .= $type->value . ' join `' . $related . '`';
            $query .= ' on `' . $this->base . '`.`' . $baseField . '` = `' . $related . '`.`' . $relatedField . '`';
        }
        return $query;
    }
}