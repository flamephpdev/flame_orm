<?php

use FlamePHPDev\FlameQuery\Data\Model;
use FlamePHPDev\FlameQuery\Interfaces\IRelation;

class Todo extends Model {
    protected ?string $table = 'todos';

    public static function user(): IRelation {
        return (new self)->belongsTo(new User, 'user_id');
    }
}