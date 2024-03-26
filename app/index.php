<?php

use App\Autoloader;
use App\Models\User;

require_once '/app/Autoloader.php';
Autoloader::register();



$user = (new User)->find(1);

$user = (new User)->hydrate($user);
$user->setPassword(
    password_hash('1234', PASSWORD_ARGON2I)
)
    ->update();

    
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