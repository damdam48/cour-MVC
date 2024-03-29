<?php

namespace App\Form;

use App\Core\Form;
use App\Models\Article;

class ArticleForm extends Form
{
    public function __construct(string $action, ?Article $article = null)
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
                'value' => $article ? $article->getTitle() : null,
            ])
            ->endDiv()
            ->startDiv(['class' => 'mb-3'])
            ->addLabel('content', 'Content', ['class' => 'form-label'])
            ->addTextarea(
                'content',
                [
                    'class' => 'form-control',
                    'id' => 'content',
                    'placeholder' => 'Entrer un contenu',
                    'rows' => '5',
                ],
                $article ? $article->getContent() : null
            )
            ->endDiv()
            ->startDiv(['class' => 'mb-3 form-check'])
            ->addInput('checkbox', 'enable', [
                'class' => 'form-check-input',
                'checked' => $article ? (bool) $article->getEnable() : null,
                'id' => 'enable'
            ])
            ->addLabel('enable', 'Actif', ['class' => 'form-check-label'])
            ->endDiv()
            ->addButton($article ? 'Modifier' : 'Créer', ['class' => 'btn btn-primary'])
            ->endForm();
    }
}
