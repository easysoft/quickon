<?php
/*
 * The routes for API.
 */
$routes = array();

$routes['/tokens']        = 'tokens';
$routes['/adminpassword'] = 'adminPassword';

$config->routes = $routes;
