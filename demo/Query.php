<?php

use FlamePHPDev\FlameQuery\Database\Database;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/User.php';
require_once __DIR__. '/Todo.php';

new Database('127.0.0.1', 3306, 'flame_orm', 'root', '', true);

$userTodos = User::select(['username', 'email'])
    ->select(['id', 'task'], new Todo)
    ->dry()
    ->with(User::todos())
    ->whereWith(User::todos(), "is_finished", 0, "=")
    ->get();

$todosUser = Todo::select(['id', 'task'])
    ->dry()
    ->with(Todo::user())
    ->get();


var_dump($userTodos, $todosUser);
