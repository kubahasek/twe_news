<?php
require "utils.php";

if (!isset($_GET["id"])) {
    header("Location: /twe_news/");
    die();
}
session_start();

if ($_SESSION["user"]["role"] != "admin") {
    if(!IsSignedIn()) {
        header("LOCATION: /twe_news/login.php?msg=needlogin");
        die();
    }
    header("LOCATION: /twe_news/?toast=true&message=Je potřeba účet administrátora&color=red&redirect=/twe_news");
    die(); 
}


$user = getUser($_GET["id"]);

enableUser($_GET["id"]);
header("Location: /twe_news/administration.php");
die();
