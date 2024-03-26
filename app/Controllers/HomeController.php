<?php

namespace App\Controllers;

class HomeController
{
    #[Route('/', 'app.home', ['GET'])]
    public function index(): void
    {
        echo 'Page d\'accueil';
    }
}
