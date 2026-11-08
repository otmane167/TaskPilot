<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'project')]
class Project
{
    #[ORM\Id]
    #[ORM\Column(length: 24)]
    private string $id;

    #[ORM\ManyToOne(targetEntity: Team::class, inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Team $team;

    #[ORM\Column(length: 160)]
    private string $name;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(length: 20)]
    private string $priority;

    #[ORM\Column(type: 'date_immutable', nullable: true)]
    private ?\DateTimeImmutable $deadline;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    /** @var Collection<int, Task> */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'project', cascade: ['remove'], orphanRemoval: true)]
    private Collection $tasks;

    public function __construct(string $name, ?Team $team = null, string $description = '', string $priority = 'medium', ?\DateTimeImmutable $deadline = null)
    {
        $this->id = bin2hex(random_bytes(6));
        $this->name = $name;
        $this->team = $team;
        $this->description = $description;
        $this->priority = $priority;
        $this->deadline = $deadline;
        $this->createdAt = new \DateTimeImmutable();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPriority(): string
    {
        return $this->priority;
    }

    public function getDeadline(): ?\DateTimeImmutable
    {
        return $this->deadline;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /** @return Collection<int, Task> */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }
}
