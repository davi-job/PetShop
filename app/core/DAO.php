<?php

abstract class DAO {
    protected \PDO $conn;
    public function __construct() {
        $this->conn = Conexao::getConn();
    }
}