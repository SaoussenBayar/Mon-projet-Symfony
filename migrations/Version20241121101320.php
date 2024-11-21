<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241121101320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires_jeux ADD user_id INT DEFAULT NULL, ADD jeux_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaires_jeux ADD CONSTRAINT FK_9F92C847A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaires_jeux ADD CONSTRAINT FK_9F92C847EC2AA9D2 FOREIGN KEY (jeux_id) REFERENCES jeux_educ (id)');
        $this->addSql('CREATE INDEX IDX_9F92C847A76ED395 ON commentaires_jeux (user_id)');
        $this->addSql('CREATE INDEX IDX_9F92C847EC2AA9D2 ON commentaires_jeux (jeux_id)');
        $this->addSql('ALTER TABLE jouets ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jouets ADD CONSTRAINT FK_936DA812A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_936DA812A76ED395 ON jouets (user_id)');
        $this->addSql('ALTER TABLE statut ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE statut ADD CONSTRAINT FK_E564F0BFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E564F0BFA76ED395 ON statut (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires_jeux DROP FOREIGN KEY FK_9F92C847A76ED395');
        $this->addSql('ALTER TABLE commentaires_jeux DROP FOREIGN KEY FK_9F92C847EC2AA9D2');
        $this->addSql('DROP INDEX IDX_9F92C847A76ED395 ON commentaires_jeux');
        $this->addSql('DROP INDEX IDX_9F92C847EC2AA9D2 ON commentaires_jeux');
        $this->addSql('ALTER TABLE commentaires_jeux DROP user_id, DROP jeux_id');
        $this->addSql('ALTER TABLE jouets DROP FOREIGN KEY FK_936DA812A76ED395');
        $this->addSql('DROP INDEX IDX_936DA812A76ED395 ON jouets');
        $this->addSql('ALTER TABLE jouets DROP user_id');
        $this->addSql('ALTER TABLE statut DROP FOREIGN KEY FK_E564F0BFA76ED395');
        $this->addSql('DROP INDEX IDX_E564F0BFA76ED395 ON statut');
        $this->addSql('ALTER TABLE statut DROP user_id');
    }
}
