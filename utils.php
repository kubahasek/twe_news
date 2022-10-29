<?php

function run($sql, $data = []){
        require "db.php";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute($data);
        return $stmt -> fetchAll();
}

function getArticles(): array {
        $sql = "
        SELECT ar.*, concat(a.name, ' ', a.surname) as authorName, GROUP_CONCAT(c.name) as catName, GROUP_CONCAT(c.id) as catId, a.id as authorId FROM article ar
        INNER JOIN author a on ar.author_id = a.id
        INNER JOIN article_category ac on ar.id = ac.article_id
        INNER JOIN category c on ac.category_id = c.id
        GROUP BY ar.id
        LIMIT 5
        ";
        return run($sql);
}

function getCategories(){
        $sql = "SELECT c.*, count(a.id) as numOfArticles FROM category c 
                INNER JOIN article_category ac on c.id = ac.category_id
                INNER JOIN article a ON ac.article_id = a.id
                GROUP BY c.id";
        return run($sql);
}
