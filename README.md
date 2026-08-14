# TaskPilot
# TaskPilot - Symfony Project Management App

TaskPilot is a Symfony 7 project-management app for organizing teams, projects, and tasks in a clean web dashboard. The current version is a lightweight, portfolio-ready prototype with a JSON-backed data store, Twig pages, and working task-board flows.
TaskPilot is a Symfony 7 web app for managing teams, projects, and tasks. It includes a dashboard, project tracking, team management, and a Kanban-style task board.

## Features

- Dashboard with team, project, task, due-soon, overdue, and completed-task stats
- Kanban-style board with Backlog, To do, In progress, Review, and Done columns
- Create teams with leads and comma-separated members
- Create projects with team ownership, priority, description, and deadline
- Create tasks with project, assignee, priority, status, and due date
- Update task status directly from the board
- Dashboard with task and project statistics
- Kanban board with task status columns
- Create teams, projects, and tasks
- Assign tasks, priorities, statuses, and due dates
- Update task status from the board
- Delete tasks
- Seed data on first run for an immediate demo experience
- Responsive Twig/CSS interface with Symfony Asset Mapper
- JSON-backed local data store
- Responsive Twig interface

## Tech Stack

- Twig
- Symfony UX Turbo and Stimulus
- Symfony Asset Mapper
- Doctrine ORM and migrations configured for future database-backed features
- PostgreSQL service via Docker Compose
- Doctrine ORM
- PostgreSQL with Docker Compose
- PHPUnit

## Getting Started
Clone the repository and install dependencies:

```bash
git clone https://github.com/your-username/taskpilot.git
git clone https://github.com/your-username/TaskPilot.git
cd taskpilot
composer install
```
APP_SECRET=change-me
```

### Run the App
## Run the App

Using the Symfony CLI:


## Database

The current prototype stores app data in:
The app currently stores data in:

```text
var/taskpilot.json
```

Doctrine and PostgreSQL are already configured for the planned database-backed version. To start the local PostgreSQL container:
PostgreSQL is configured for future database-backed features:

```bash
docker compose up -d
```

The default database connection is configured in `.env`:

```dotenv
DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
```

## Useful Commands
assets/                  Frontend JavaScript and CSS
config/                  Symfony configuration
public/                  Front controller and public assets
src/Controller/          HTTP controllers
src/Service/             Application services
src/Controller/          Controllers
src/Service/             Services
templates/               Twig templates
tests/                   PHPUnit test setup
var/taskpilot.json       Local JSON data store generated at runtime
tests/                   Tests
var/taskpilot.json       Local data store
```

## Main Routes

## Roadmap

TaskPilot is planned as a full Symfony portfolio project. Upcoming features include:
