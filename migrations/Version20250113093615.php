<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250113093615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jouets ADD id_echange INT NOT NULL');
        $this->addSql('ALTER TABLE recette CHANGE ingredients ingredients LONGTEXT NOT NULL, CHANGE detail detail LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE user ADD reset_token VARCHAR(255) DEFAULT NULL, CHANGE nom nom VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jouets DROP id_echange');
        $this->addSql('ALTER TABLE user DROP reset_token, CHANGE nom nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE recette CHANGE ingredients ingredients TEXT NOT NULL, CHANGE detail detail TEXT NOT NULL');
    }
}
