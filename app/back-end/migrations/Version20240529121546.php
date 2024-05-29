<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240529121546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking ALTER bkg_status SET NOT NULL');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEBAAF71F1 FOREIGN KEY (car_vin) REFERENCES car (car_vin) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E00CEDDEBAAF71F1 ON booking (car_vin)');
        $this->addSql('ALTER TABLE car ALTER car_status SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE car ALTER car_status DROP NOT NULL');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDEBAAF71F1');
        $this->addSql('DROP INDEX IDX_E00CEDDEBAAF71F1');
        $this->addSql('ALTER TABLE booking ALTER bkg_status DROP NOT NULL');
    }
}
