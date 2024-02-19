<?php

namespace FlamePHPDev\FlameQuery\Interfaces;

interface IRelation {
    public function related(): IModel;
}