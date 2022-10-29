<?php
global $conn;
$conn = new PDO('mysql:host=mysqlstudenti.litv.sssvt.cz;dbname=4a1_hasekjakub_db1', 'hasekjakub', '123456', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
$conn->query('SET NAMES utf8');
