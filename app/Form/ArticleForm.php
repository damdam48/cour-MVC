<?php

namespace App\Form;

use App\Core\Form;
use App\Models\Article;
use App\Models\User;

class ArticleForm extends Form
{
    public function __construct(string $action, ?Article $article = null, private User $user = new User)
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
            ->endDiv();

        if ($article) {
            $this
                ->startDiv(['class' => 'mb-3'])
                ->addLabel('user', 'Auteur', ['class' => 'form-label'])
                ->addSelect(
                    'user',
                    $this->user->findForSelect($article ? $article->getUserId() : null),
                    [
                        'class' => 'form-control',
                        'id' => 'user',
                    ]
                )
                ->endDiv();
        }

        $this->startDiv(['class' => 'mb-3 form-check'])
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
