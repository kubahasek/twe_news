<?php 

session_start();
unset($_SESSION["user"]);
header("LOCATION: /twe_news/");