<?php

namespace App\Models;
use App\Models\Model;

class User extends Models
{
    public function __construct()
    {
        $this->table = 'users';
    }
}