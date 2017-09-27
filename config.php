<?php

session_start();

$appName = 'Vote4Music';

switch ($_SERVER['HTTP_HOST']) {
    case 'localhost':
        $appPath = '/Vote4Music_Simple/';
        $sqlHost = 'localhost';
        $sqlDB = 'vote4music';
        $sqlUser = 'varazdinac';
        $sqlPw = '123';
        break;

    case 'vote4music.byethost7.com':
        $appPath = '/Vote4Music_Simple/';
        $sqlHost = 'sql108.byethost7.com';
        $sqlDB = 'b7_20112054_vote4music';
        $sqlUser = 'b7_20112054';
        $sqlPw = 'p76qxry3';
        break;

    default:
        $appPath = '/';
        break;
}

try {
    $conn = new PDO("mysql:host=" . $sqlHost . ";dbname=" . $sqlDB,$sqlUser,$sqlPw);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $conn->exec("SET CHARACTER SET utf8");
    $conn->exec("SET NAMES utf8");
} catch (PDOException $e) {
    switch ($e->getCode()) {
        case 2002:
            echo "Can't connect to MySQL server";
            break;

        case 1049:
            echo "Database does not exist";
            break;

        case 1045:
            echo "Combination of username and password does not exist in MySQL server";
            break;

        default:
            print_r($e);
            break;
    }
    exit;
}
