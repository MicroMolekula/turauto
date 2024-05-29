<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240529111940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE booking_bkg_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE client_clt_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE manager_mng_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE station_service_stn_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE booking (bkg_id INT NOT NULL, car_vin VARCHAR(17) NOT NULL, clt_id INT NOT NULL, mng_id INT NOT NULL, stn_id INT NOT NULL, bkg_date_begin TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, bkg_date_end TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, bkg_cost INT NOT NULL, bkg_status INT CHECK (0 <= bkg_status AND bkg_status >= 2), PRIMARY KEY(bkg_id))');
        $this->addSql('COMMENT ON COLUMN booking.bkg_date_begin IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN booking.bkg_date_end IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE car (car_vin VARCHAR(17) NOT NULL, car_make VARCHAR(32) NOT NULL, car_model VARCHAR(32) NOT NULL, car_date_of_issue TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, car_state_number VARCHAR(8) NOT NULL, cls_title VARCHAR(32) NOT NULL, stn_id INT NOT NULL, car_body_type VARCHAR(64) NOT NULL, car_color VARCHAR(32) NOT NULL, car_status  INT check (car_status between 0 and 3), PRIMARY KEY(car_vin))');
        $this->addSql('COMMENT ON COLUMN car.car_date_of_issue IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE car_class (cls_title VARCHAR(32) NOT NULL, cls_coef_cost NUMERIC(2, 0) NOT NULL, cls_drv_exp NUMERIC(2, 0) NOT NULL, cls_category VARCHAR(32) NOT NULL, cls_mileage_limit INT NOT NULL, PRIMARY KEY(cls_title))');
        $this->addSql('CREATE TABLE client (clt_id INT NOT NULL, clt_surname VARCHAR(32) NOT NULL, clt_name VARCHAR(32) NOT NULL, clt_midlename VARCHAR(32) NOT NULL, clt_email VARCHAR(64) NOT NULL, clt_passport_details VARCHAR(10) NOT NULL, clt_num_drv_lic VARCHAR(10) NOT NULL, clt_drv_lic_category VARCHAR(32) NOT NULL, clt_drv_lic_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, clt_phone VARCHAR(10) NOT NULL, clt_password VARCHAR(255) NOT NULL, PRIMARY KEY(clt_id))');
        $this->addSql('COMMENT ON COLUMN client.clt_drv_lic_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE manager (mng_id INT NOT NULL, mng_surname VARCHAR(32) NOT NULL, mng_name VARCHAR(32) NOT NULL, mng_midlename VARCHAR(32) NOT NULL, mng_passport_details VARCHAR(10) NOT NULL, mng_password VARCHAR(255) NOT NULL, stn_id INT NOT NULL, PRIMARY KEY(mng_id))');
        $this->addSql('CREATE TABLE service_car (car_vin VARCHAR(17) NOT NULL, srv_type VARCHAR(32) NOT NULL, PRIMARY KEY(car_vin, srv_type))');
        $this->addSql('CREATE TABLE station_service (stn_id INT NOT NULL, stn_address VARCHAR(64) NOT NULL, stn_phone VARCHAR(10) NOT NULL, stn_time_begin TIME(0) WITHOUT TIME ZONE NOT NULL, stn_time_end TIME(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(stn_id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE booking_bkg_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE client_clt_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE manager_mng_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE station_service_stn_id_seq CASCADE');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE car_class');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE manager');
        $this->addSql('DROP TABLE service_car');
        $this->addSql('DROP TABLE station_service');
    }
}
