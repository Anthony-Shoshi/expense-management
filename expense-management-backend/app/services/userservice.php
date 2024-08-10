<?php
namespace Services;

use Models\User;
use Repositories\UserRepository;

class UserService {

    private $repository;

    function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function checkUsernamePassword($username, $password) {
        return $this->repository->checkUsernamePassword($username, $password);
    }

    public function registerUser($username, $password, $email, $user_role = 'user') {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->password = $password;
        $user->user_role = $user_role;
        return $this->repository->registerUser($user);
    }

    public function checkUsernameExists($username) {
        return $this->repository->checkUsernameExists($username);
    }

    public function getAllUsersExcept($userId) {
        return $this->repository->getAllUsers($userId);
    }
}

?>