<?php

function run($sql, $data = [])
{
  require "db.php";
  $stmt = $conn->prepare($sql);
  $stmt->execute($data);
  return $stmt->fetchAll();
}

function insert($sql, $data = [])
{
  require "db.php";
  $stmt = $conn->prepare($sql);
  $stmt->execute($data);
  return $conn->lastInsertId();
}

function getArticles(): array
{
  $sql = "
        SELECT ar.*, concat(a.name, ' ', a.surname) as authorName, a.id as authorId FROM article ar
        INNER JOIN author a on ar.author_id = a.id
        INNER JOIN article_category ac on ar.id = ac.article_id
        INNER JOIN category c on ac.category_id = c.id
        GROUP BY ar.id
        ORDER BY ar.created_at desc
        LIMIT 5
        ";
  return run($sql);
}

function getCategories()
{
  $sql = "SELECT c.*, count(a.id) as numOfArticles FROM category c 
                INNER JOIN article_category ac on c.id = ac.category_id
                INNER JOIN article a ON ac.article_id = a.id
                GROUP BY c.id";
  return run($sql);
}

function getArticlesForCategory(int $id)
{
  $sql = "
        SELECT ar.*, concat(a.name, ' ', a.surname) as authorName, a.id as authorId FROM article ar
        INNER JOIN author a on ar.author_id = a.id
        INNER JOIN article_category ac on ar.id = ac.article_id
        INNER JOIN category c on ac.category_id = c.id
        WHERE c.id = :id
        GROUP BY ar.id
        ";
  $data = ["id" => $id,];
  return run($sql, $data);
}

function getCategoryName(int $id)
{
  $sql = "SELECT name FROM category where id = :id";
  $data = ["id" => $id,];
  return run($sql, $data);
}

function getArticlesForAuthor(int $id)
{
  $sql = "
        SELECT ar.*, concat(a.name, ' ', a.surname) as authorName, a.id as authorId FROM article ar
        INNER JOIN author a on ar.author_id = a.id
        INNER JOIN article_category ac on ar.id = ac.article_id
        INNER JOIN category c on ac.category_id = c.id
        WHERE a.id = :id
        GROUP BY ar.id
        ";
  $data = ["id" => $id,];
  return run($sql, $data);
}

function getAuthorName(int $id)
{
  $sql = "SELECT concat(name, ' ', surname) as name FROM author WHERE id = :id";
  $data = ["id" => $id,];
  return run($sql, $data);
}

function getAuthors()
{
  $sql = "SELECT concat(a.name, ' ', a.surname) as authorName, a.id as authorId, count(ar.id) as numOfArticles FROM author a
                INNER JOIN article ar on a.id = ar.author_id 
                GROUP BY a.id
                ";
  return run($sql);
}

function getArticle(int $id)
{
  $sql = "SELECT article.*, concat(author.name, ' ', author.surname) as authorName, author.id as authorId from article  INNER JOIN author on article.author_id = author.id WHERE article.id = :id";
  $data = ["id" => $id,];
  return run($sql, $data);
}

function getCategoriesForArticle(int $id)
{
  $sql = "SELECT c.id, c.name FROM article a 
          INNER JOIN article_category ac on a.id = ac.article_id 
          INNER JOIN category c on c.id = ac.category_id
          WHERE a.id = :id
        ";
  $data = ["id" => $id,];
  return run($sql, $data);
}

function createArticle(string $name, string $perex, array $categories, string $author, string $content)
{
  $sql = "INSERT INTO article (title, perex, author_id, text, created_at) VALUES (:name, :perex, :author, :content, NOW())";
  $data = [
    "name" => $name,
    "perex" => $perex,
    "author" => $author,
    "content" => $content,
  ];
  $id = insert($sql, $data);
  insertArticleCategories($id, $categories);
}

function insertArticleCategories(int $articleId, array $categories)
{
  foreach ($categories as $c) {
    $sql = "INSERT INTO article_category (article_id, category_id) VALUES (:articleId, :categoryId)";
    $data = [
      "articleId" => $articleId,
      "categoryId" => $c,
    ];
    run($sql, $data);
  }
}
