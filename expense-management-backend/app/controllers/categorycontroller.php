<?php

namespace Controllers;

use Exception;
use Services\CategoryService;

class CategoryController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new CategoryService();
    }

    public function getAll()
    {
        $jwt = $this->checkForJwt();
        if (!$jwt) {
            $this->respondWithError(401, "Unauthorized: Missing or invalid JWT token");
            return;
        }

        $offset = null;
        $limit = null;

        if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
            $offset = $_GET["offset"];
        }
        if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $limit = $_GET["limit"];
        }

        $categories = $this->service->getAll($offset, $limit);

        $this->respond($categories);
    }

    public function getOne($id)
    {
        $jwt = $this->checkForJwt();
        if (!$jwt) {
            $this->respondWithError(401, "Unauthorized: Missing or invalid JWT token");
            return;
        }

        $category = $this->service->getOne($id);

        if (!$category) {
            $this->respondWithError(404, "Category not found");
            return;
        }

        $this->respond($category);
    }

    public function create()
    {
        $jwt = $this->checkForJwt();
        if (!$jwt) {
            $this->respondWithError(401, "Unauthorized: Missing or invalid JWT token");
            return;
        }

        $categoryData = $this->validateCategoryData();
        if (!$categoryData['success']) {
            $this->respondWithError(400, $categoryData['message']);
            return;
        }

        try {
            $category = $this->createObjectFromPostedJson("Models\\Category");
            $this->service->insert($category);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        $this->respond($category);
    }

    public function update($id)
    {
        $jwt = $this->checkForJwt();
        if (!$jwt) {
            $this->respondWithError(401, "Unauthorized: Missing or invalid JWT token");
            return;
        }

        $categoryData = $this->validateCategoryData();
        if (!$categoryData['success']) {
            $this->respondWithError(400, $categoryData['message']);
            return;
        }

        try {
            $category = $this->createObjectFromPostedJson("Models\\Category");
            $this->service->update($category, $id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        $this->respond($category);
    }

    public function delete($id)
    {
        $jwt = $this->checkForJwt();
        if (!$jwt) {
            $this->respondWithError(401, "Unauthorized: Missing or invalid JWT token");
            return;
        }

        try {
            $this->service->delete($id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        $this->respond(true);
    }

    private function validateCategoryData()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['name'])) {
            return ['success' => false, 'message' => "Category name is required."];
        }

        $name = htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8');

        return ['success' => true, 'name' => $name];
    }
}
