<?php

namespace OrderProcessorCore\Tests\App\UseCases\Order;

use OrderProcessorCore\App\UseCases\Order\CreateOrderUseCase;
use OrderProcessorCore\Domain\Entities\OrderEntity;
use OrderProcessorCore\Domain\Interfaces\IOrderRepository;
use PHPUnit\Framework\TestCase;

class CreateOrderUseCaseTests extends TestCase
{
    public function test_handle_calls_is_valid_and_sets_order()
    {
        // Arrange
        $order = $this->createMock(OrderEntity::class);
        $order->expects($this->once())
              ->method('isValid')
              ->willReturn(true);

        $repository = $this->createMock(IOrderRepository::class);
        $repository->expects($this->once())
                   ->method('setOrder')
                   ->with($order)
                   ->willReturn(true);

        $useCase = new CreateOrderUseCase($repository);

        // Act
        $result = $useCase->handle($order);

        // Assert
        $this->assertTrue($result);
    }

    public function test_handle_returns_false_when_repository_fails()
    {
        $order = $this->createMock(OrderEntity::class);
        $order->expects($this->once())
              ->method('isValid')
              ->willReturn(true);

        $repository = $this->createMock(IOrderRepository::class);
        $repository->expects($this->once())
                   ->method('setOrder')
                   ->willReturn(false);

        $useCase = new CreateOrderUseCase($repository);

        $result = $useCase->handle($order);

        $this->assertFalse($result);
    }

    public function test_handle_throws_exception_when_order_is_invalid()
    {
        $this->expectException(\InvalidArgumentException::class);

        $order = $this->createMock(OrderEntity::class);
        $order->expects($this->once())
              ->method('isValid')
              ->willThrowException(new \InvalidArgumentException("Invalid order"));

        $repository = $this->createMock(IOrderRepository::class);
        $repository->expects($this->never())
                   ->method('setOrder');

        $useCase = new CreateOrderUseCase($repository);
        $useCase->handle($order);
    }
}
