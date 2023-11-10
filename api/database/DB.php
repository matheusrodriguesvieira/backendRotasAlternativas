<?php
class DB
{
    public static function connect()
    {
        $host = 'ep-cool-darkness-123456.us-east-2.aws.neon.tech';
        $user = 'matheusrodriguespgm';
        $base = 'escala';
        $password = 'Vb8yEXQAOvf9';

        return new PDO("mysql:host={$host};dbname={$base};charset=UTF8;", $user, $password);
    }
}
