<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261108000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create teams, projects and tasks with their relationships';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE team (id VARCHAR(24) NOT NULL, name VARCHAR(120) NOT NULL, lead VARCHAR(120) DEFAULT NULL, members JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE project (id VARCHAR(24) NOT NULL, team_id VARCHAR(24) DEFAULT NULL, name VARCHAR(160) NOT NULL, description TEXT NOT NULL, priority VARCHAR(20) NOT NULL, deadline DATE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_PROJECT_TEAM ON project (team_id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_PROJECT_TEAM FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE TABLE task (id VARCHAR(24) NOT NULL, project_id VARCHAR(24) NOT NULL, title VARCHAR(180) NOT NULL, description TEXT NOT NULL, assignee VARCHAR(120) DEFAULT NULL, status VARCHAR(20) NOT NULL, priority VARCHAR(20) NOT NULL, due_date DATE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_TASK_PROJECT ON task (project_id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_TASK_PROJECT FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_TASK_PROJECT');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_PROJECT_TEAM');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE team');
    }
}
