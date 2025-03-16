<?php
namespace App\DTO;

class CarsResponse
{
    public function __construct(
        public string $id,
        public string $mark,
        public string $model,
        public string $date,
        public string $state_number,
        public string $cls_title,
        public string $image,
        public string $station_service,
        public string $body_type,
        public string $color,
        public string $status,
        public string $gearbox,
        public array $add_services,
    ) {
    }
}