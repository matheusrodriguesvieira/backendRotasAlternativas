<?php
class DB
{
    public static function connect()
    {

        $host = getenv('DB_HOST');
        $base = getenv('DB_BASE');
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASS');
        $sslmode = getenv('DB_SSLMODE');
        $options = getenv('DB_OPTIONS');



        return new PDO("pgsql:host=$host;dbname=$base;user=$user;password=$pass;sslmode=$sslmode;options=$options");
    }
}
