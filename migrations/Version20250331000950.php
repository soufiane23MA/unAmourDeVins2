<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250331000950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande ADD user_nom VARCHAR(255) NOT NULL, ADD user_prenom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE commande RENAME INDEX numero_commande TO UNIQ_6EEAA67DCFFD611D');
        $this->addSql('ALTER TABLE user ADD is_anonymized TINYINT(1) NOT NULL, ADD anonymized_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP user_nom, DROP user_prenom');
        $this->addSql('ALTER TABLE commande RENAME INDEX uniq_6eeaa67dcffd611d TO numero_commande');
        $this->addSql('ALTER TABLE user DROP is_anonymized, DROP anonymized_at');
    }
}
