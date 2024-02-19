<?php

namespace FlamePHPDev\FlameQuery\Data;

use Exception;
use FlamePHPDev\FlameQuery\Data\Builder\Builder;
use FlamePHPDev\FlameQuery\Data\Relation\RelationMethods;
use FlamePHPDev\FlameQuery\Database\Database;
use FlamePHPDev\FlameQuery\Interfaces\IBuilder;
use FlamePHPDev\FlameQuery\Interfaces\IModel;

abstract class Model extends BaseModel implements IModel {
    use RelationMethods;
    protected ?string $table = null;
    private readonly array $realFields;
    protected array $hidden = [];
    protected array $__fieldSet = [];
    public array $fields;


    /**
     * @throws Exception
     */
    public static function builder(): IBuilder {
        $model = new static();
        $model->realFields = array_keys(
            $model->bootUp($model->table ?: ':unknown:', Database::$global)
        );
        return new Builder($model);
    }

    protected function make(array $dataset): void {
        foreach($dataset as $key => $value) {
            $this->__fieldSet[$key] = $value;
            if(!in_array($key, $this->hidden)) {
                $this->{$key} = $value;
                $this->fields[$key] = $value;
            }
        }
    }

    /**
     * @throws Exception
     */
    public static function select(array|string $fields = ['*']): IBuilder {
        return static::builder()->select($fields);
    }

    public static function delete(?int $id): bool {
        return false;
    }

    public static function update(array $data): bool {
        return false;
    }

    public static function insert(array $data): bool {
        return false;
    }

    public function getRealFields(): array {
        return $this->realFields;
    }

    public function getTable(): string {
        return $this->table;
    }
}