<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241017161604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tm_task (id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, in_progress BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F8586BE15E237E06 ON tm_task (name)');
        $this->addSql('COMMENT ON COLUMN tm_task.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN tm_task.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN tm_task.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE tm_time (id UUID NOT NULL, task_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, start_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C5B228818DB60186 ON tm_time (task_id)');
        $this->addSql('COMMENT ON COLUMN tm_time.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN tm_time.task_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN tm_time.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN tm_time.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN tm_time.start_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN tm_time.end_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE tm_time ADD CONSTRAINT FK_C5B228818DB60186 FOREIGN KEY (task_id) REFERENCES tm_task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tm_time DROP CONSTRAINT FK_C5B228818DB60186');
        $this->addSql('DROP TABLE tm_task');
        $this->addSql('DROP TABLE tm_time');
    }
}
