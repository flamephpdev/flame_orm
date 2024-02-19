<?php

use FlamePHPDev\FlameQuery\Data\Model;
use FlamePHPDev\FlameQuery\Interfaces\IRelation;

class User extends Model {
    protected ?string $table = 'users';

    public static function todos(): IRelation {
        return (new self)->hasMany(new Todo, 'user_id');
    }
}