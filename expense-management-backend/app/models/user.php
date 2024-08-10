<?php
namespace Models;

class User {

    public int $id;
    public string $username;
    public string $password;
    public string $email;
    public string $user_role;

    // public function __construct($username, $password, $email, $user_role = 'user') {
    //     $this->username = $username;
    //     $this->password = $password;
    //     $this->email = $email;
    //     $this->user_role = $user_role;
    // }
}

?>