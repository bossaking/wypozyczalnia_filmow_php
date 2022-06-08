<?php
session_start();

include 'db.php';

?>

<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Super wypożyczalnia</title>

    <link rel="stylesheet" href="style.css">
    <link href='https://fonts.googleapis.com/css?family=Akaya+Kanadaka&subset=latin,latin-ext' rel='stylesheet'
          type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Akaya+Telivigala&subset=latin,latin-ext' rel='stylesheet'
          type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Arbutus+Slab&subset=latin,latin-ext' rel='stylesheet'
          type='text/css'>

</head>
<body>
<header class="page-header">
    <a href="index.php" class="site-name">Super Wypożyczalnia</a>
    <section class="nav-links">

        <?php
        if (!isset($_SESSION['user'])) { ?>
            <a href="login.php">Zaloguj się</a>
            <?php
        } else {
            ?>
            <a href="logout.php"><?=$_SESSION['user']['name']?> <?=$_SESSION['user']['surname']?></a>
            <?php
        }
        ?>

    </section>
</header>
