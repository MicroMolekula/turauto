<?php

namespace App\DTO;

class CarsPage
{
    function __construct(
       public array $pages,
       public array $cars,
    ) {
    }
}