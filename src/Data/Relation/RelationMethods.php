<?php

namespace FlamePHPDev\FlameQuery\Data\Relation;

use FlamePHPDev\FlameQuery\Interfaces\IModel;

trait RelationMethods {
    public function belongsTo(IModel $model, $baseField = '@auto', $relatedField = 'id'): Relation {
        if($baseField == '@auto') $baseField = $model->getTable() . '_id';
        return new Relation($this, $model, $baseField, $relatedField);
    }

    public function hasOne(IModel $model, $relatedField = '@auto', $baseField = 'id'): Relation {
        if($relatedField == '@auto') $relatedField = $this->getTable() . '_id';
        return new Relation($this, $model, $baseField, $relatedField);
    }

    public function hasMany(IModel $model, $baseField = '@auto', $relatedField = 'id'): Relation {
        if($baseField == '@auto') $baseField = $model->getTable() . '_id';
        return new Relation($this, $model, $relatedField, $baseField, true);
    }

    public function belongToMany(IModel $model, $baseField = '@auto', $relatedField = 'id'): Relation {
        if($baseField == '@auto') $baseField = $model->getTable() . '_id';
        return new Relation($this, $model, $relatedField, $baseField, true);
    }
}