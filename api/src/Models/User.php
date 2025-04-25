<?php
namespace Models;

class User{
    private string $conn; 

    function __construct(\Database $conn)
    {
        $this->conn = $conn->getConnection();
    }

}