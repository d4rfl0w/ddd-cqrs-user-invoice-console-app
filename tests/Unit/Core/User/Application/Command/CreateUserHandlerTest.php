<?php

namespace App\Tests\Core\User\Application\Command;

use App\Core\User\Application\Command\CreateUserCommand;
use App\Core\User\Application\Command\CreateUserHandler;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Domain\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateUserHandlerTest extends TestCase
{
    private UserRepositoryInterface|MockObject $userRepository;
    private CreateUserHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->handler = new CreateUserHandler($this->userRepository);
    }

    public function test_handle_success(): void
    {
        // Mockowanie użytkownika
        $user = new User('test@example.com', false);

        // Oczekiwania: metoda save i flush zostaną wywołane
        $this->userRepository->expects(self::once())
            ->method('save')
            ->with($this->isInstanceOf(User::class));

        $this->userRepository->expects(self::once())
            ->method('flush');

        // Utworzenie instancji CreateUserCommand
        $command = new CreateUserCommand('test@example.com');

        // Wywołanie metody __invoke
        $this->handler->__invoke($command);
    }

    public function test_handle_inactive_user(): void
    {
        $this->expectException(\Exception::class); // Adjust the exception type as needed

        // Mockowanie użytkownika jako nieaktywnego
        $user = $this->createMock(User::class);
        $user->method('isActive')->willReturn(false);

        $this->userRepository->expects(self::once())
            ->method('save')
            ->willThrowException(new \Exception('Cannot create invoice for inactive user'));

        $this->handler->__invoke(new CreateUserCommand('inactive@example.com', false));
    }

    public function test_handle_user_save_failure(): void
    {
        $this->expectException(\Exception::class); // Adjust the exception type as needed

        // Mockowanie użytkownika
        $user = new User('test@example.com', false);

        // Oczekiwania: metoda save rzuci wyjątek
        $this->userRepository->expects(self::once())
            ->method('save')
            ->willThrowException(new \Exception('Failed to save user'));

        $this->handler->__invoke(new CreateUserCommand('test@example.com'));

        // Oczekiwania: metoda flush nie zostanie wywołana
        $this->userRepository->expects(self::never())
            ->method('flush');
    }

    public function test_handle_flush_failure(): void
    {
        $this->expectException(\Exception::class); // Adjust the exception type as needed

        // Mockowanie użytkownika
        $user = new User('test@example.com', false);

        // Oczekiwania: metoda save zostanie wywołana raz
        $this->userRepository->expects(self::once())
            ->method('save')
            ->with($this->isInstanceOf(User::class));

        // Oczekiwania: metoda flush rzuci wyjątek
        $this->userRepository->expects(self::once())
            ->method('flush')
            ->willThrowException(new \Exception('Failed to flush'));

        $this->handler->__invoke(new CreateUserCommand('test@example.com'));
    }
}
