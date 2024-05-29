<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240529122954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE1E3D6DF2 FOREIGN KEY (clt_id) REFERENCES client (clt_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE1C5BA793 FOREIGN KEY (mng_id) REFERENCES manager (mng_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE72DF04D1 FOREIGN KEY (stn_id) REFERENCES station_service (stn_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E00CEDDE1E3D6DF2 ON booking (clt_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE1C5BA793 ON booking (mng_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE72DF04D1 ON booking (stn_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE1E3D6DF2');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE1C5BA793');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE72DF04D1');
        $this->addSql('DROP INDEX IDX_E00CEDDE1E3D6DF2');
        $this->addSql('DROP INDEX IDX_E00CEDDE1C5BA793');
        $this->addSql('DROP INDEX IDX_E00CEDDE72DF04D1');
    }
}
