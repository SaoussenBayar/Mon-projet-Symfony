<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241120170029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE jeux_educ (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, age_recommende INT NOT NULL, description LONGTEXT NOT NULL, categorie VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, date_mise_ajour DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaires_jeux CHANGE date_commentaire date_commentaire VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE jeux_educ');
        $this->addSql('ALTER TABLE commentaires_jeux CHANGE date_commentaire date_commentaire DATETIME NOT NULL');
    }
}
