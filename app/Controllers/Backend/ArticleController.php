<?php

namespace App\Controllers\Backend;

use App\Core\BaseController;
use App\Core\Route;
use App\Form\ArticleForm;
use App\Models\Article;

class ArticleController extends BaseController
{
    public function __construct(
        private Article $article = new Article
    ) {
    }

    #[Route('/admin/articles', 'admin.articles.index', ['GET'])]
    public function index(): void
    {
        $this->render('Backend/Articles/index.php', [
            'articles' => $this->article->findAll(),
            'meta' => [
                'title' => 'Administration des articles'
            ]
        ]);
    }



    #[Route('/admin/articles/create', 'admin.articles.create', ['GET', 'POST'])]
    public function create(): void
    {
        $form = new ArticleForm('/admin/articles/create');

        if ($form->validate($_POST, ['title', 'content'])) {
            $title = trim(strip_tags($_POST['title']));
            $content = trim(strip_tags($_POST['content']));
            $enable = isset($_POST['enable']) ? 1 : 0;

            if (!$this->article->findOneBy(['title' => $title])) {
                $this->article
                    ->setTitle($title)
                    ->setContent($content)
                    ->setEnable($enable)
                    ->setCreatedAt(new \DateTime())
                    ->create();

                $_SESSION['messages']['success'] = 'L\'article a bien été créé.';
                $this->redirect('/admin/articles');
            } else {
                $_SESSION['messages']['danger'] = 'Un article avec ce titre existe déjà.';
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['messages']['danger'] = 'Veuillez remplir les champs obligatoires.';
        }

        $this->render('Backend/Articles/create.php', [
            'form' => $form->createView(),
            'meta' => [
                'title' => 'Création d\'un article',
            ]
        ]);
    }

    

    #[Route('/admin/articles/([0-9]+)/edit', 'admin.articles.edit', ['GET', 'POST'])]
    public function edit(int $id): void
    {
        $article = $this->article->find($id);

        if (!$article) {
            $_SESSION['messages']['danger'] = "article non trouvé";

            $this->redirect('/admin/articles');
        }

        $form = new ArticleForm($_SERVER['REQUEST_URI'], $article);


        if ($form->validate($_POST, ['title', 'content'])) {
            $title = trim(strip_tags($_POST['title']));
            $content = trim(strip_tags($_POST['content']));
            $enable = isset($_POST['enable']) ? 1 : 0;


            if (!$this->article->findOneBy(['title' => $title])) {
                $article
                    ->setTitle($title)
                    ->setContent($content)
                    ->setEnable($enable)
                    ->setUpdatedAt(new \DateTime())
                    ->update();


                $_SESSION['messages']['success'] = 'L\'article a bien été mofdifier.';
                $this->redirect('/admin/articles');
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['messages']['danger'] = 'Veuillez remplir les champs obligatoires.';
        }

        $this->render('Backend/Articles/edit.php', [
            'form' => $form->createView(),
            'meta' => [
                'title' => 'Update d\'un article',
            ]
        ]);
    }

    #[Route('/admin/articles/delete', 'admin.users.delete', ['POST'])]
    public function delete(): void
    {
        $article = $this->article->find(!empty($_POST['id']) ? $_POST['id'] : 0);

        if (!$article) {
            $_SESSION['messages']['danger'] = 'Article not found';

            $this->redirect('/admin/articles');
        }

        if (hash_equals($_SESSION['token'], !empty($_POST['token']) ? $_POST['token'] : '')) {
            $article->delete();

            $_SESSION['messages']['success'] = "Article supprimé avec succès";
        } else {
            $_SESSION['messages']['danger'] = "Invalide token CSRF";
        }

        $this->redirect('/admin/articles');
    }
}
