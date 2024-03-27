<?php

namespace App\Controllers\Security;

use App\Core\Route;
use App\Core\BaseController;
use App\Form\LoginForm;

class SecurityController extends BaseController
{
    #[Route('/login', 'app.login', ['GET', 'POST'])]
    public function login(): void
    {
        $form = new LoginForm;

        


        $this->render('Security/login.php', [
            'form' => $form->createView(),
            'meta' => [
                'title' => 'Se connecter',
            ]

        ]);
    }
}
