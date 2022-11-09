<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220601152620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game ADD publisher_id INT NOT NULL');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C40C86FCE FOREIGN KEY (publisher_id) REFERENCES publisher (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_232B318C40C86FCE ON game (publisher_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318C40C86FCE');
        $this->addSql('DROP INDEX IDX_232B318C40C86FCE');
        $this->addSql('ALTER TABLE game DROP publisher_id');
    }
}
