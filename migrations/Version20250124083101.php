<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250124083101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accord (id INT AUTO_INCREMENT NOT NULL, plat_id INT DEFAULT NULL, produit_id INT DEFAULT NULL, INDEX IDX_91361A04D73DB560 (plat_id), INDEX IDX_91361A04F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date_commande DATE NOT NULL, statut VARCHAR(50) DEFAULT NULL, INDEX IDX_6EEAA67DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE domaine (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, nom_domaine VARCHAR(100) NOT NULL, adresse_domaine VARCHAR(100) NOT NULL, INDEX IDX_78AF0ACC98260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, date_facture DATE NOT NULL, total_facture NUMERIC(10, 2) NOT NULL, UNIQUE INDEX UNIQ_FE86641082EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plat (id INT AUTO_INCREMENT NOT NULL, nom_plat VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, domaine_id INT DEFAULT NULL, nom_produit VARCHAR(50) NOT NULL, prix NUMERIC(10, 2) NOT NULL, image VARCHAR(255) DEFAULT NULL, detail_produit LONGTEXT NOT NULL, contenance VARCHAR(50) NOT NULL, exclusif TINYINT(1) NOT NULL, INDEX IDX_29A5EC274272FC9F (domaine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_commande (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, commande_id INT DEFAULT NULL, quantite INT NOT NULL, INDEX IDX_47F5946EF347EFB (produit_id), INDEX IDX_47F5946E82EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, nom_region VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, date_naissance DATE NOT NULL, adresse VARCHAR(255) DEFAULT NULL, phone VARCHAR(50) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accord ADD CONSTRAINT FK_91361A04D73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id)');
        $this->addSql('ALTER TABLE accord ADD CONSTRAINT FK_91361A04F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE domaine ADD CONSTRAINT FK_78AF0ACC98260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641082EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC274272FC9F FOREIGN KEY (domaine_id) REFERENCES domaine (id)');
        $this->addSql('ALTER TABLE produit_commande ADD CONSTRAINT FK_47F5946EF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE produit_commande ADD CONSTRAINT FK_47F5946E82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accord DROP FOREIGN KEY FK_91361A04D73DB560');
        $this->addSql('ALTER TABLE accord DROP FOREIGN KEY FK_91361A04F347EFB');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA76ED395');
        $this->addSql('ALTER TABLE domaine DROP FOREIGN KEY FK_78AF0ACC98260155');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641082EA2E54');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC274272FC9F');
        $this->addSql('ALTER TABLE produit_commande DROP FOREIGN KEY FK_47F5946EF347EFB');
        $this->addSql('ALTER TABLE produit_commande DROP FOREIGN KEY FK_47F5946E82EA2E54');
        $this->addSql('DROP TABLE accord');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE domaine');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE plat');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE produit_commande');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
