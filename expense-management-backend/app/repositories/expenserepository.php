<?php

namespace Repositories;

use Models\Category;
use Models\Expense;
use PDO;
use PDOException;

class ExpenseRepository extends Repository
{
    public function searchExpenses($userId, $startDate, $endDate, $categoryId = null, $offset = null, $limit = null)
    {
        try {
            $query = "SELECT expense.*, category.name AS category_name 
                  FROM expense 
                  INNER JOIN category ON expense.category_id = category.id 
                  WHERE expense.user_id = :userId 
                  AND expense.created_at BETWEEN :startDate AND :endDate";

            $params = [
                ':userId' => $userId,
                ':startDate' => $startDate,
                ':endDate' => $endDate,
            ];

            if ($categoryId !== null) {
                $query .= " AND expense.category_id = :categoryId";
                $params[':categoryId'] = $categoryId;
            }

            if ($offset !== null && $limit !== null) {
                $query .= " LIMIT :limit OFFSET :offset";
                $params[':limit'] = $limit;
                $params[':offset'] = $offset;
            }

            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);

            $expenses = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $expenses[] = $this->rowToExpense($row);
            }

            return $expenses;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getAll($userId, $offset = null, $limit = null)
    {
        try {
            $query = "SELECT expense.*, category.name as category_name, user.username as username
                      FROM expense 
                      INNER JOIN category ON expense.category_id = category.id 
                      JOIN user ON expense.user_id = user.id 
                      WHERE expense.user_id = :userId";
            if (isset($limit) && isset($offset)) {
                $query .= " LIMIT :limit OFFSET :offset ";
            }
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            if (isset($limit) && isset($offset)) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();

            $expenses = array();
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $expenses[] = $this->rowToExpense($row);
            }

            return $expenses;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getAllByCategoryId($categoryId, $offset = null, $limit = null)
    {
        try {
            $query = "SELECT expense.*, category.name as category_name, user.username as username 
                      FROM expense 
                      INNER JOIN category ON expense.category_id = category.id 
                      JOIN user ON expense.user_id = user.id 
                      WHERE expense.category_id = :categoryId";

            if (isset($limit) && isset($offset)) {
                $query .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
            if (isset($limit) && isset($offset)) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();

            $expenses = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $expenses[] = $this->rowToExpense($row);
            }

            return $expenses;
        } catch (PDOException $e) {
            echo $e;
            return false;
        }
    }

    public function getOne($expenseId, $userId)
    {
        try {
            $query = "SELECT expense.*, category.name as category_name, user.username as username 
                      FROM expense 
                      INNER JOIN category ON expense.category_id = category.id
                      JOIN user ON expense.user_id = user.id 
                      WHERE expense.id = :expenseId AND expense.user_id = :userId";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':expenseId', $expenseId, PDO::PARAM_INT);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $this->rowToExpense($row);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    private function rowToExpense($row)
    {
        if (!$row) {
            return null;
        }

        $expense = new Expense();
        $expense->id = $row['id'];
        $expense->title = $row['title'];
        $expense->amount = $row['amount'];
        $expense->category_name = $row['category_name'];
        $expense->category_id = $row['category_id'];
        $expense->username = $row['username'];
        $expense->user_id = $row['user_id'];
        $expense->created_at = $row['created_at'];

        return $expense;
    }

    public function create(Expense $expense)
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO expense (title, amount, category_id, user_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$expense->title, $expense->amount, $expense->category_id, $expense->user_id]);

            $expense->id = $this->connection->lastInsertId();

            return $this->getOne($expense->id, $expense->user_id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function update(Expense $expense)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE expense SET title = ?, amount = ?, category_id = ? WHERE id = ?");
            $stmt->execute([$expense->title, $expense->amount, $expense->category_id, $expense->id]);

            return $this->getOne($expense->id, $expense->user_id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function delete($expenseId, $userId)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM expense WHERE id = :expenseId AND user_id = :userId");
            $stmt->bindParam(':expenseId', $expenseId, PDO::PARAM_INT);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e;
        }
        return false;
    }
}
