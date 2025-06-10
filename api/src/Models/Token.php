<?php

namespace Models;

class Token
{
    private $conn;
    function __construct(private \Database $database)
    {
        $this->conn = $this->database->getConnection();
    }
     
    function updateToken(string $user_id, string $accessToken, string $refreshToken): array
    {

        $query = "UPDATE user_token SET refresh_token= :refresh_token, access_token = :access_token WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(":refreshToken", $refreshToken, \PDO::PARAM_STR);
        $stmt->bindValue(":accessToken", $accessToken, \PDO::PARAM_STR);
        $stmt->bindValue(":user_id", $user_id, \PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return ['message' => "success"];
        }


        return ['error' => "An unknown error occured in the database"];
    }

    function storeToken() {}

    function getToken(): string
    {

        return "";
    }
}
$db = new \Database("localhost","root","","todo_list");
$test = new Token($db);
$test->updateToken(1,"hello","eorld");