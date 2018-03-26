<?php

$router->get('/', function () {
    return 'Hello galera!';
});

$router->get('/home', 'HomeController@index');
$router->get('/view', 'viewController@index');
