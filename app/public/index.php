<?php

use App\Autoloader;
use App\Models\Article;
use App\Models\User;
use App\Core\main;
define('ROOT', dirname(__DIR__));

require_once  ROOT . '/Autoloader.php';
Autoloader::register();

// var_dump($_GET);
// var_dump(ROOT);


// $article = (new Article)
//     ->setTitle('Super article 2')
//     ->setContent('Génial 2')
//     ->setEnable(true)
//     ->create();

//on instance la class Main
$app = new Main();

// on demarre l'application
$app->start();
