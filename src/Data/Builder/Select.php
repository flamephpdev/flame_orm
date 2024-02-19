<?php

namespace FlamePHPDev\FlameQuery\Data\Builder;


class Select {
    public function __construct(protected array $selections) {}

    public function generate(): array {
        $arrayFields = [];
        $query = '';
        foreach($this->selections as $i => $selection) {
            [$s, $model] = $selection;
            $table = $model->getTable();
            if($i != 0) $query .= ' ';
            foreach($s as $j => $field) {
                if($j !== 0 && !str_ends_with($query, ' ')) $query .= ' ';
                $query .= $table . '.' . $field;
                if($field == '*') $arrayFields = [...$arrayFields, ...array_map(function($f) use ($model) {
                    return [$f, $model];
                }, $model->getRealFields())];
                else $arrayFields[] = [$field, $model];
                if(array_key_last($s) !== $j) $query .= ',';
            }
            if(array_key_last($this->selections) !== $i) $query .= ',';
        }
        return [$query, $arrayFields];
    }
}