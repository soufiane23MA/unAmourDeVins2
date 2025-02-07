<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250206163151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accord DROP FOREIGN KEY FK_91361A04D73DB560');
        $this->addSql('ALTER TABLE accord DROP FOREIGN KEY FK_91361A04F347EFB');
        $this->addSql('ALTER TABLE accord ADD CONSTRAINT FK_91361A04D73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE accord ADD CONSTRAINT FK_91361A04F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accord DROP FOREIGN KEY FK_91361A04D73DB560');
        $this->addSql('ALTER TABLE accord DROP FOREIGN KEY FK_91361A04F347EFB');
        $this->addSql('ALTER TABLE accord ADD CONSTRAINT FK_91361A04D73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE accord ADD CONSTRAINT FK_91361A04F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
