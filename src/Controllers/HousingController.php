<?php

namespace Controllers;

use Models\Housing;
use Source\Renderer;

class HousingController
{
    public function home()
    {
        $housingModel = new Housing();
        $typeFilter = $_GET['types'] ?? null;
        // var_dump($typeFilter);
        $housings = $housingModel->joinAllTables($typeFilter);
        $types = $housingModel->getDistinctType();
        // var_dump($types);
        // var_dump($housings);
        Renderer::view('home/home', ['housings' => $housings, 'types' => $types]);
        return;
    }
}
