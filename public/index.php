<?php

/**
 * MVC Structure
 *
 * @author Elielson Batista <elielsonbatistaa@hotmail.com>
 */

/**
 * Autoloading
 */
require '../bootstrap/autoload.php';

/**
 * Registering Routes
 */
$router = new \Src\Http\Router();

require '../routes/web.php';

/**
 * Calling Request
 */
$request = new \Src\Http\Request($router);
$request->start();
