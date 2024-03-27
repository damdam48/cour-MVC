<?php

namespace App\Controllers\Security;

use App\Core\Route;
use App\Models\User;
use App\Form\LoginForm;
use App\Core\BaseController;

class SecurityController extends BaseController
{
    #[Route('/login', 'app.login', ['GET', 'POST'])]
    public function login(): void
    {
        $form = new LoginForm('/login');

        if ($form->validate($_POST, ['email', 'password'])) {
            $user = (new User)->findByEmail($_POST['email']);

            if ($user && password_verify($_POST['password'], $user->getPassword())) {
                $user->login();

                $this->redirect('/');
            } else {
                $_SESSION['messages']['danger'] = "Identifiants invalide";
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['messages']['danger'] = "Veuillez remplir tous les champs obligatoires";
        }




        $this->render('Security/login.php', [
            'form' => $form->createView(),
            'meta' => [
                'title' => 'Se connecter',
            ]

        ]);
    }
}
