<?php

use App\Autoloader;
use App\Models\User;

require_once '/app/Autoloader.php';
Autoloader::register();



$user = (new User)->find(4);

$user->delete();

var_dump($user);








?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Super chat vert</h1>
</body>

</html>