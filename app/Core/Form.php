<?php

namespace App\Core;

abstract class Form
{
    /**
     * stock le code HTML du formulaire enb chaine de caractere
     *
     * @var string
     */
    protected string $formCode = '';


    /**
     * Validation du formulaire (si tous les champs sont remplis)
     *
     * @param array $form Tableau issu du formulaire ($_POST || $_GET)
     * @param array $champs Tableau listant les champs obligatoires (['email', 'password'])
     * @return bool
     */
    public function validate(array $form, array $champs): bool
    {
        // On boucle sur le tableau de champs obligateur

        foreach ($champs as $champ) {
            if (empty($form[$champ]) || strlen(trim($form[$champ])) === 0) {
                return false;
            }
        }
        // le formulaire n'ai pas vide on envoie true
        return true;
    }
}
