<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109143431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jouets ADD id_echange INT NOT NULL');
        $this->addSql('ALTER TABLE recette CHANGE ingredients ingredients LONGTEXT NOT NULL, CHANGE detail detail LONGTEXT NOT NULL, CHANGE tempsPrep temps_prep INT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE nom nom VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jouets DROP id_echange');
        $this->addSql('ALTER TABLE recette CHANGE ingredients ingredients TEXT NOT NULL, CHANGE detail detail TEXT NOT NULL, CHANGE temps_prep tempsPrep INT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE nom nom VARCHAR(255) NOT NULL');
    }
}
