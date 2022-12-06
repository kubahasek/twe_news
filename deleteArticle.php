<?php
require "utils.php";

if (!isset($_GET["id"])) {
  header("Location: /twe_news/");
  die();
}
$article = getArticle($_GET["id"]);

if(!IsSignedIn()) {
    header("LOCATION: /twe_news/login.php?msg=needlogin");
    die();
}
if ($_SESSION["user"]["role"] != "admin" && $_SESSION["user"]["role"] != "author") {
  header("LOCATION: /twe_news/?toast=true&message=Je potřeba účet administrátora&color=red&redirect=/twe_news");
  die(); 
}
if ($_SESSION["user"]["role"] == "author" && $_SESSION["user"]["id"] != $article[0]["author_id"]) {
  header("LOCATION: /twe_news/?toast=true&message=Nelze mazat článek jiného autora&color=red&redirect=/twe_news");
  die();
}

deleteArticle($_GET["id"]);
echo "<script>document.referrer ? window.location = document.referrer : history.back()</script>";
