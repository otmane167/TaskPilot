<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'team')]
class Team
{
    #[ORM\Id]
    #[ORM\Column(length: 24)]
    private string $id;

    #[ORM\Column(length: 120)]
    private string $name;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $lead = null;

    #[ORM\Column(type: 'json')]
    private array $members = [];

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    /** @var Collection<int, Project> */
    #[ORM\OneToMany(targetEntity: Project::class, mappedBy: 'team')]
    private Collection $projects;

    public function __construct(string $name, ?string $lead = null, array $members = [])
    {
        $this->id = bin2hex(random_bytes(6));
        $this->name = $name;
        $this->lead = $lead;
        $this->members = $members;
        $this->createdAt = new \DateTimeImmutable();
        $this->projects = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLead(): ?string
    {
        return $this->lead;
    }

    public function getMembers(): array
    {
        return $this->members;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /** @return Collection<int, Project> */
    public function getProjects(): Collection
    {
        return $this->projects;
    }
}
