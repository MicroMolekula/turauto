<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240529124729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D72DF04D1 FOREIGN KEY (stn_id) REFERENCES station_service (stn_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DC9EFE3D9 FOREIGN KEY (cls_title) REFERENCES car_class (cls_title) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_773DE69D72DF04D1 ON car (stn_id)');
        $this->addSql('CREATE INDEX IDX_773DE69DC9EFE3D9 ON car (cls_title)');
        $this->addSql('ALTER TABLE manager ADD CONSTRAINT FK_FA2425B972DF04D1 FOREIGN KEY (stn_id) REFERENCES station_service (stn_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_FA2425B972DF04D1 ON manager (stn_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69D72DF04D1');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69DC9EFE3D9');
        $this->addSql('DROP INDEX IDX_773DE69D72DF04D1');
        $this->addSql('DROP INDEX IDX_773DE69DC9EFE3D9');
        $this->addSql('ALTER TABLE manager DROP CONSTRAINT FK_FA2425B972DF04D1');
        $this->addSql('DROP INDEX IDX_FA2425B972DF04D1');
    }
}
