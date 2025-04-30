<?php
namespace Models;

class User{
    private $conn; 

    function __construct(\Database $conn)
    {
        $this->conn = $conn->getConnection();
    }



    public function createAccount(string $name, string $email, string $password): array {
        $userId = uniqid("user_");
        $sql = "INSERT INTO user (user_id, name, email, password) VALUES (:user_id, :name, :email, :password)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":user_id", $userId, \PDO::PARAM_STR);
        $stmt->bindValue(":name", $name, \PDO::PARAM_STR);
        $stmt->bindValue(":email", $email, \PDO::PARAM_STR);
        $stmt->bindValue(":password", $password, \PDO::PARAM_STR);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            return ['user_id' => $userId, 'name' => $name, 'email' => $email];
        }

        return ['error' => 'Failed to create account'];

    }

    public function verifyEmail(string $email): array{

        $sql = "SELECT * FROM user WHERE email =  :email";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":email", $email, \PDO::PARAM_STR);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            return ['error' => 'This Email already exists'];
        }

        return ['message' => 'Email does not exist'];
    }

}
