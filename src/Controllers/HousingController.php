<?php

namespace Controllers;

use Models\Housing;
use Source\Renderer;

class HousingController
{
    public function home()
    {
        $housingModel = new Housing();
        $housings = $housingModel->joinAllTables();
        Renderer::view('home/home', ['housings' => $housings]);
        return;
    }
}
