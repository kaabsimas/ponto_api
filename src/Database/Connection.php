<?php

namespace Ponto\Database;

use PDO;

class Connection
{
    public function connect()
    {
        return new PDO("mysql:host=localhost:3306;dbname=app_ponto", "fg-user", "my_cool_secret", [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ]);
    }
}