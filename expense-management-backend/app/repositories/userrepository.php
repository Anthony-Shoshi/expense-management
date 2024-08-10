<?php

namespace Repositories;

use Models\User;
use PDO;
use PDOException;
use Repositories\Repository;

class UserRepository extends Repository
{
    function checkUsernamePassword($username, $password)
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, username, password, email, user_role FROM user WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
            $user = $stmt->fetch();

            if ($user && $this->verifyPassword($password, $user->password)) {
                return $user;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo $e;
            return false;
        }
    }

    function registerUser(User $user)
    {
        try {
            $hashedPassword = $this->hashPassword($user->password);

            $stmt = $this->connection->prepare("INSERT INTO user (username, password, email, user_role) VALUES (:username, :password, :email, :user_role)");
            $stmt->bindParam(':username', $user->username);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':email', $user->email);
            $stmt->bindParam(':user_role', $user->user_role);
            $stmt->execute();

            $user->id = $this->connection->lastInsertId();

            return $user;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function checkUsernameExists($username)
    {
        try {
            $stmt = $this->connection->prepare("SELECT COUNT(*) FROM user WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            return $count > 0;
        } catch (PDOException $e) {
            echo $e;
            return false;
        }
    }

    // hash the password (currently uses bcrypt)
    function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // verify the password hash
    function verifyPassword($input, $hash)
    {
        return password_verify($input, $hash);
    }

    public function getAllUsers($userId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, username, email, user_role FROM user WHERE id != :userId");
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Models\User');
        } catch (PDOException $e) {
            echo $e;
            return false;
        }
    }
}
