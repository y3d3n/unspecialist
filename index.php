<?php

$routes = [
    '' => 'home.php',
    'about' => 'about.php',
    'contact' => 'contact.php',
];

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

if (array_key_exists($uri, $routes)) {
    require "pages/" . $routes[$uri];
} else {
    http_response_code(404);
    require "pages/404.php";
}