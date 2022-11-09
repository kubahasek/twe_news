<?php
require "utils.php";

if (!isset($_GET["id"])) {
  header("Location: /twe_news/");
  die();
}

deleteComment($_GET["id"]);
echo "<script>document.referrer ? window.location = document.referrer : history.back()</script>";
