<?php 

class User {
    private $table;

    public function __construct() {
        $this->table = 'users';
    }

    
    public function fetch($id = null) {
        $table = $this->table;
        
        $db = new Database();

        $con = $db->dbconnection();

        if ($id != null) {
            $query = "SELECT * FROM $table WHERE id = :zid";
            $sql = $con->prepare($query);
            $sql->bindParam(':zid', $id);
            $sql->execute();
            return $sql->fetch(PDO::FETCH_ASSOC);
        }

        $query = "SELECT * FROM $table";
        $sql = $con->prepare($query);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}