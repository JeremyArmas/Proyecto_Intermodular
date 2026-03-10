<?php

namespace App\DTO;

class CategoryData
{
    // Constructor para inicializar las propiedades de la categoría
    public function __construct(
        public string $name,
        public string $slug,
    ) {}
}