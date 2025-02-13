<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250130153834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Crée la table 'article' avant toutes les autres tables qui y font référence
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, contenu VARCHAR(220) NOT NULL, image VARCHAR(220) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Crée les autres tables qui n'ont pas de dépendance sur 'article'
        $this->addSql('CREATE TABLE jeux_educ (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, age_recommende INT NOT NULL, description LONGTEXT NOT NULL, categorie VARCHAR(255) NOT NULL, datetime DATETIME NOT NULL, date_mise_ajour DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jouets (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, id_echange INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, contact VARCHAR(255) NOT NULL, date_publication DATETIME NOT NULL, INDEX IDX_936DA812A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recette (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, ingredients LONGTEXT NOT NULL, detail LONGTEXT NOT NULL, image VARCHAR(120) NOT NULL, age_recommende VARCHAR(120) NOT NULL, tempsPrep INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Crée les autres tables en s'assurant que 'article' existe
        $this->addSql('CREATE TABLE commentaires_jeux (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, jeux_id INT DEFAULT NULL, contenu VARCHAR(255) NOT NULL, note INT NOT NULL, date_commentaire VARCHAR(255) NOT NULL, INDEX IDX_9F92C847A76ED395 (user_id), INDEX IDX_9F92C847EC2AA9D2 (jeux_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires_recette (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, recette_id INT DEFAULT NULL, contenu LONGTEXT NOT NULL, note INT NOT NULL, date_commentaire DATETIME NOT NULL, is_approved TINYINT(1) NOT NULL, INDEX IDX_AB273B86A76ED395 (user_id), INDEX IDX_AB273B8689312FE9 (recette_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires_statut (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, statut_id INT DEFAULT NULL, contenu VARCHAR(255) NOT NULL, date_commentaire DATETIME NOT NULL, INDEX IDX_C146F276A76ED395 (user_id), INDEX IDX_C146F276F6203804 (statut_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Crée les tables restantes
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statut (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, contenu LONGTEXT NOT NULL, INDEX IDX_E564F0BFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, pseudo VARCHAR(255) DEFAULT NULL, date_inscription DATETIME NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_article (user_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_5A37106CA76ED395 (user_id), INDEX IDX_5A37106C7294869C (article_id), PRIMARY KEY(user_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Ajout des contraintes de clés étrangères après création des tables
        $this->addSql('ALTER TABLE user_article ADD CONSTRAINT FK_5A37106CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_article ADD CONSTRAINT FK_5A37106C7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // Cette méthode permet de revenir en arrière et de supprimer les tables dans l'ordre inverse de leur création
        $this->addSql('ALTER TABLE user_article DROP FOREIGN KEY FK_5A37106CA76ED395');
        $this->addSql('ALTER TABLE user_article DROP FOREIGN KEY FK_5A37106C7294869C');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE commentaires_jeux');
        $this->addSql('DROP TABLE commentaires_recette');
        $this->addSql('DROP TABLE commentaires_statut');
        $this->addSql('DROP TABLE jeux_educ');
        $this->addSql('DROP TABLE jouets');
        $this->addSql('DROP TABLE recette');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE statut');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_article');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
