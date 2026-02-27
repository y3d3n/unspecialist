<!DOCTYPE html>
<html>
<?php $site = "Unspecialist" ?>
<head>
    <title><?= $title ?? $site ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <header>
        <h1><?= $title ?? $site ?></h1>
        <nav>
            <a href="/">Home</a>
            <a href="about">About</a>
        </nav>
    </header>