<?php

namespace App\Core;

use App\Core\Route;
use ErrorException;

class Main
{

    public function __construct(
        private Routeur $routeur = new Routeur(),

    ) {
    }

    public function start(): void
    {
        $uri = $_GET['q'];
        if (!empty($uri) && $uri !== '/' && $uri[-1] === '/') {
            $uri = substr($uri, 0, -1);

            http_response_code(301);
            header("location: /$uri", true);
            exit();
        }

        $this->initRoute();

        $this->routeur->handle($uri, $_SERVER['REQUEST_METHOD']);
    }

    private function initRoute(): void
    {
        // on recupere dynamiquement tous les fichiers dans le dossiers controllers
        $Files = glob(realpath(ROOT . '/Controllers') . '/*.php');
        $Files2 = glob(realpath(ROOT . '/Controllers') . '/**/*.php');

        $Files = array_merge_recursive($Files, $Files2);

        // on boucle sur tous les fichiers controllers
        foreach ($Files as $file) {
            // on veut transformer le chemein du fichier en Namespace
            $file = substr($file, 1);
            $file = str_replace('/', '\\', $file);
            $file = str_replace('.php', '', $file);

            $class = ucfirst($file);


            if (!class_exists($class)) {
                throw new ErrorException("la class $class n'exite pas, vérifier le namespace ou le nom du fichier");
            }

            $methods = get_class_methods($class);

            // on boucle sur les méthodes
            foreach ($methods as $method) {
                $attributes = (new \ReflectionMethod($class, $method))->getAttributes(Route::class);

                foreach ($attributes as $attribute) {
                    $route = $attribute->newInstance();

                    $route->setController($class)
                        ->setAction($method);
                    $this->routeur->addRoute([
                        'url' => $route->getUrl(),
                        'name' => $route->getName(),
                        'controller' => $route->getController(),
                        'action' => $route->getAction(),
                        'methods' => $route->getMethod(),
                    ]);
                    var_dump($route);
                }
            }
        }
    }
}
