<?php

use Controllers\ExpenseController;
use Models\Expense;
use PHPUnit\Framework\TestCase;
use Services\ExpenseService;

class ExpenseControllerTest extends TestCase
{
    private $mockService;
    private $controller;

    protected function setUp(): void
    {
        // Mock the ExpenseService
        $this->mockService = $this->createMock(ExpenseService::class);

        // Mock the parent Controller's methods (like checkForJwt, respond, etc.)
        $this->controller = $this->getMockBuilder(ExpenseController::class)
            ->setConstructorArgs([$this->mockService])
            ->onlyMethods(['checkForJwt', 'respond', 'respondWithError', 'createObjectFromPostedJson'])
            ->getMock();
    }

    public function testGetAllExpenses()
    {
        // Mock checkForJwt to return a dummy JWT object
        $jwt = (object) ['data' => (object) ['id' => 1]];
        $this->controller->expects($this->once())
            ->method('checkForJwt')
            ->willReturn($jwt);

        // Mock the service method getAllExpenses
        $expectedExpenses = [
            ['id' => 1, 'title' => 'Expense 1', 'amount' => 100],
            ['id' => 2, 'title' => 'Expense 2', 'amount' => 200],
        ];

        $this->mockService->expects($this->once())
            ->method('getAllExpenses')
            ->with($jwt->data->id, null, null)
            ->willReturn($expectedExpenses);

        // Mock respond to capture the response
        $this->controller->expects($this->once())
            ->method('respond')
            ->with($expectedExpenses);

        // Call the method
        $this->controller->getAllExpenses();

        // Assertions are already covered by the mock expectations
    }
}
