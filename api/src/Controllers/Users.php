<?php

namespace Controllers;

class Users
{


    public function __construct(private \Services\User $userService) {}

    public function processRequest(string $method, string $request): void
    {

        switch ($request) {
            case "signin":
                $this->signin($method);
                break;
            case "signup":
                $this->signup($method);
                break;
            default:
                http_response_code(403);
                echo json_encode(['error' => "unknown request"], true);
        }
    }

    public function signin(string $method): void
    {

        switch ($method) {
            case "POST":
                $payload = (array) json_decode(file_get_contents('php://input'));
                $filter = $this->signin_filter($payload);

                if (count($filter) > 0) {
                    http_response_code(406);
                    echo json_encode($filter, JSON_PRETTY_PRINT);
                    break;
                }

                $response = $this->userService->login($payload['email'], $payload['password']);

                if(!empty($response['error'])){
                    http_response_code(422);
                    echo json_encode($response, JSON_PRETTY_PRINT);
                    break;
                }

                echo json_encode($response, JSON_PRETTY_PRINT);
                break;


            default:
                http_response_code(405);

                header("Allow: POST");
        }

    }

    public function signup(string $method): void
    {

        switch ($method) {
            case "POST":

                $payload = (array) json_decode(file_get_contents('php://input'));
                $filter = $this->signup_filter($payload);

                if (count($filter) > 0) {
                    http_response_code(406);
                    echo json_encode($filter, JSON_PRETTY_PRINT);
                    break;
                }

                $response = $this->userService->createAccount($payload['name'], $payload['email'], $payload['password']);

                if(!empty($response['error'])){
                    http_response_code(422);
                    echo json_encode($response, JSON_PRETTY_PRINT);
                    break;
                }

                echo json_encode($response, JSON_PRETTY_PRINT);
                break;


            default:
                http_response_code(405);

                header("Allow: POST");
        }
    }

    function  signup_filter(array $userData): array
    {

        $error = [];

        if (empty($userData['email'])) {
            $error[] = 'email is required';
        }

        if (array_key_exists('email', $userData) && filter_var($userData['email'], FILTER_VALIDATE_EMAIL) === false) {
            $error[] = 'not a valid email';
        }

        if (empty($userData['name'])) {
            $error[] = "name is required";
        }

        if (empty($userData["password"])) {
            $error[] = "password is required";
        }

        if (!empty($userData["password"])  && strlen($userData["password"]) < 8){
            $error[] = "password must be at least 8 characters";
        }

        if(!empty($userData["password"]) && !preg_match('/[A-Z]/', $userData['password'])){
            $error[] = "password must have at least one uppercase letter";
        }

        if(!empty($userData["password"]) && !preg_match('/[a-z]/', $userData['password'])){
            $error[] = "password must have at least one Lowercase letter";
        }

        if(!empty($userData["password"]) && !preg_match('/[0-9]/', $userData['password'])){
            $error[] = "password must contain at least one number";
        }

        if(!empty($userData["password"]) && !preg_match('/[\W]/', $userData['password'])){
            $error[] = "password must contain at least one Special Character";
        }

        return $error;
    }

    function  signin_filter(array $userData): array
    {

        $error = [];

        if (empty($userData['email'])) {
            $error[] = 'email is required';
        }

        if (array_key_exists('email', $userData) && filter_var($userData['email'], FILTER_VALIDATE_EMAIL) === false) {
            $error[] = 'not a valid email';
        }

        if (empty($userData["password"])) {
            $error[] = "password is required";
        }

        return $error;
    }
}
