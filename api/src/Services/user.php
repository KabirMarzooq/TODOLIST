<?php
namespace Services;

class User{
    private \Models\User $userModel;
    private \JwtHandler $jwtHandler;

    function __construct(\Models\User $userModel, \JwtHandler $jwtHandler)
    {
        $this->userModel = $userModel;
        $this->jwtHandler = $jwtHandler;
    }

    public function login(string $method, array $payload): array{
        
        return ['error' => 'Failed to Login'];

    }

    public function createAccount(string $name, string $email, string $password): array{

        $password = password_hash($password, PASSWORD_DEFAULT);

        $verifyEmail = $this->userModel->verifyEmail($email);
        if(!empty($verifyEmail['error'])){
            return $verifyEmail;
        }

        $createAccount = $this->userModel->createAccount($name, $email, $password);
        if(!empty($createAccount['error'])){
            
            return $createAccount;

        }

        $tokenization = $this->jwtAuthCreate($createAccount["user_id"]);

        return ["access_token" => $tokenization, 'name' => $createAccount['name'], 'email' => $createAccount['email']];

    }

    public function jwtAuthCreate(string $userId) : string | array{
        $token = $this->jwtHandler->generateAccessToken($userId);

        if($token){

            return $token;

        }

        return ['error'=> "An error occured generating the Authentication"];
    }

}