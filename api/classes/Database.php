<?php

class Database extends PDO {
    private $db;
    private $user;
    private $pass;
    private $option;

    public function __construct() {
        $this->db = 'mysql:host=localhost;dbname=university';
        $this->user = 'root';
        $this->pass = 'root';
        $this->option = array(
            $this::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        );
    }

    public function dbconnection() {
        try {
            $con = new PDO($this->db, $this->user, $this->pass, $this->option);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $con;
        }
        catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}