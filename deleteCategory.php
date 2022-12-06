<?php
require "utils.php";

if (!isset($_GET["id"])) {
  header("Location: /twe_news/");
  die();
}

if ($_SESSION["user"]["role"] != "admin") {
    if(!IsSignedIn()) {
        header("LOCATION: /twe_news/login.php?msg=needlogin");
        die();
    }
    header("LOCATION: /twe_news/?toast=true&message=Je potřeba účet administrátora&color=red&redirect=/twe_news");
    die(); 
}
$category = getCategory($_GET["id"]);
if ($category[0]["numOfArticles"] > 0) {
  echo "<script>alert('Nelze smazat kategorii pokud má článek');</script>";
  echo "<script>history.back();</script>";
  die();
}

deleteCategory($_GET["id"]);
echo "<script>document.referrer ? window.location = document.referrer : history.back()</script>";
