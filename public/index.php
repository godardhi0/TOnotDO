<?php
session_start();

require_once '../app/core/Router.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Database.php';
require_once '../app/core/Auth.php';

$router = new Router();
$router->dispatch();
