<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240608174219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE OR REPLACE FUNCTION public.check_timework_srv_stn()
                        RETURNS trigger
                        LANGUAGE 'plpgsql'
                        VOLATILE NOT LEAKPROOF
                        AS
                        $$
                        BEGIN
                            IF NEW.car_vin NOT IN
                            (
                                SELECT C.car_vin
                                FROM car C INNER JOIN station_service S ON C.stn_id = S.stn_id
                                WHERE S.stn_id IN 
                                (
                                    SELECT S.stn_id
                                    FROM station_service S
                                    WHERE '15:00:00' BETWEEN S.stn_time_begin AND S.stn_time_end
                                )
                            )
                            THEN
                                RAISE EXCEPTION 'Пункт обслуживания, в котором находится автомобиль, закрыт';
                            ELSE
                                RETURN NEW;
                            END IF;
                        END;
                        $$;");
         $this->addSql('ALTER FUNCTION public.check_timework_srv_stn()
                        OWNER TO postgres;');
        $this->addSql('CREATE OR REPLACE TRIGGER check_timework_stn
                        AFTER INSERT
                        ON public.booking
                        FOR EACH ROW
                        EXECUTE FUNCTION public.check_timework_srv_stn();');
        
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
    }
}
