<?php

namespace FlamePHPDev\FlameQuery\Data\Mapper;

use FlamePHPDev\FlameQuery\Database\Database;
use PDO;

class Query {
    public function __construct(
        protected string $query,
        protected array $binding,
    ) {}

    public function fetch(): false|array {
        $pdo = Database::$global->getConnection();
        $statement = $pdo->prepare($this->query);
        foreach($this->binding as $i => $bind) $statement->bindValue($i+1, $bind);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_NUM);
    }
}