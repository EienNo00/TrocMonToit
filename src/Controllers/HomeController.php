<?php

namespace Controllers;

use Models\User;
use Source\Renderer;

class HomeController
{
    private string $res;

    public function index()
    {
        $userModel = new User();
        $users = $userModel->all();
        Renderer::view('home/index');
        return;
    }
}
