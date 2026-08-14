# TaskPilot - Symfony Project Management App

TaskPilot is a Symfony 7 web app for managing teams, projects, and tasks. It includes a dashboard, project tracking, team management, and a Kanban-style task board.

## Features

- Dashboard with task and project statistics
- Kanban board with task status columns
- Create teams, projects, and tasks
- Assign tasks, priorities, statuses, and due dates
- Update task status from the board
- Delete tasks
- JSON-backed local data store
- Responsive Twig interface

## Tech Stack

- PHP 8.2+
- Symfony 7.4
- Twig
- Symfony UX Turbo and Stimulus
- Symfony Asset Mapper
- Doctrine ORM
- PostgreSQL with Docker Compose
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
git clone https://github.com/your-username/TaskPilot.git
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

## Run the App

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

The app currently stores data in:

```text
var/taskpilot.json
```

PostgreSQL is configured for future database-backed features:

```bash
docker compose up -d
```

## Useful Commands

Run tests:

```bash
php bin/phpunit
```

Clear the Symfony cache:

```bash
php bin/console cache:clear
```

Run database migrations after entities are added:

```bash
php bin/console doctrine:migrations:migrate
```

## Project Structure

```text
assets/                  Frontend JavaScript and CSS
config/                  Symfony configuration
public/                  Front controller and public assets
src/Controller/          Controllers
src/Service/             Services
templates/               Twig templates
tests/                   Tests
var/taskpilot.json       Local data store
```

## Main Routes

- `/` - Dashboard
- `/board` - Kanban board
- `/projects` - Project list and creation form
- `/teams` - Team list and creation form

## Roadmap

- Authentication and user profiles
- Doctrine entities and migrations
- Role-based access control
- Comments and activity logs
- File attachments
- Notifications
- REST API
- Admin dashboard
- More tests

## License

Proprietary.
