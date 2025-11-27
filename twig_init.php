<?php
// twig_init.php

require_once __DIR__ . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');

$twig = new \Twig\Environment($loader, [
    'cache' => false, // disable cache in development
    'autoescape' => 'html', // helps with XSS protection
]);
