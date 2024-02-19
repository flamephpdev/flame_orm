<?php

namespace FlamePHPDev\FlameQuery\Data;

use Exception;
use FlamePHPDev\FlameQuery\Database\Database;
use FlamePHPDev\FlameQuery\Enums\ConfigDataCoreFields;

abstract class BaseModel {
    protected array $__config_data_core__orm__ = [];

    /**
     * @throws Exception
     */
    protected function bootUp(string $table, Database $db): array {
        $key = ConfigDataCoreFields::MySQLExportedFields->value;
        if(isset($this->__config_data_core__orm__[$key])) return $this->__config_data_core__orm__[$key];
        try {
            $data = $db->select('explain ' . $table);
        } catch (Exception) {
            throw new Exception('Invalid database table name: ' . $table);
        }
        $fields = [];
        foreach($data as $col) {
            $type = (new TypeTranslator($col['Type']))->make();
            $fields[$col['Field']] = [
                'type' => $type,
                'default' => $col['Default'],
                'is_nullable' => $col['Null'] == 'Yes',
                'key' => $col['Key'] == '' ? NULL : strtolower($col['Key']),
                'extra' => $col['Extra'] == '' ? NULL : $col['Extra'],
            ];
        }
        $this->__config_data_core__orm__[$key] = $fields;
        return $fields;
    }
}