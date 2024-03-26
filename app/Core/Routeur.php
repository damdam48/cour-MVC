<?php

namespace App\Core;

class Routeur
{
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
        // on boucle sur le tableau des routes disponibles
        foreach ($this->routes as $route) {
            if ($route['url'] === $url && in_array($method, $route['methods'])) {
                // on recupere le nom du controleur dans la route
                $controller = $route['controller'];

                // on recupere le nom de la method dans la route
                $action = $route['action'];

                $controller = new $controller();
                // on execute la methode dans le controller
                $controller->$action();

                return;
            }
        }

        http_response_code(404);
        echo "<h1> Page not found</h1>";
        exit();
    }
}
