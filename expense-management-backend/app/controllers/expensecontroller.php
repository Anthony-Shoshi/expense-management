<?php

namespace Controllers;

use Exception;
use Models\Expense;
use Services\ExpenseService;

class ExpenseController extends Controller
{

    private $service;

    public function __construct(ExpenseService $service = null)
    {
        $this->service = $service ?: new ExpenseService();
    }

    public function searchExpenses()
    {
        $jwt = $this->checkForJwt();
        if (!$jwt) {
            $this->respondWithError(401, "Unauthorized: Missing or invalid JWT token");
            return;
        }

        $userId = $jwt->data->id;

        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        $categoryId = $_GET['category_id'] ?? null;
        $offset = $_GET['offset'] ?? null;
        $limit = $_GET['limit'] ?? null;

        if (!$startDate || !$endDate || !strtotime($startDate) || !strtotime($endDate) || ($categoryId !== null && !is_numeric($categoryId)) || ($offset !== null && !is_numeric($offset)) || ($limit !== null && !is_numeric($limit))) {
            $this->respondWithError(400, "Invalid input parameters");
            return;
        }

        try {
            $expenses = $this->service->searchExpenses($userId, $startDate, $endDate, $categoryId, $offset, $limit);
            $this->respond($expenses);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function getAllExpenses()
    {
        $jwt = $this->checkForJwt();
        if (!$jwt) {
            $this->respondWithError(401, "Unauthorized: Missing or invalid JWT token");
            return;
        }

        $userId = $jwt->data->id;

        $offset = isset($_GET['offset']) ? $_GET['offset'] : null;
        $limit = isset($_GET['limit']) ? $_GET['limit'] : null;

        try {
            $expenses = $this->service->getAllExpenses($userId, $offset, $limit);
            $this->respond($expenses);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function getExpense($id)
    {
        $jwt = $this->checkForJwt();
        if (!$jwt) {
            $this->respondWithError(401, "Unauthorized: Missing or invalid JWT token");
            return;
        }

        $userId = $jwt->data->id;

        try {
            $expense = $this->service->getExpense($id, $userId);
            if (!$expense) {
                $this->respondWithError(404, "Expense not found or does not belong to the user.");
                return;
            }
            $this->respond($expense);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function createExpense()
    {
        $jwt = $this->checkForJwt();
        if (!$jwt) {
            $this->respondWithError(401, "Unauthorized: Missing or invalid JWT token");
            return;
        }

        $userId = $jwt->data->id;

        $expenseData = $this->validateExpenseData();
        if (!$expenseData['success']) {
            $this->respondWithError(400, $expenseData['message']);
            return;
        }

        try {
            $expenseData = $this->createObjectFromPostedJson("Models\\Expense");
            $expenseData->user_id = $userId;

            $expense = $this->service->createExpense($expenseData);
            $this->respond($expense, 201);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function updateExpense($id)
    {
        $jwt = $this->checkForJwt();
        if (!$jwt) {
            $this->respondWithError(401, "Unauthorized: Missing or invalid JWT token");
            return;
        }

        $userId = $jwt->data->id;

        $expenseData = $this->validateExpenseData();
        if (!$expenseData['success']) {
            $this->respondWithError(400, $expenseData['message']);
            return;
        }

        try {
            $expenseData = $this->createObjectFromPostedJson("Models\\Expense");
            $expenseData->id = $id;
            $expenseData->user_id = $userId;

            $expense = $this->service->updateExpense($expenseData);
            $this->respond($expense);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function deleteExpense($id)
    {
        $jwt = $this->checkForJwt();
        if (!$jwt) {
            $this->respondWithError(401, "Unauthorized: Missing or invalid JWT token");
            return;
        }

        $userId = $jwt->data->id;

        try {
            $success = $this->service->deleteExpense($id, $userId);
            if (!$success) {
                $this->respondWithError(404, "Expense not found or does not belong to the user.");
                return;
            }
            $this->respond(true, 204);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    private function validateExpenseData()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['title'])) {
            return ['success' => false, 'message' => "Title is required."];
        }

        if (empty($data['amount'])) {
            return ['success' => false, 'message' => "Amount is required."];
        }

        $amount = $data['amount'];
        if (!is_numeric($amount)) {
            return ['success' => false, 'message' => "Amount must be a numeric value."];
        }

        if (empty($data['category_id'])) {
            return ['success' => false, 'message' => "category id is required."];
        }

        $title = htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8');
        $category_id = htmlspecialchars($data['category_id'], ENT_QUOTES, 'UTF-8');
        $amount = floatval($data['amount']);

        if ($amount <= 0) {
            return ['success' => false, 'message' => "Amount must be a positive number."];
        }

        return ['success' => true, 'amount' => $amount, 'title' => $title, 'category_id' => $category_id];
    }

    public function getAllByCategoryId($categoryId)
    {
        $jwt = $this->checkForJwt();
        if (!$jwt) {
            $this->respondWithError(401, "Unauthorized: Missing or invalid JWT token");
            return;
        }

        $offset = isset($_GET['offset']) ? $_GET['offset'] : null;
        $limit = isset($_GET['limit']) ? $_GET['limit'] : null;

        try {
            $expenses = $this->service->getAllByCategoryId($categoryId, $offset, $limit);
            $this->respond($expenses);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function getAllByUserId($userId)
    {
        $jwt = $this->checkForJwt();
        if (!$jwt) {
            $this->respondWithError(401, "Unauthorized: Missing or invalid JWT token");
            return;
        }

        $offset = isset($_GET['offset']) ? $_GET['offset'] : null;
        $limit = isset($_GET['limit']) ? $_GET['limit'] : null;

        try {
            $expenses = $this->service->getAllByUserId($userId, $offset, $limit);
            $this->respond($expenses);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }
}
