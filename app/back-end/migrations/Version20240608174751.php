<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240608174751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE OR REPLACE FUNCTION public.update_car_status_end_bkg()
                        RETURNS trigger
                        LANGUAGE 'plpgsql'
                        VOLATILE NOT LEAKPROOF
                        AS
                        $$
                        BEGIN
                            UPDATE car C SET
                            car_status = 2
                            WHERE C.car_vin = NEW.car_vin AND (NEW.bkg_status = 0 OR NEW.bkg_status = 2);
                            RETURN NEW;
                        END;
                        $$;");
        $this->addSql('ALTER FUNCTION public.update_car_status_end_bkg()
                        OWNER TO postgres;');
        $this->addSql('CREATE OR REPLACE TRIGGER update_car_status_end
                        AFTER UPDATE OF bkg_status
                        ON public.booking
                        FOR EACH ROW
                        EXECUTE FUNCTION public.update_car_status_end_bkg();');
        $this->addSql("CREATE OR REPLACE FUNCTION public.update_car_status_new_bkg()
                        RETURNS trigger
                        LANGUAGE 'plpgsql'
                        VOLATILE NOT LEAKPROOF
                        AS
                        $$
                        BEGIN
                            UPDATE car C SET
                            car_status = NEW.bkg_status
                            WHERE C.car_vin = NEW.car_vin;
                            RETURN NEW;
                        END;
                        $$;");
        $this->addSql('ALTER FUNCTION public.update_car_status_new_bkg()
                        OWNER TO postgres;');
        $this->addSql('CREATE OR REPLACE TRIGGER update_car_status_new
                        AFTER INSERT
                        ON public.booking
                        FOR EACH ROW
                        EXECUTE FUNCTION public.update_car_status_new_bkg();');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
    }
}
