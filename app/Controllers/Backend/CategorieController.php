<?php

namespace App\Controllers\Backend;

use DateTime;
use App\Core\Route;
use App\Models\Categorie;
use App\Form\CategorieForm;
use App\Core\BaseController;

class CategorieController extends BaseController
{
    public function __construct(
        private Categorie $categorie = new categorie
    ){

    }
    
    #[Route ('/admin/categories', 'admin.categores.index', ['GET'])]
    public function index() : void
    {
        $_SESSION['token'] = bin2hex(random_bytes(80));

        $this->render('Backend/Categories/index.php', [
            'categories' => $this->categorie->findAll(),
            'meta' => [
                'title' => 'Administration des categores',
                'js' => [
                    '/assets/js/switchCategorie.js',
                ]
            ]

            
        ]);

    }

    #[Route('/admin/categories/create', 'admin.categories.create', ['GET', 'POST'])]
    public function create(): void
    {
        $form = new CategorieForm('/admin/categories/create');

        if ($form->validate($_POST, ['title'])) {
            $title = trim(strip_tags($_POST['title']));
            $enable = isset($_POST['enable']) ? 1 : 0;

            if (!$this->categorie->findOneBy(['title' => $title])) {
                $this->categorie
                    ->setTitle($title)
                    ->setEnable($enable)
                    ->setCreatedAt(new \DateTime())
                    ->create();

                $_SESSION['messages']['success'] = 'L\'categorie a bien été créé.';
                $this->redirect('/admin/categories');
            } else {
                $_SESSION['messages']['danger'] = 'Un categorie avec ce titre existe déjà.';
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['messages']['danger'] = 'Veuillez remplir les champs obligatoires.';
        }

        $this->render('Backend/categories/create.php', [
            'form' => $form->createView(),
            'meta' => [
                'title' => 'Création d\'un categorie',
            ]
        ]);
    }


    #[Route('/admin/categories/([0-9]+)/edit', 'admin.categories.edit', ['GET', 'POST'])]
    public function edit(int $id): void
    {
        $categorie = $this->categorie->find($id);

        if (!$categorie) {
            $_SESSION['messages']['danger'] = 'Cet categorie n\'existe pas.';
            $this->redirect('/admin/categories');
        }

        $form = new CategorieForm($_SERVER['REQUEST_URI'], $categorie);

        if ($form->validate($_POST, ['title',])) {
            $title = trim(strip_tags($_POST['title']));

            $enable = isset($_POST['enable']) ? 1 : 0;

            if ($title !== $categorie->getTitle() && $this->categorie->findOneBy(['title' => $title])) {
                $_SESSION['messages']['danger'] = 'Un categorie avec ce titre existe déjà.';
            } else {
                $categorie
                    ->setTitle($title)
                    ->setEnable($enable)
                    ->setUpdatedAt(new DateTime())
                    ->update();

                $_SESSION['messages']['success'] = 'L\'categorie a bien été modifié.';
                $this->redirect('/admin/categories');
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['messages']['danger'] = 'Veuillez remplir les champs obligatoires.';
        }

        $this->render('Backend/Categories/edit.php', [
            'form' => $form->createView(),
            'meta' => [
                'title' => 'Modification de ' . $categorie->getTitle(),
            ]
        ]);
    }


    #[Route('/admin/categories/([0-9]+)/switch', 'admin.categories.swith', ['GET'])]
    public function swith(int $id): void
    {
        header('Content-Type:application/json');

        $categorie = $this->categorie->find($id);

        if (!$categorie) {
            http_response_code(404);
            echo json_encode([
                'status' => 404,
                'message' => "categorie non trouvé",
            ]);
            exit;
        }
        $categorie
            ->setEnable(!$categorie->getEnable())
            ->update();

            http_response_code(201);

        echo json_encode([
            'status' => 201,
            'message' => 'Visibility changed',
            'enable' => (bool) $categorie->getEnable(),
        ]);
    }

    #[Route('/admin/categories/delete', 'admin.categories.delete', ['POST'])]
    public function delete(): void
    {
        $categorie = $this->categorie->find(!empty($_POST['id']) ? $_POST['id'] : 0);

        if ($categorie) {
            if (hash_equals($_SESSION['token'], $_POST['token'])) {
                $categorie->delete();
                $_SESSION['messages']['success'] = 'L\'categorie a bien été supprimé.';
            } else {
                $_SESSION['messages']['danger'] = 'Jeton de sécurité invalide.';
            }
        } else {
            $_SESSION['messages']['danger'] = 'Cet categorie n\'existe pas.';
        }

        $this->redirect('/admin/categories');
    }



}

