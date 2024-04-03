<?php

namespace App\Form;

use App\Core\Form;
use App\Models\Categorie;


class CategorieForm extends Form
{
    public function __construct(string $action, ? Categorie $categorie = null)
    {
        $this
            ->startForm($action, 'POST', [
                'class' => 'card p-3 w-75 mx-auto',
            ])
            ->startDiv(['class' => 'mb-3'])
            ->addLabel('title', 'Title', ['class' => 'form-label'])
            ->addInput('text', 'title', [
                'class' => 'form-control',
                'id' => 'title',
                'placeholder' => 'Entrer un titre',
                'value' => $categorie ? $categorie->getTitle() : null,
            ])
            ->endDiv()
            ->endDiv();
        $this->startDiv(['class' => 'mb-3 form-check'])
            ->addInput('checkbox', 'enable', [
                'class' => 'form-check-input',
                'checked' => $categorie ? (bool) $categorie->getEnable() : null,
                'id' => 'enable'
            ])
            ->addLabel('enable', 'Actif', ['class' => 'form-check-label'])
            ->endDiv()
            ->addButton($categorie ? 'Modifier' : 'Créer', ['class' => 'btn btn-primary'])
            ->endForm();
    }
}
