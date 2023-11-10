<?php
class DB
{
    public static function connect()
    {
        $host = 'sql204.infinityfree.com';
        $user = 'if0_35366081';
        $base = 'if0_35366081_escala';
        $password = 'spOPu4NV5h';

        return new PDO("mysql:host={$host};dbname={$base};charset=UTF8;", $user, $password);
    }
}
