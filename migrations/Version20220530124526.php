<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220530124526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE game_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE platform_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE price_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE publisher_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE studio_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE game (id INT NOT NULL, studio_id INT NOT NULL, name VARCHAR(255) NOT NULL, genre VARCHAR(255) NOT NULL, announce_date DATE NOT NULL, release_date DATE DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_232B318C446F285F ON game (studio_id)');
        $this->addSql('CREATE TABLE platform (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, date DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE price (id INT NOT NULL, platform_id INT NOT NULL, game_id INT NOT NULL, cost DOUBLE PRECISION NOT NULL, discount INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CAC822D9FFE6496F ON price (platform_id)');
        $this->addSql('CREATE INDEX IDX_CAC822D9E48FD905 ON price (game_id)');
        $this->addSql('CREATE TABLE publisher (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE review (id INT NOT NULL, author_id INT NOT NULL, admin_id INT DEFAULT NULL, game_id INT NOT NULL, text VARCHAR(255) NOT NULL, date DATE NOT NULL, grade INT NOT NULL, checked BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_794381C6F675F31B ON review (author_id)');
        $this->addSql('CREATE INDEX IDX_794381C6642B8210 ON review (admin_id)');
        $this->addSql('CREATE INDEX IDX_794381C6E48FD905 ON review (game_id)');
        $this->addSql('CREATE TABLE studio (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE studio_user (studio_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(studio_id, user_id))');
        $this->addSql('CREATE INDEX IDX_EC686DD1446F285F ON studio_user (studio_id)');
        $this->addSql('CREATE INDEX IDX_EC686DD1A76ED395 ON studio_user (user_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, publisher_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE INDEX IDX_8D93D64940C86FCE ON "user" (publisher_id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C446F285F FOREIGN KEY (studio_id) REFERENCES studio (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE price ADD CONSTRAINT FK_CAC822D9FFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE price ADD CONSTRAINT FK_CAC822D9E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6642B8210 FOREIGN KEY (admin_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE studio_user ADD CONSTRAINT FK_EC686DD1446F285F FOREIGN KEY (studio_id) REFERENCES studio (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE studio_user ADD CONSTRAINT FK_EC686DD1A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64940C86FCE FOREIGN KEY (publisher_id) REFERENCES publisher (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE price DROP CONSTRAINT FK_CAC822D9E48FD905');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6E48FD905');
        $this->addSql('ALTER TABLE price DROP CONSTRAINT FK_CAC822D9FFE6496F');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64940C86FCE');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318C446F285F');
        $this->addSql('ALTER TABLE studio_user DROP CONSTRAINT FK_EC686DD1446F285F');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6F675F31B');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6642B8210');
        $this->addSql('ALTER TABLE studio_user DROP CONSTRAINT FK_EC686DD1A76ED395');
        $this->addSql('DROP SEQUENCE game_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE platform_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE price_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE publisher_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE review_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE studio_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE platform');
        $this->addSql('DROP TABLE price');
        $this->addSql('DROP TABLE publisher');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE studio');
        $this->addSql('DROP TABLE studio_user');
        $this->addSql('DROP TABLE "user"');
    }
}
