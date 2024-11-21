<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241121100542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires_statut ADD user_id INT DEFAULT NULL, ADD statut_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaires_statut ADD CONSTRAINT FK_C146F276A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaires_statut ADD CONSTRAINT FK_C146F276F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
        $this->addSql('CREATE INDEX IDX_C146F276A76ED395 ON commentaires_statut (user_id)');
        $this->addSql('CREATE INDEX IDX_C146F276F6203804 ON commentaires_statut (statut_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires_statut DROP FOREIGN KEY FK_C146F276A76ED395');
        $this->addSql('ALTER TABLE commentaires_statut DROP FOREIGN KEY FK_C146F276F6203804');
        $this->addSql('DROP INDEX IDX_C146F276A76ED395 ON commentaires_statut');
        $this->addSql('DROP INDEX IDX_C146F276F6203804 ON commentaires_statut');
        $this->addSql('ALTER TABLE commentaires_statut DROP user_id, DROP statut_id');
    }
}
