<?php
/**
 * 获取环境变量
 *
 * @param string $name
 * @param mixed $value
 * @return mixed
 */
function env($name = '', $default = null)
{
    static $env = null;
    if (null === $env) {
        $env_file = __DIR__ . '/.env';
        $env = file_exists($env_file) ? parse_ini_file($env_file, true) : [];
    }

    if ('' === $name) return $env;

    $value = $env;
    foreach(explode('.', $name) as $key) {
        if (!array_key_exists($key, $value)) return $default;
        $value = $value[$key];
    }

    return $value;
}

/**
 * 获取 PDO 实例
 *
 * @return \PDO
 */
function getPdo()
{
    $dsn = 'mysql:dbname=' . env('database.name', 'tdp') .
        ';host=' . env('database.host', 'localhost') .
        ';port=' . env('database.port', '3306');
    $pdo = new \PDO($dsn, env('database.user', 'tdp'), env('database.pass', ''));
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);

    return $pdo;
}