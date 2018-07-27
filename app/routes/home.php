<?php

$app->get('/', function() use ($app) {
    $reddit = $app->reddit;

    $reddit->login();

    $app->render('home.php');
})->name('home');