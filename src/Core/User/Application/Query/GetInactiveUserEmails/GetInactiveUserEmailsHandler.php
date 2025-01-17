<?php

namespace App\Core\User\Application\Query\GetInactiveUserEmails;

use App\Core\User\Application\DTO\UserDTO;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetInactiveUserEmailsHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(GetInactiveUserEmailsQuery $query): array
    {
        $inactiveUsers = $this->userRepository->findInactiveUsers();

        return array_map(function ($user) {
            return new UserDTO(
                $user->getId(),
                $user->getEmail(),
                $user->isActive()
            );
        }, $inactiveUsers);
    }
}
