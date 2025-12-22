<?php
session_start();

define('ROOT', dirname(__DIR__));

require_once ROOT . '/app/core/Router.php';
require_once ROOT . '/app/core/Controller.php';
require_once ROOT . '/app/core/Database.php';
require_once ROOT . '/app/core/Auth.php';

$router = new Router();
$router->dispatch();

