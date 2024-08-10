<?php

namespace Controllers;

use Exception;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Services\UserService;

class UserController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new UserService();
    }

    private function generateJwt($user)
    {
        $secretKey = "YOUR_SECRET_KEY";
        $expiration = time() + (60 * 60); // Expiration time: Current time + 1 hour
        $notBefore = time(); // Not before: Current time
        $payload = array(
            "iss" => "ANTHONY",
            "aud" => "USER",
            "iat" => time(),
            "nbf" => $notBefore,
            "exp" => $expiration,
            "data" => array(
                "id" => $user->id,
                "username" => $user->username,
                "email" => $user->email
            )
        );

        $jwt = JWT::encode($payload, $secretKey, "HS256");

        return $jwt;
    }

    public function login()
    {

        $data = $this->createObjectFromPostedJson("Models\\User");

        $user = $this->service->checkUsernamePassword($data->username, $data->password);

        if (!$user) {
            $this->respondWithError(401, "Incorrect credentials!");
            return;
        }

        $token = $this->generateJwt($user);

        $data = array(
            "message" => "Successful login.",
            "jwt" => $token,
            "user" => array(
                "id" => $user->id,
                "username" => $user->username,
                "email" => $user->email,
                "user_role" => $user->user_role
            )
        );

        $this->respond($data);
    }

    public function register()
    {
        try {
            $data = $this->createObjectFromPostedJson("Models\\User");

            if (!isset($data->username) || !isset($data->password) || !isset($data->email)) {
                $this->respondWithError(400, "Missing required fields");
                return;
            }

            $existingUser = $this->service->checkUsernameExists($data->username);
            if ($existingUser) {
                $this->respondWithError(409, "User with username '{$data->username}' already exists");
                return;
            }

            $newUser = $this->service->registerUser($data->username, $data->password, $data->email);

            $token = $this->generateJwt($newUser);

            $responseData = array(
                "message" => "User registered successfully.",
                "jwt" => $token,
                "user" => array(
                    "id" => $newUser->id,
                    "username" => $newUser->username,
                    "email" => $newUser->email,
                    "role" => $newUser->user_role
                )
            );

            $this->respond($responseData);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function getAll()
    {
        $jwt = $this->checkForJwt();
        if (!$jwt) {
            $this->respondWithError(401, "Unauthorized: Missing or invalid JWT token");
            return;
        }

        $loggedInUserId = $jwt->data->id;

        $users = $this->service->getAllUsersExcept($loggedInUserId);

        $this->respond($users);
    }
}
