<?php

namespace FlamePHPDev\FlameQuery\Database;

use PDO;

class Database {
    private PDO $connection;
    public static Database $global;

    public function __construct(string $host, int|string $port, string $database, string $user, string $password, $global = false) {
        $this->connection = new PDO("mysql:host=$host;port=$port;dbname=$database", $user, $password);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if($global) {
            self::$global = $this;
        }
    }

    public function getConnection(): PDO {
        return $this->connection;
    }

    public function select($sql_data,array $value_array = NULL,array $return_keys = NULL, $fetch_modes = []): array|false {
        $sql = $this->connection->prepare($sql_data);
        if($value_array){
            foreach($value_array as $i => $value){
                $sql->bindValue($i + 1,$value);
            }
        }
        try {
            $sql->execute();
        } catch(\Exception $e){
            return array("error"=>$e->getMessage());
        }
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        if($return_keys){
            $rdata = $data;
            foreach($return_keys as $i){
                if(isset($rdata[$i])){
                    $rdata = $rdata[$i];
                } else {
                    return false;
                }
            }
            return $rdata;
        }
        return $data;
    }
}