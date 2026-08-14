<?php

namespace App\Controller;

use App\Service\TaskPilotStore;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(TaskPilotStore $store): Response
    {
        return $this->page('dashboard', $store);
    }

    #[Route('/board', name: 'app_board', methods: ['GET'])]
    public function board(TaskPilotStore $store): Response
    {
        return $this->page('board', $store);
    }

    #[Route('/projects', name: 'app_projects', methods: ['GET'])]
    public function projects(TaskPilotStore $store): Response
    {
        return $this->page('projects', $store);
    }

    #[Route('/teams', name: 'app_teams', methods: ['GET'])]
    public function teams(TaskPilotStore $store): Response
    {
        return $this->page('teams', $store);
    }

    #[Route('/teams', name: 'app_team_create', methods: ['POST'])]
    public function createTeam(Request $request, TaskPilotStore $store): RedirectResponse
    {
        $store->addTeam($request->request->all());
        $this->addFlash('success', 'Team created.');

        return $this->redirectToRoute('app_home');
    }

    #[Route('/projects', name: 'app_project_create', methods: ['POST'])]
    public function createProject(Request $request, TaskPilotStore $store): RedirectResponse
    {
        $store->addProject($request->request->all());
        $this->addFlash('success', 'Project created.');

        return $this->redirectToRoute('app_home');
    }

    #[Route('/tasks', name: 'app_task_create', methods: ['POST'])]
    public function createTask(Request $request, TaskPilotStore $store): RedirectResponse
    {
        $store->addTask($request->request->all());
        $this->addFlash('success', 'Task created.');

        return $this->redirectToRoute('app_home');
    }

    #[Route('/tasks/{id}/status', name: 'app_task_status', methods: ['POST'])]
    public function updateTaskStatus(string $id, Request $request, TaskPilotStore $store): RedirectResponse
    {
        $store->updateTaskStatus($id, (string) $request->request->get('status', 'todo'));

        return $this->redirectToRoute('app_home');
    }

    #[Route('/tasks/{id}/delete', name: 'app_task_delete', methods: ['POST'])]
    public function deleteTask(string $id, TaskPilotStore $store): RedirectResponse
    {
        $store->deleteTask($id);
        $this->addFlash('success', 'Task deleted.');

        return $this->redirectToRoute('app_home');
    }

    private function page(string $page, TaskPilotStore $store): Response
    {
        return $this->render('home/index.html.twig', [
            ...$store->dashboard(),
            'page' => $page,
        ]);
    }
}
