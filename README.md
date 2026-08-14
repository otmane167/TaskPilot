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
src/Controller/          HTTP controllers
src/Service/             Application services
templates/               Twig templates
tests/                   PHPUnit test setup
var/taskpilot.json       Local JSON data store generated at runtime
```

## Main Routes

- `/` - Dashboard
- `/board` - Kanban board
- `/projects` - Project list and creation form
- `/teams` - Team list and creation form

## Roadmap

TaskPilot is planned as a full Symfony portfolio project. Upcoming features include:

- User registration, login, email verification, and password reset
- Doctrine entities for users, organizations, memberships, projects, tasks, comments, notifications, and attachments
- Role-based access control with Symfony voters
- Project and task CRUD forms
- Comments and activity logs
- File uploads for task attachments
- In-app and email notifications with Symfony Messenger
- REST API endpoints with filtering and pagination
- Admin dashboard
- Broader PHPUnit functional and integration test coverage

## License

This project is currently marked as proprietary in `composer.json`.
