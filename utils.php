<?php

require "db.php";

function run($sql, $data = []){
    $stmt = $conn -> prepare($sql);
    $stmt -> execute($data)
}