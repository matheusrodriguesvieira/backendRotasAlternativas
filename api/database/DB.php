<?php
class DB
{
    public static function connect()
    {

        $host = 'ep-fancy-haze-53308299.us-east-2.aws.neon.tech';
        $base = 'escala';
        $user = 'matheusrodriguespgm';
        $pass = 'Vb8yEXQAOvf9';
        $sslmode = 'require';
        $options = 'endpoint=ep-fancy-haze-53308299';

        return new PDO("pgsql:host=$host;dbname=$base;user=$user;password=$pass;sslmode=$sslmode;options=$options");
    }
}
