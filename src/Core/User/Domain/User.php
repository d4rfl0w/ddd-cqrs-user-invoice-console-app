<?php

namespace App\Core\User\Domain;

use App\Common\EventManager\EventsCollectorTrait;
use App\Core\User\Domain\Event\UserCreatedEvent;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "users")]
class User
{
    use EventsCollectorTrait;

    #[ORM\Id]
    #[ORM\Column(type: "integer", options: ["unsigned" => true], nullable: false)]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private ?int $id;

    #[ORM\Column(type: "string", length: 300, nullable: false)]
    private string $email;

    #[ORM\Column(type: "boolean", options: ["default" => true], nullable: false)]
    private bool $active;

    public function __construct(string $email, bool $active = true)
    {
        $this->id = null;
        $this->email = $email;
        $this->active = $active;

        $this->record(new UserCreatedEvent($this));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
