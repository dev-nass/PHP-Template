<?php

$router->get('index', 'UserController', 'index');
$router->post('index', 'UserController', 'store');