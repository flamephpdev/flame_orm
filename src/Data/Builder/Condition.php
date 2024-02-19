<?php

namespace FlamePHPDev\FlameQuery\Data\Builder;

class Condition {
    public function __construct(protected array $conditions) {}

    public function generate(): array {
        $binding = [];
        $query = '';
        $firstCond = true;
        if(!empty($this->conditions)) $query .= 'where';
        foreach($this->conditions as $i => $condition) {
            [$field, $value, $operator, $table, $nextJoin] = $condition;
            if(!$firstCond) $query .= ' ' . $nextJoin;
            else $firstCond = false;
            if(!str_ends_with($query, ' ')) $query .= ' ';
            [$val, $bind] = $this->generateValue($value);
            if(is_array($bind)) $binding = array_merge($binding, $bind);
            else $binding[] = $bind;
            $query .= '`' .  $table . '`.`' . $field . '` ' . $operator . ' ' . $val;
        }
        return [$query, $binding];
    }

    protected function generateValue(mixed $value): array|string
    {
        $type = gettype($value);
        switch($type) {
            case 'boolean': {
                return ['?', intval($value)];
            }
            case 'array': {
                $binding = [];
                $re = '(';
                foreach($value as $i => $v) {
                    $gen = $this->generateValue($v);
                    $re .= $gen[0];
                    $binding[] = $gen[1];
                    if($i != array_key_last($value)) $re .= ', ';
                }
                return [$re . ')', $binding];
            }
            default: {
                return ['?', $value];
            }
        }
    }
}