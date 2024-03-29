<?php

namespace App\Core;

use ErrorException;
use ReflectionMethod;

/**
 * Point d'entée de notre application
 */
class Main
{
    public function __construct(
        private Routeur $routeur = new Routeur(),
    ) {
    }

    public function start(): void
    {
        session_start();

        $uri = $_SERVER['REQUEST_URI'];

        // On verifie que l'URI n'est pas vide
        if (!empty($uri) && $uri !== '/' && $uri[-1] === '/') {

            // On enleve le dernier /
            $uri = substr($uri, 0, -1);

            // On envoie un code de redirection permanente
            http_response_code(301);

            // On redirige vers l'URL sans le dernier /
            header('Location: ' . $uri);
            exit;
        }

        $this->initRouter();

        $this->routeur->handle($uri, $_SERVER['REQUEST_METHOD']);
    }

    private function initRouter(): void
    {
        // On récupère dynamiquement tous les fichiers dans le dossiers controllers
        $files =  glob(realpath(ROOT . '/Controllers') . '/*.php');
        $files2 = glob(realpath(ROOT . '/Controllers') . '/**/*.php');

        $files = array_merge_recursive($files, $files2);

        // On boucle sur tous les fichiers controllers
        foreach ($files as $file) {
            // On veut transformer le chemin du fichier en Namespace
            $file = substr($file, 1);
            $file = str_replace('/', '\\', $file);
            $file = str_replace('.php', '', $file);

            $class = ucfirst($file);

            // On vérifie que la classe existe
            if (!class_exists($class)) {
                throw new ErrorException("La class $class n'existe pas, vérifier le namespace ou le nom du fichier");
            }

            // On récupère toutes les méthodes de la classe
            $methods = get_class_methods($class);

            // On boucle sur les méthodes
            foreach ($methods as $method) {
                $attributes = (new ReflectionMethod($class, $method))->getAttributes(Route::class);

                foreach ($attributes as $attribute) {
                    $route = $attribute->newInstance();
                    $route->setController($class)
                        ->setAction($method);

                    $this->routeur->addRoute([
                        'url' => $route->getUrl(),
                        'name' => $route->getName(),
                        'controller' => $route->getController(),
                        'action' => $route->getAction(),
                        'methods' => $route->getMethods(),
                    ]);
                }
            }
        }
    }
}
