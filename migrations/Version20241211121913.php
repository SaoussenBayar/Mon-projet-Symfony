<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241211121913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(50) NOT NULL, contenu VARCHAR(255) NOT NULL, image LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaires_recette ADD CONSTRAINT FK_AB273B86A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaires_recette ADD CONSTRAINT FK_AB273B8689312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id)');
        $this->addSql('ALTER TABLE commentaires_statut ADD CONSTRAINT FK_C146F276A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaires_statut ADD CONSTRAINT FK_C146F276F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE jouets ADD id_echange INT NOT NULL');
        $this->addSql('ALTER TABLE jouets ADD CONSTRAINT FK_936DA812A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE statut ADD CONSTRAINT FK_E564F0BFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user DROP prenom, DROP date_naissance, DROP ville, DROP pays, DROP date_inscription, CHANGE nom nom VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE article');
        $this->addSql('ALTER TABLE commentaires_recette DROP FOREIGN KEY FK_AB273B86A76ED395');
        $this->addSql('ALTER TABLE commentaires_recette DROP FOREIGN KEY FK_AB273B8689312FE9');
        $this->addSql('ALTER TABLE user ADD prenom VARCHAR(255) NOT NULL, ADD date_naissance DATE NOT NULL, ADD ville VARCHAR(255) NOT NULL, ADD pays VARCHAR(255) NOT NULL, ADD date_inscription DATETIME NOT NULL, CHANGE nom nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE commentaires_statut DROP FOREIGN KEY FK_C146F276A76ED395');
        $this->addSql('ALTER TABLE commentaires_statut DROP FOREIGN KEY FK_C146F276F6203804');
        $this->addSql('ALTER TABLE statut DROP FOREIGN KEY FK_E564F0BFA76ED395');
        $this->addSql('ALTER TABLE jouets DROP FOREIGN KEY FK_936DA812A76ED395');
        $this->addSql('ALTER TABLE jouets DROP id_echange');
    }
}
