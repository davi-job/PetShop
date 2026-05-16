<?php

class Conexao {
    private static $instancia;

    public static function getConn(): \PDO {
        if (!isset(self::$instancia)) {
            self::$instancia = new \PDO('psql:host=localhost;dbname=petshop', 'admin', 'root');
        }
        
        return self::$instancia;
    }
}