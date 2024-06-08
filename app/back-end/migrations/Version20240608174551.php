<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240608174551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE OR REPLACE PROCEDURE public.add_new_booking(
                                car_vin_in char,
                                client_id_in integer,
                                count_days integer
                            )
                            LANGUAGE 'plpgsql'
                            AS
                            $$
                            DECLARE
                                total_cost integer;
                                stn_id_in integer;
                                mng_id_in integer;
                                bkg_date_begin_in timestamp;
                                bkg_date_end_in timestamp;
                            BEGIN
                                IF car_vin_in NOT IN (
                                SELECT car_vin
                                FROM car
                                WHERE car_status = 2
                                )
                                THEN RAISE EXCEPTION 'Машина занята';
                                END IF;
                                
                                IF client_id_in NOT IN (
                                SELECT CLT.clt_id
                                FROM car C
                                INNER JOIN car_class CC ON C.cls_title = CC.cls_title, client CLT
                                WHERE C.car_vin = car_vin_in AND
                                DATE_PART('year', CURRENT_DATE) - DATE_PART('year', CLT.clt_drv_lic_date) >= CC.cls_drv_exp AND
                                STRPOS(CLT.clt_drv_lic_category, CC.cls_category) > 0
                                )
                                THEN RAISE EXCEPTION 'Данная машина не доступна для клиента';
                                END IF;
                                
                                total_cost := (
                                SELECT  ((CC.cls_coef_cost*2000*5) + COALESCE(TS.total_for_add_options, 0)) as total_cost
                                FROM car C 
                                INNER JOIN car_class CC ON C.cls_title = CC.cls_title
                                INNER JOIN 
                                (
                                    SELECT C.car_vin, SUM(SA.srv_cost) as total_for_add_options
                                    FROM car C
                                    LEFT JOIN service_car SC ON SC.car_vin = C.car_vin
                                    LEFT JOIN add_service SA ON SA.srv_type = SC.srv_type
                                    GROUP BY C.car_vin
                                ) TS ON C.car_vin = TS.car_vin
                                WHERE C.car_vin = car_vin_in
                                );
                                
                                stn_id_in := (
                                SELECT S.stn_id
                                FROM station_service S INNER JOIN car C ON C.stn_id = S.stn_id
                                WHERE C.car_vin = car_vin_in
                                );
                                
                                mng_id_in := (
                                SELECT M.mng_id
                                FROM manager M
                                WHERE M.stn_id = stn_id_in
                                );
                                
                                bkg_date_begin_in := NOW();
                                
                                bkg_date_end_in := (NOW()::date  + 5)::timestamp + NOW()::time;
                                
                                
                                INSERT INTO booking
                                (car_vin, clt_id, mng_id, stn_id, bkg_date_begin, bkg_date_end, bkg_cost, bkg_status)
                                VALUES
                                (car_vin_in, client_id_in, mng_id_in, stn_id_in, bkg_date_begin_in, bkg_date_end_in, total_cost, 1);
                            END;
                            $$;");
        $this->addSql('ALTER PROCEDURE public.add_new_booking(char, integer, integer)
                        OWNER TO postgres;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
    }
}
