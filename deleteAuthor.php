<?php
require "utils.php";

if (!isset($_GET["id"])) {
  header("Location: /twe_news/");
  die();
}

$author = getAuthor($_GET["id"]);
if ($author[0]["numOfArticles"] > 0) {
  echo "<script>alert('Nelze smazat autora pokud má článek');</script>";
  echo "<script>history.back();</script>";
  die();
}

deleteAuthor($_GET["id"]);
echo "<script>document.referrer ? window.location = document.referrer : history.back()</script>";
