# TaskPilot

TaskPilot is a Symfony 7 project-management app for organizing teams, projects, and tasks in a clean web dashboard. The current version is a lightweight, portfolio-ready prototype with a JSON-backed data store, Twig pages, and working task-board flows.

## Features

- Dashboard with team, project, task, due-soon, overdue, and completed-task stats
- Kanban-style board with Backlog, To do, In progress, Review, and Done columns
- Create teams with leads and comma-separated members
- Create projects with team ownership, priority, description, and deadline
- Create tasks with project, assignee, priority, status, and due date
- Update task status directly from the board
- Delete tasks
- Seed data on first run for an immediate demo experience
- Responsive Twig/CSS interface with Symfony Asset Mapper

## Tech Stack

- PHP 8.2+
- Symfony 7.4
- Twig
- Symfony UX Turbo and Stimulus
- Symfony Asset Mapper
- Doctrine ORM and migrations configured for future database-backed features
- PostgreSQL service via Docker Compose
- PHPUnit

## Getting Started

### Prerequisites

- PHP 8.2 or newer
- Composer
- Symfony CLI, recommended
- Docker, optional for the PostgreSQL service

### Installation

Clone the repository and install dependencies:

```bash
git clone https://github.com/your-username/taskpilot.git
cd taskpilot
composer install
```

Create a local environment file if needed:

```bash
cp .env .env.local
```

Set `APP_SECRET` in `.env.local`:

```dotenv
APP_SECRET=change-me
```

### Run the App

Using the Symfony CLI:

```bash
symfony server:start
```

Then open:

```text
http://127.0.0.1:8000
```

If you do not use the Symfony CLI, you can run PHP's built-in server:

```bash
php -S 127.0.0.1:8000 -t public
```

## Database

The current prototype stores app data in:

```text
var/taskpilot.json
```

Doctrine and PostgreSQL are already configured for the planned database-backed version. To start the local PostgreSQL container:

```bash
docker compose up -d
```

The default database connection is configured in `.env`:

```dotenv
DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
