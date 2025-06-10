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

    public function login(string $email, string $password): array{

        $user = $this->userModel->login($email, $password);

        if(!empty($user['error'])){
            return $user;
        }

        if ($this->passwordVerify(password: $password, hash:$user['password'] ) === false){
            return ['error' => "Invalid Password"];
        }

        //UPDATE TOKEN IN DATABASE

         return ["access_token" => $this->jwtAuthCreate($user['user_id']), 'name' => $user['name'], 'email' => $user['email']];

         return ['error' => 'Failed to Login'];

    }

    public function passwordVerify(string $password,string $hash): bool{
        return password_verify($password, $hash);
    }

    public function createAccount(string $name, string $email, string $password): array{

        $password = password_hash($password, PASSWORD_DEFAULT);

       if ($this->userModel->getUserEmail($email) === true) {
            return ['error' => 'Email already exists'];
       }

        $createAccount = $this->userModel->createAccount($name, $email, $password);
        if(!empty($createAccount['error'])){
            
            return $createAccount;

        }

        //CREATE TOKEN IN DATABASE

        $tokenization = $this->jwtAuthCreate($createAccount["user_id"]);

        return ["access_token" => $tokenization, 'name' => $createAccount['name'], 'email' => $createAccount['email']];

    }

    public function jwtAuthCreate(string $userId) : string | array{
        $token = $this->jwtHandler->generateAccessToken($userId);
        //Insert the jwt tokens in the database

        if($token){

            return $token;

        }

        return ['error'=> "An error occured generating the Authentication"];
    }

}