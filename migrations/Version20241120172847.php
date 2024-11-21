<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241120172847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires_recette ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaires_recette ADD CONSTRAINT FK_AB273B86A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AB273B86A76ED395 ON commentaires_recette (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires_recette DROP FOREIGN KEY FK_AB273B86A76ED395');
        $this->addSql('DROP INDEX IDX_AB273B86A76ED395 ON commentaires_recette');
        $this->addSql('ALTER TABLE commentaires_recette DROP user_id');
    }
}
