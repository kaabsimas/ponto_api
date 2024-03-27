<?php

namespace Ponto\Database;

use PDO;

class Connection
{
    public function connect()
    {
        return new PDO("mysql://fg-user:my_cool_secret@127.0.0.1:3306/app_ponto", "", "", [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ]);
    }
}