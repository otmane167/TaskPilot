<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;

final class TaskPilotStore
{
    private string $path;

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        string $projectDir,
        private readonly Filesystem $filesystem,
    ) {
        $this->path = $projectDir.'/var/taskpilot.json';
    }

    public function dashboard(): array
    {
        $data = $this->read();
        $today = new \DateTimeImmutable('today');

        $stats = [
            'teams' => count($data['teams']),
            'projects' => count($data['projects']),
            'tasks' => count($data['tasks']),
            'done' => 0,
            'overdue' => 0,
            'dueSoon' => 0,
        ];

        foreach ($data['tasks'] as $task) {
            if ($task['status'] === 'done') {
                ++$stats['done'];
            }

            if ($task['dueDate'] !== '') {
                $dueDate = new \DateTimeImmutable($task['dueDate']);

                if ($task['status'] !== 'done' && $dueDate < $today) {
                    ++$stats['overdue'];
                }

                if ($task['status'] !== 'done' && $dueDate >= $today && $dueDate <= $today->modify('+7 days')) {
                    ++$stats['dueSoon'];
                }
            }
        }

        $data['stats'] = $stats;
        $data['tasksByStatus'] = $this->groupTasksByStatus($data['tasks']);

        return $data;
    }

    public function addTeam(array $input): void
    {
        $data = $this->read();

        $data['teams'][] = [
            'id' => $this->id(),
            'name' => $this->text($input['name'] ?? 'New team'),
            'lead' => $this->text($input['lead'] ?? ''),
            'members' => $this->csv($input['members'] ?? ''),
            'createdAt' => $this->now(),
        ];

        $this->write($data);
    }

    public function addProject(array $input): void
    {
        $data = $this->read();

        $data['projects'][] = [
            'id' => $this->id(),
            'teamId' => $input['teamId'] ?? '',
            'name' => $this->text($input['name'] ?? 'New project'),
            'description' => $this->text($input['description'] ?? ''),
            'priority' => $input['priority'] ?? 'medium',
            'deadline' => $input['deadline'] ?? '',
            'createdAt' => $this->now(),
        ];

        $this->write($data);
    }

    public function addTask(array $input): void
    {
        $data = $this->read();

        $data['tasks'][] = [
            'id' => $this->id(),
            'projectId' => $input['projectId'] ?? '',
            'title' => $this->text($input['title'] ?? 'New task'),
            'description' => $this->text($input['description'] ?? ''),
            'assignee' => $this->text($input['assignee'] ?? 'Unassigned'),
            'status' => $input['status'] ?? 'todo',
            'priority' => $input['priority'] ?? 'medium',
            'dueDate' => $input['dueDate'] ?? '',
            'createdAt' => $this->now(),
        ];

        $this->write($data);
    }

    public function updateTaskStatus(string $id, string $status): void
    {
        $data = $this->read();

        foreach ($data['tasks'] as &$task) {
            if ($task['id'] === $id) {
                $task['status'] = $status;
                $task['updatedAt'] = $this->now();
                break;
            }
        }

        $this->write($data);
    }

    public function deleteTask(string $id): void
    {
        $data = $this->read();
        $data['tasks'] = array_values(array_filter($data['tasks'], static fn (array $task): bool => $task['id'] !== $id));

        $this->write($data);
    }

    private function read(): array
    {
        if (!$this->filesystem->exists($this->path)) {
            $this->write($this->seed());
        }

        $data = json_decode((string) file_get_contents($this->path), true);

        return is_array($data) ? array_replace_recursive($this->seed(false), $data) : $this->seed();
    }

    private function write(array $data): void
    {
        $this->filesystem->dumpFile($this->path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    private function seed(bool $withData = true): array
    {
        if (!$withData) {
            return ['teams' => [], 'projects' => [], 'tasks' => []];
        }

        $team = $this->id();
        $design = $this->id();
        $platform = $this->id();

        return [
            'teams' => [
                [
                    'id' => $team,
                    'name' => 'Product Team',
                    'lead' => 'Sara Manager',
                    'members' => ['Sara Manager', 'Yassine Dev', 'Nora QA'],
                    'createdAt' => $this->now(),
                ],
            ],
            'projects' => [
                [
                    'id' => $design,
                    'teamId' => $team,
                    'name' => 'Client Portal Redesign',
                    'description' => 'Improve the client workspace, navigation, and task visibility.',
                    'priority' => 'high',
                    'deadline' => (new \DateTimeImmutable('+14 days'))->format('Y-m-d'),
                    'createdAt' => $this->now(),
                ],
                [
                    'id' => $platform,
                    'teamId' => $team,
                    'name' => 'Operations Dashboard',
                    'description' => 'Track overdue work, team workload, and weekly delivery.',
                    'priority' => 'medium',
                    'deadline' => (new \DateTimeImmutable('+30 days'))->format('Y-m-d'),
                    'createdAt' => $this->now(),
                ],
            ],
            'tasks' => [
                [
                    'id' => $this->id(),
                    'projectId' => $design,
                    'title' => 'Define project roles',
                    'description' => 'Confirm owner, manager, and member responsibilities.',
                    'assignee' => 'Sara Manager',
                    'status' => 'done',
                    'priority' => 'high',
                    'dueDate' => (new \DateTimeImmutable('-1 day'))->format('Y-m-d'),
                    'createdAt' => $this->now(),
                ],
                [
                    'id' => $this->id(),
                    'projectId' => $design,
                    'title' => 'Build task workflow',
                    'description' => 'Support todo, in progress, review, and done states.',
                    'assignee' => 'Yassine Dev',
                    'status' => 'in_progress',
                    'priority' => 'urgent',
                    'dueDate' => (new \DateTimeImmutable('+2 days'))->format('Y-m-d'),
                    'createdAt' => $this->now(),
                ],
                [
                    'id' => $this->id(),
                    'projectId' => $platform,
                    'title' => 'QA dashboard metrics',
                    'description' => 'Verify overdue and due-soon calculations.',
                    'assignee' => 'Nora QA',
                    'status' => 'review',
                    'priority' => 'medium',
                    'dueDate' => (new \DateTimeImmutable('+5 days'))->format('Y-m-d'),
                    'createdAt' => $this->now(),
                ],
            ],
        ];
    }

    private function groupTasksByStatus(array $tasks): array
    {
        $columns = ['backlog' => [], 'todo' => [], 'in_progress' => [], 'review' => [], 'done' => []];

        foreach ($tasks as $task) {
            $columns[$task['status'] ?? 'todo'][] = $task;
        }

        return $columns;
    }

    private function id(): string
    {
        return bin2hex(random_bytes(6));
    }

    private function now(): string
    {
        return (new \DateTimeImmutable())->format(\DateTimeInterface::ATOM);
    }

    private function text(string $value): string
    {
        return trim(strip_tags($value));
    }

    private function csv(string $value): array
    {
        return array_values(array_filter(array_map(fn (string $item): string => $this->text($item), explode(',', $value))));
    }
}
