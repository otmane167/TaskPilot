<?php

namespace App\Service;

use App\Entity\Project;
use App\Entity\Task;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;

final class TaskPilotStore
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function dashboard(): array
    {
        $teams = $this->entityManager->getRepository(Team::class)->findBy([], ['createdAt' => 'ASC']);
        $projects = $this->entityManager->getRepository(Project::class)->findBy([], ['createdAt' => 'ASC']);
        $tasks = $this->entityManager->getRepository(Task::class)->findBy([], ['createdAt' => 'ASC']);

        if (!$teams && !$projects && !$tasks) {
            $this->seed();
            return $this->dashboard();
        }

        $teamRows = [];
        foreach ($teams as $team) {
            $teamRows[] = [
                'id' => $team->getId(),
                'name' => $team->getName(),
                'lead' => $team->getLead() ?? '',
                'members' => $team->getMembers(),
            ];
        }

        $projectRows = [];
        foreach ($projects as $project) {
            $deadline = $project->getDeadline();
            $team = $project->getTeam();
            $projectRows[] = [
                'id' => $project->getId(),
                'teamId' => $team ? $team->getId() : '',
                'name' => $project->getName(),
                'description' => $project->getDescription(),
                'priority' => $project->getPriority(),
                'deadline' => $deadline ? $deadline->format('Y-m-d') : '',
            ];
        }

        $taskRows = [];
        foreach ($tasks as $task) {
            $dueDate = $task->getDueDate();
            $taskRows[] = [
                'id' => $task->getId(),
                'projectId' => $task->getProject()->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'assignee' => $task->getAssignee() ?? '',
                'status' => $task->getStatus(),
                'priority' => $task->getPriority(),
                'dueDate' => $dueDate ? $dueDate->format('Y-m-d') : '',
            ];
        }

        $today = new \DateTimeImmutable('today');
        $stats = ['teams' => count($teamRows), 'projects' => count($projectRows), 'tasks' => count($taskRows), 'done' => 0, 'overdue' => 0, 'dueSoon' => 0];
        foreach ($taskRows as $task) {
            if ($task['status'] === 'done') {
                $stats['done']++;
            }

            if ($task['dueDate'] === '' || $task['status'] === 'done') {
                continue;
            }

            $dueDate = new \DateTimeImmutable($task['dueDate']);
            if ($dueDate < $today) {
                $stats['overdue']++;
            }

            if ($dueDate >= $today && $dueDate <= $today->modify('+7 days')) {
                $stats['dueSoon']++;
            }
        }

        return [
            'teams' => $teamRows, 'projects' => $projectRows, 'tasks' => $taskRows, 'stats' => $stats,
            'tasksByStatus' => $this->groupTasksByStatus($taskRows),
        ];
    }

    public function addTeam(array $input): void
    {
        $name = $this->text($input['name'] ?? 'New team');
        $lead = $this->text($input['lead'] ?? '');
        $members = $this->csv($input['members'] ?? '');
        $team = new Team($name, $lead !== '' ? $lead : null, $members);
        $this->entityManager->persist($team);
        $this->entityManager->flush();
    }

    public function addProject(array $input): void
    {
        $teamId = (string) ($input['teamId'] ?? '');
        $team = $teamId !== '' ? $this->entityManager->find(Team::class, $teamId) : null;
        $name = $this->text($input['name'] ?? 'New project');
        $description = $this->text($input['description'] ?? '');
        $priority = $this->choice($input['priority'] ?? '', ['low', 'medium', 'high', 'urgent'], 'medium');
        $deadline = $this->date($input['deadline'] ?? '');
        $project = new Project($name, $team, $description, $priority, $deadline);
        $this->entityManager->persist($project);
        $this->entityManager->flush();
    }

    public function addTask(array $input): void
    {
        $projectId = (string) ($input['projectId'] ?? '');
        $project = $this->entityManager->find(Project::class, $projectId);
        if (!$project instanceof Project) return;
        $title = $this->text($input['title'] ?? 'New task');
        $description = $this->text($input['description'] ?? '');
        $assignee = $this->text($input['assignee'] ?? '');
        $status = $this->choice($input['status'] ?? '', ['backlog', 'todo', 'in_progress', 'review', 'done'], 'todo');
        $priority = $this->choice($input['priority'] ?? '', ['low', 'medium', 'high', 'urgent'], 'medium');
        $dueDate = $this->date($input['dueDate'] ?? '');
        $task = new Task($project, $title, $description, $assignee !== '' ? $assignee : null, $status, $priority, $dueDate);
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function updateTaskStatus(string $id, string $status): void
    {
        $task = $this->entityManager->find(Task::class, $id);
        if ($task instanceof Task && in_array($status, ['backlog', 'todo', 'in_progress', 'review', 'done'], true)) {
            $task->changeStatus($status);
            $this->entityManager->flush();
        }
    }

    public function deleteTask(string $id): void
    {
        $task = $this->entityManager->find(Task::class, $id);
        if ($task instanceof Task) {
            $this->entityManager->remove($task);
            $this->entityManager->flush();
        }
    }

    private function seed(): void
    {
        $team = new Team('Product Team', 'Sara Manager', ['Sara Manager', 'Yassine Dev', 'Nora QA']);
        $design = new Project('Client Portal Redesign', $team, 'Improve the client workspace, navigation, and task visibility.', 'high', new \DateTimeImmutable('+14 days'));
        $platform = new Project('Operations Dashboard', $team, 'Track overdue work, team workload, and weekly delivery.', 'medium', new \DateTimeImmutable('+30 days'));
        $this->entityManager->persist($team);
        $this->entityManager->persist($design);
        $this->entityManager->persist($platform);
        $this->entityManager->persist(new Task($design, 'Define project roles', 'Confirm owner, manager, and member responsibilities.', 'Sara Manager', 'done', 'high', new \DateTimeImmutable('-1 day')));
        $this->entityManager->persist(new Task($design, 'Build task workflow', 'Support todo, in progress, review, and done states.', 'Yassine Dev', 'in_progress', 'urgent', new \DateTimeImmutable('+2 days')));
        $this->entityManager->persist(new Task($platform, 'QA dashboard metrics', 'Verify overdue and due-soon calculations.', 'Nora QA', 'review', 'medium', new \DateTimeImmutable('+5 days')));
        $this->entityManager->flush();
    }

    private function groupTasksByStatus(array $tasks): array
    {
        $columns = ['backlog' => [], 'todo' => [], 'in_progress' => [], 'review' => [], 'done' => []];
        foreach ($tasks as $task) {
            $columns[$task['status']][] = $task;
        }

        return $columns;
    }

    private function date(mixed $value): ?\DateTimeImmutable
    {
        if (!is_string($value) || $value === '') {
            return null;
        }

        $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $value);
        if ($date && $date->format('Y-m-d') === $value) {
            return $date;
        }

        return null;
    }

    private function choice(mixed $value, array $allowed, string $default): string
    {
        if (is_string($value) && in_array($value, $allowed, true)) {
            return $value;
        }

        return $default;
    }

    private function text(mixed $value): string
    {
        if (!is_scalar($value)) {
            return '';
        }

        return trim(strip_tags((string) $value));
    }

    private function csv(mixed $value): array
    {
        $members = explode(',', $this->text($value));
        $cleanMembers = [];

        foreach ($members as $member) {
            $member = $this->text($member);
            if ($member !== '') {
                $cleanMembers[] = $member;
            }
        }

        return $cleanMembers;
    }
}
