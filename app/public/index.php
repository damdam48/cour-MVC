<?php

use App\Autoloader;
use App\Core\Main;

define('ROOT', dirname(__DIR__));

require_once ROOT . '/Autoloader.php';

Autoloader::register();

// On instancie la class Main
$app = new Main();

// On démarre l'application
$app->start();
