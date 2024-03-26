<?php

namespace app\Core;

class Main
{
    public function start(): void
    {
        $uri = $_GET['q'];
        if (!empty($uri) && $uri !== '/' && $uri[-1] === '/') {
            $uri = substr($uri, 0, -1);

            http_response_code(301);
            header("location: /$uri");
            exit();
        }
    }
}
