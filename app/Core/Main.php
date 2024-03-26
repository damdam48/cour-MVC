<?php

namespace App\Core;

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
        $Files = glob(realpath(ROOT . '/Controllers') . '/*.php');
        $Files2 = glob(realpath(ROOT . '/Controllers') . '/**/*.php');
        var_dump($Files, $Files2);
    }
}
