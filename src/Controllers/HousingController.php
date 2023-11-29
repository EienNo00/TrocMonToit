<?php

namespace Controllers;

use Models\Housing;
use Source\Renderer;

class HousingController
{
    public function home()
    {
        $housingModel = new Housing();
        $housings = $housingModel->allTables();
        // var_dump($housings);
        Renderer::view('home/home', ['housings' => $housings]);
        return;
    }
}
