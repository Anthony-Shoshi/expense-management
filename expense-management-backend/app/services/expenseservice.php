<?php

namespace Services;

use Models\Expense;
use Repositories\ExpenseRepository;

class ExpenseService
{

    private $repository;

    public function __construct()
    {
        $this->repository = new ExpenseRepository();
    }

    public function searchExpenses($userId, $startDate, $endDate, $categoryId = null, $offset = null, $limit = null)
    {
        return $this->repository->searchExpenses($userId, $startDate, $endDate, $categoryId = null, $offset = null, $limit = null);
    }
    
    public function getAllExpenses($userId, $offset = null, $limit = null)
    {
        return $this->repository->getAll($userId, $offset, $limit);
    }

    public function getExpense($expenseId, $userId)
    {
        return $this->repository->getOne($expenseId, $userId);
    }

    public function createExpense(Expense $expense)
    {
        if ($expense->amount < 0) {
            throw new \Exception("Expense amount must be non-negative.");
        }

        return $this->repository->create($expense);
    }

    public function updateExpense(Expense $expense)
    {
        $existingExpense = $this->repository->getOne($expense->id, $expense->user_id);
        if (!$existingExpense) {
            throw new \Exception("Expense not found or does not belong to the user.");
        }

        return $this->repository->update($expense);
    }

    public function deleteExpense($expenseId, $userId)
    {
        $existingExpense = $this->repository->getOne($expenseId, $userId);
        if (!$existingExpense) {
            throw new \Exception("Expense not found or does not belong to the user.");
        }

        return $this->repository->delete($expenseId, $userId);
    }

    public function getAllByCategoryId($categoryId, $offset = null, $limit = null)
    {
        return $this->repository->getAllByCategoryId($categoryId, $offset, $limit);
    }

    public function getAllByUserId($userId, $offset = null, $limit = null)
    {
        return $this->repository->getAll($userId, $offset, $limit);
    }
}
