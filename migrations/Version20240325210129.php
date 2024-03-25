<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240325210129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe AS SELECT id, name, time, nb_people, difficulty, description, quantity, is_favorite, created_at, updated_at FROM recipe');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('CREATE TABLE recipe (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, time INTEGER DEFAULT NULL, nb_people INTEGER DEFAULT NULL, difficulty INTEGER DEFAULT NULL, description CLOB NOT NULL, quantity DOUBLE PRECISION DEFAULT NULL, is_favorite BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_DA88B137A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO recipe (id, name, time, nb_people, difficulty, description, quantity, is_favorite, created_at, updated_at) SELECT id, name, time, nb_people, difficulty, description, quantity, is_favorite, created_at, updated_at FROM __temp__recipe');
        $this->addSql('DROP TABLE __temp__recipe');
        $this->addSql('CREATE INDEX IDX_DA88B137A76ED395 ON recipe (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe AS SELECT id, name, time, nb_people, difficulty, description, quantity, is_favorite, created_at, updated_at FROM recipe');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('CREATE TABLE recipe (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL, time INTEGER DEFAULT NULL, nb_people INTEGER DEFAULT NULL, difficulty INTEGER DEFAULT NULL, description CLOB NOT NULL, quantity DOUBLE PRECISION DEFAULT NULL, is_favorite BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO recipe (id, name, time, nb_people, difficulty, description, quantity, is_favorite, created_at, updated_at) SELECT id, name, time, nb_people, difficulty, description, quantity, is_favorite, created_at, updated_at FROM __temp__recipe');
        $this->addSql('DROP TABLE __temp__recipe');
    }
}
