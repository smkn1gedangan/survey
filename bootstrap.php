<?php
require_once __DIR__ . '/vendor/autoload.php'; // pastikan composer autoload

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
