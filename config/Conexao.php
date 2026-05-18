<?php

class Conexao {
    private static $instancia;

    public static function getConn(): \PDO {
        if (!isset(self::$instancia)) {
            self::$instancia = new \PDO('pgsql:host=localhost;dbname=petshop', 'admin', 'root');
            self::$instancia->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        
        return self::$instancia;
    }
}