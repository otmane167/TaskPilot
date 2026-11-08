<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'task')]
class Task
{
    #[ORM\Id]
    #[ORM\Column(length: 24)]
    private string $id;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Project $project;

    #[ORM\Column(length: 180)]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $assignee;

    #[ORM\Column(length: 20)]
    private string $status;

    #[ORM\Column(length: 20)]
    private string $priority;

    #[ORM\Column(type: 'date_immutable', nullable: true)]
    private ?\DateTimeImmutable $dueDate;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct(Project $project, string $title, string $description = '', ?string $assignee = null, string $status = 'todo', string $priority = 'medium', ?\DateTimeImmutable $dueDate = null)
    {
        $this->id = bin2hex(random_bytes(6));
        $this->project = $project;
        $this->title = $title;
        $this->description = $description;
        $this->assignee = $assignee;
        $this->status = $status;
        $this->priority = $priority;
        $this->dueDate = $dueDate;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAssignee(): ?string
    {
        return $this->assignee;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getPriority(): string
    {
        return $this->priority;
    }

    public function getDueDate(): ?\DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function changeStatus(string $status): void
    {
        $this->status = $status;
        $this->updatedAt = new \DateTimeImmutable();
    }
}
