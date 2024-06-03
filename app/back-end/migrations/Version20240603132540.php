<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240603132540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE add_service (srv_type VARCHAR(32) NOT NULL, srv_cost INT NOT NULL, PRIMARY KEY(srv_type))');
        $this->addSql('CREATE TABLE service_car (srv_type VARCHAR(32) NOT NULL, car_vin VARCHAR(17) NOT NULL, PRIMARY KEY(srv_type, car_vin))');
        $this->addSql('CREATE INDEX IDX_556D95EAF6A8B6EB ON service_car (srv_type)');
        $this->addSql('CREATE INDEX IDX_556D95EABAAF71F1 ON service_car (car_vin)');
        $this->addSql('CREATE TABLE booking (bkg_id INT NOT NULL, car_vin VARCHAR(17) NOT NULL, clt_id INT NOT NULL, mng_id INT NOT NULL, stn_id INT NOT NULL, bkg_date_begin TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, bkg_date_end TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, bkg_cost INT NOT NULL, bkg_status INT CHECK (0 <= bkg_status AND bkg_status >= 2), PRIMARY KEY(bkg_id))');
        $this->addSql('CREATE INDEX IDX_E00CEDDEBAAF71F1 ON booking (car_vin)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE1E3D6DF2 ON booking (clt_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE1C5BA793 ON booking (mng_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE72DF04D1 ON booking (stn_id)');
        $this->addSql('COMMENT ON COLUMN booking.bkg_date_begin IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN booking.bkg_date_end IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE car (car_vin VARCHAR(17) NOT NULL, cls_title VARCHAR(32) NOT NULL, stn_id INT NOT NULL, car_make VARCHAR(32) NOT NULL, car_model VARCHAR(32) NOT NULL, car_date_of_issue TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, car_state_number VARCHAR(8) NOT NULL, car_body_type VARCHAR(64) NOT NULL, car_color VARCHAR(32) NOT NULL, car_status  INT check (car_status between 0 and 3), PRIMARY KEY(car_vin))');
        $this->addSql('CREATE INDEX IDX_773DE69D72DF04D1 ON car (stn_id)');
        $this->addSql('CREATE INDEX IDX_773DE69DC9EFE3D9 ON car (cls_title)');
        $this->addSql('COMMENT ON COLUMN car.car_date_of_issue IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE car_class (cls_title VARCHAR(32) NOT NULL, cls_coef_cost NUMERIC(2, 0) NOT NULL, cls_drv_exp NUMERIC(2, 0) NOT NULL, cls_category VARCHAR(32) NOT NULL, cls_mileage_limit INT NOT NULL, PRIMARY KEY(cls_title))');
        $this->addSql('CREATE TABLE client (clt_id INT NOT NULL, clt_surname VARCHAR(32) NOT NULL, clt_name VARCHAR(32) NOT NULL, clt_midlename VARCHAR(32) NOT NULL, clt_email VARCHAR(64) NOT NULL, clt_passport_details VARCHAR(10) NOT NULL, clt_num_drv_lic VARCHAR(10) NOT NULL, clt_drv_lic_category VARCHAR(32) NOT NULL, clt_drv_lic_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, clt_phone VARCHAR(10) NOT NULL, clt_password VARCHAR(255) NOT NULL, PRIMARY KEY(clt_id))');
        $this->addSql('COMMENT ON COLUMN client.clt_drv_lic_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE manager (mng_id INT NOT NULL, stn_id INT NOT NULL, mng_surname VARCHAR(32) NOT NULL, mng_name VARCHAR(32) NOT NULL, mng_midlename VARCHAR(32) NOT NULL, mng_passport_details VARCHAR(10) NOT NULL, mng_password VARCHAR(255) NOT NULL, PRIMARY KEY(mng_id))');
        $this->addSql('CREATE INDEX IDX_FA2425B972DF04D1 ON manager (stn_id)');
        $this->addSql('CREATE TABLE station_service (stn_id INT NOT NULL, stn_address VARCHAR(64) NOT NULL, stn_phone VARCHAR(10) NOT NULL, stn_time_begin TIME(0) WITHOUT TIME ZONE NOT NULL, stn_time_end TIME(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(stn_id))');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE service_car ADD CONSTRAINT FK_556D95EAF6A8B6EB FOREIGN KEY (srv_type) REFERENCES add_service (srv_type) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service_car ADD CONSTRAINT FK_556D95EABAAF71F1 FOREIGN KEY (car_vin) REFERENCES car (car_vin) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEBAAF71F1 FOREIGN KEY (car_vin) REFERENCES car (car_vin) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE1E3D6DF2 FOREIGN KEY (clt_id) REFERENCES client (clt_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE1C5BA793 FOREIGN KEY (mng_id) REFERENCES manager (mng_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE72DF04D1 FOREIGN KEY (stn_id) REFERENCES station_service (stn_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D72DF04D1 FOREIGN KEY (stn_id) REFERENCES station_service (stn_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DC9EFE3D9 FOREIGN KEY (cls_title) REFERENCES car_class (cls_title) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE manager ADD CONSTRAINT FK_FA2425B972DF04D1 FOREIGN KEY (stn_id) REFERENCES station_service (stn_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE service_car DROP CONSTRAINT FK_556D95EAF6A8B6EB');
        $this->addSql('ALTER TABLE service_car DROP CONSTRAINT FK_556D95EABAAF71F1');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDEBAAF71F1');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE1E3D6DF2');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE1C5BA793');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE72DF04D1');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69D72DF04D1');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69DC9EFE3D9');
        $this->addSql('ALTER TABLE manager DROP CONSTRAINT FK_FA2425B972DF04D1');
        $this->addSql('DROP TABLE add_service');
        $this->addSql('DROP TABLE service_car');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE car_class');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE manager');
        $this->addSql('DROP TABLE station_service');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
