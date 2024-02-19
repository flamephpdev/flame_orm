<?php

namespace FlamePHPDev\FlameQuery\Data\Builder;

use Exception;
use FlamePHPDev\FlameQuery\Data\Mapper\ObjectMapper;
use FlamePHPDev\FlameQuery\Data\Mapper\Query;
use FlamePHPDev\FlameQuery\Enums\JoinType;
use FlamePHPDev\FlameQuery\Interfaces\IBuilder;
use FlamePHPDev\FlameQuery\Interfaces\IModel;
use FlamePHPDev\FlameQuery\Interfaces\IRelation;

class Builder implements IBuilder {
    protected bool $dryRun = false;

    protected array $fields = [];
    protected array $relations = [];
    protected array $whereConditions = [];
    protected JoinType $joinType = JoinType::INNER;

    protected ?string $orderType = NULL;
    protected array $orderFields = [];
    protected ?int $limit = NULL;
    protected ?int $offset = NULL;

    protected bool $onlyFirst = false;

    public function __construct(protected IModel $model) {}

    public function select(array|string $field = '*', ?IModel $model = NULL): IBuilder {
        if(!is_array($field)) $field = explode(',', $field);
        $this->fields[] = [$field, is_null($model) ? $this->model : $model];
        return $this;
    }

    public function where(string $field, mixed $value, string $operator): IBuilder {
        $this->whereConditions[] = [
            $field,
            $value,
            $operator,
            $this->model->getTable(),
            'and'
        ];
        return $this;
    }

    public function orWhere(string $field, mixed $value, string $operator): IBuilder {
        $this->whereConditions[] = [
            $field,
            $value,
            $operator,
            $this->model->getTable(),
            'or'
        ];
        return $this;
    }

    /**
     * @throws Exception
     */
    public function whereWith(IRelation|string $relation, string $field, mixed $value, string $operator): IBuilder {
        $this->with($relation);
        $this->whereConditions[] = [
            $field,
            $value,
            $operator,
            $relation->related()->getTable(),
            'and'
        ];
        return $this;
    }

    /**
     * @throws Exception
     */
    public function orWhereWith(IRelation|string $relation, string $field, mixed $value, string $operator): IBuilder {
        $this->with($relation);
        $this->whereConditions[] = [
            $field,
            $value,
            $operator,
            $relation->related()->getTable(),
            'or'
        ];
        return $this;
    }

    public function orderBy(array|string $field, $type): IBuilder {
        $this->orderType = $type;
        $this->orderFields = array_merge($this->orderFields, is_array($field) ? $field : explode(',', $field));
        return $this;
    }

    public function desc(array|string $field): IBuilder {
        return $this->orderBy($field, 'desc');
    }

    public function asc(array|string $field): IBuilder {
        return $this->orderBy($field, 'asc');
    }

    public function limit(int $limit): IBuilder {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): IBuilder {
        $this->offset = $offset;
        return $this;
    }

    public function first(string $via = 'id', ?IModel $model = null): array|bool {
        if(is_null($model)) $model = $this->model;
        $this->onlyFirst = true;
        return $this->limit(1)->asc($this->model->getTable() . '.' . $via)->get();
    }

    public function latest(string $via = 'id', ?IModel $model = null): array|bool {
        if(is_null($model)) $model = $this->model;
        $this->onlyFirst = true;
        return $this->limit(1)->desc($this->model->getTable() . '.' . $via)->get();
    }

    /**
     * @throws Exception
     */
    public function with(array|string|IRelation $relation, JoinType $type = JoinType::INNER): IBuilder
    {
        $_iterator = [];
        if(is_array($relation)) $_iterator = $relation;
        else $_iterator[] = $relation;
        foreach($_iterator as $rel) {
            if(is_string($rel)) {
                try {
                    $rel = $this->model->{$rel}();
                } catch(Exception) {
                    throw new Exception('Invalid relation method: ' . $rel);
                }
            }
            if(!in_array(($table = $rel->related->getTable()), array_keys($this->relations))) {
                $this->relations[$table] = [
                    $rel->baseField,
                    $rel->relatedField,
                    $type,
                ];
            }
        }
        return $this;
    }

    public function dry(): IBuilder {
        $this->dryRun = true;
        return $this;
    }

    public function get(): mixed {
        $bindings = [];
        $baseTable = $this->model->getTable();

        [$select, $genFields] = (new Select($this->fields))->generate();
        $query = 'select ' . $select . ' from ' . $baseTable . ' ';
        $query .= trim((new Join(
                relations: $this->relations,
                base: $baseTable,
            ))->generate()) . ' ';

        [$conditions, $condBind] = (new Condition($this->whereConditions))->generate();
        $query .= trim($conditions);
        $bindings = array_merge($bindings, $condBind);

        if(!is_null($this->orderType)) $query .= ' order by ' . implode(', ', $this->orderFields) . ' ' . $this->orderType;
        if(gettype($this->limit) == 'integer') $query .= ' limit ' . $this->limit;
        if(gettype($this->offset) == 'integer') $query .= ' offset ' . $this->limit;
        $query = preg_replace('/\s+/', ' ', $query);


        if($this->dryRun) return ['query' => $query, 'binding' => $bindings];

        $exec = (new Query($query, $bindings))->fetch();
        if(empty($exec)) return false;

        $generated = (new ObjectMapper($genFields, $exec, $this->model))->createMappedObjects();

        return ($this->onlyFirst) ? $generated[0] : $generated;
    }
}