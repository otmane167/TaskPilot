# TaskPilot - Symfony Resume Project Plan

TaskPilot is a team project-management app for teams, projects, tasks, deadlines, comments, files, and activity tracking.
It is designed to show strong Symfony skills: Doctrine modeling, security, forms, validation, voters, API Platform, testing, background jobs, UX, and clean architecture.

## Resume Pitch

Build a collaborative project manager where users can create organizations, invite teammates, manage projects, assign tasks, track deadlines, comment on work, upload attachments, and view team progress through dashboards.

Good resume line:

> Built TaskPilot, a Symfony 7 team project-management platform with role-based access control, Doctrine entities, API endpoints, dashboards, notifications, tests, and background jobs.

## Core Features

1. Authentication
   - Register, login, logout.
   - Email verification.
   - Password reset.
   - User profile page.

2. Organizations and Teams
   - A user can create an organization.
   - Organization owners can invite members.
   - Roles: owner, manager, member.
   - Access controlled with Symfony voters.

3. Projects
   - Create, edit, archive, and delete projects.
   - Project status: planning, active, on hold, completed.
   - Project deadlines and priority.
   - Project member assignment.

4. Tasks
   - Create tasks inside projects.
   - Assign tasks to users.
   - Task status: backlog, todo, in progress, review, done.
   - Priority: low, medium, high, urgent.
   - Due date tracking.
   - Subtasks or checklist items.

5. Comments and Activity
   - Comment on tasks.
   - Track changes in an activity log.
   - Show project timeline.

6. Dashboard
   - My assigned tasks.
   - Overdue tasks.
   - Tasks due this week.
   - Project progress charts.
   - Team workload view.

7. Notifications
   - In-app notifications for assignment, comments, and due dates.
   - Optional email notifications through Symfony Messenger.

8. Attachments
   - Upload files to tasks.
   - Validate file size and type.
   - Store metadata in Doctrine.

9. API
   - REST API for projects and tasks.
   - API authentication.
   - Filtering and pagination.
   - OpenAPI documentation with API Platform.

10. Admin
   - Admin dashboard with EasyAdmin.
   - Manage users, organizations, projects, and tasks.

## Suggested Symfony Stack

- Symfony 7
- Doctrine ORM
- Symfony Security
- Twig
- Symfony UX/Turbo
- Stimulus
- Symfony Forms
- Symfony Validator
- Symfony Messenger
- Symfony Mailer
- API Platform
- EasyAdmin
- PHPUnit
- Foundry and Faker for fixtures
- PostgreSQL or MySQL
- Docker Compose for local services

## Data Model

### User

- id
- email
- password
- firstName
- lastName
- avatar
- roles
- createdAt
- updatedAt

Relations:

- memberships
- assignedTasks
- comments
- notifications

### Organization

- id
- name
- slug
- createdAt
- updatedAt

Relations:

- memberships
- projects

### Membership

- id
- role
- joinedAt

Relations:

- user
- organization

### Project

- id
- name
- slug
- description
- status
- priority
- startsAt
- dueAt
- archivedAt
- createdAt
- updatedAt

Relations:

- organization
- tasks
- members

### Task

- id
- title
- description
- status
- priority
- dueAt
- completedAt
- position
- createdAt
- updatedAt

Relations:

- project
- assignee
- createdBy
- comments
- attachments
- activities

### Comment

- id
- body
- createdAt
- updatedAt

Relations:

- task
- author

### Activity

- id
- type
- message
- payload
- createdAt

Relations:

- task
- actor

### Notification

- id
- type
- title
- body
- readAt
- createdAt

Relations:

- recipient

### Attachment

- id
- originalName
- storedName
- mimeType
- size
- createdAt

Relations:

- task
- uploadedBy

## Pages

1. Login and register
2. Dashboard
3. Organization settings
4. Team members
5. Project list
6. Project detail
7. Kanban board
8. Task detail
9. My tasks
10. Notifications
11. Admin area

## API Endpoints

- `GET /api/projects`
- `POST /api/projects`
- `GET /api/projects/{id}`
- `PATCH /api/projects/{id}`
- `GET /api/tasks`
- `POST /api/tasks`
- `GET /api/tasks/{id}`
- `PATCH /api/tasks/{id}`
- `POST /api/tasks/{id}/comments`
- `GET /api/me/tasks`

## Security Rules

- Anonymous users can only access login, register, password reset, and public docs.
- Organization members can view organization projects.
- Managers can create projects and assign tasks.
- Task assignees can update their own task status.
- Owners can manage members and archive projects.
- Admin users can access EasyAdmin.

Use Symfony voters for:

- `OrganizationVoter`
- `ProjectVoter`
- `TaskVoter`

## Testing Plan

1. Unit tests
   - Task status transitions.
   - Project progress calculation.
   - Deadline/overdue helpers.

2. Functional tests
   - Login.
   - Project creation.
   - Task creation.
   - Access denied for non-members.

3. API tests
   - List tasks.
   - Create task.
   - Unauthorized requests.
   - Filtering and pagination.

4. Integration tests
   - Notifications created after task assignment.
   - Messenger handler sends deadline reminders.

## Build Phases

### Phase 1 - Foundation

- Create Symfony web app.
- Configure database.
- Add Security, Twig, Doctrine, MakerBundle.
- Build User authentication.
- Add base layout and navigation.

### Phase 2 - Main Domain

- Create Organization, Membership, Project, and Task entities.
- Add migrations and fixtures.
- Build CRUD pages for projects and tasks.
- Add validation and forms.

### Phase 3 - Collaboration

- Add comments.
- Add activity log.
- Add member invitations.
- Add voters and access rules.

### Phase 4 - Product Polish

- Add dashboard.
- Add Kanban board with Symfony UX.
- Add notifications.
- Add file attachments.

### Phase 5 - Resume Power Features

- Add API Platform.
- Add EasyAdmin.
- Add Messenger deadline reminders.
- Add tests.
- Add Docker Compose.
- Add CI workflow.

## Commands After Symfony Is Installed

```bash
symfony new TaskPilot --webapp
cd TaskPilot
composer require symfony/maker-bundle --dev
composer require api easyadmin
composer require zenstruck/foundry fakerphp/faker --dev
php bin/console make:user
php bin/console make:auth
php bin/console make:entity
php bin/console make:migration
php bin/console doctrine:migrations:migrate
symfony server:start
```

## What Makes It Good For A Resume

- It solves a familiar business problem.
- It has a realistic relational database model.
- It demonstrates Symfony Security beyond basic login.
- It includes both Twig pages and API endpoints.
- It uses background jobs, emails, file uploads, dashboards, and tests.
- It gives you clear screenshots and demo flows for interviews.

