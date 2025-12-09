<?php
require_once('Database.php');

class User {
    private $conn;
    private $table = 'users';

    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }



}