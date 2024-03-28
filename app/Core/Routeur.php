<?php

namespace App\Core;

class Routeur
{
    private const ADMIN_PATH = '/admin';
    private const REDIRECT_LOGIN_PATH = '/login';
    public function __construct(
        private array $routes = []
    ) {
    }



    /**
     * ajoute une route a notre liste de route dans le routeur
     *
     * @param array $route
     * @return self
     */
    public function addRoute(array $route): self
    {
        $this->routes[] = $route;
        return $this;
    }

    public function handle(string $url, string $method): void
    {

        if (preg_match('~^' . self::ADMIN_PATH . '~', $url)) {
            if (empty($_SESSION['user']) || !in_array('ROLE_ADMIN', $_SESSION['user']['roles'])) {

                $_SESSION['messages']['danger'] = "Vous n'avez pas les droits pour accéder a cette page";

                http_response_code(403);
                header('Location: ' . self::REDIRECT_LOGIN_PATH);
                exit();
            }
        }

        // on boucle sur le tableau des routes disponibles
        foreach ($this->routes as $route) {
            if (
                preg_match("#^" . $route['url'] . "$#", $url, $matches)
                && in_array($method, $route['methods'])

            ) {

                // on recupere le nom du controleur dans la route
                $controller = $route['controller'];

                // on recupere le nom de la method dans la route
                $action = $route['action'];

                $controller = new $controller();

                //on recupere les parametre potensielle de url
                $params = array_slice($matches, 1);


                // on execute la methode dans le controller
                $controller->$action(...$params);

                return;
            }
        }

        http_response_code(404);
        echo "<h1> Page not found</h1>";
        exit();
    }
}
