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

function update($sql, $data = [])
{
  require "db.php";
  $stmt = $conn->prepare($sql);
  $stmt->execute($data);
}

function getArticles(bool $all): array
{
  if ($all) {
    $sql = "
        SELECT ar.*, concat(a.name, ' ', a.surname) as authorName, a.id as authorId FROM article ar
        INNER JOIN author a on ar.author_id = a.id
        INNER JOIN article_category ac on ar.id = ac.article_id
        INNER JOIN category c on ac.category_id = c.id
        GROUP BY ar.id
        ORDER BY ar.created_at desc
        LIMIT 5
        ";
  } else {
    $sql = "
        SELECT ar.*, concat(a.name, ' ', a.surname) as authorName, a.id as authorId FROM article ar
        INNER JOIN author a on ar.author_id = a.id
        INNER JOIN article_category ac on ar.id = ac.article_id
        INNER JOIN category c on ac.category_id = c.id
        WHERE ar.public = 1
        GROUP BY ar.id
        ORDER BY ar.created_at desc
        LIMIT 5
        ";
  }
  return run($sql);
}

function getCategories()
{
  $sql = "SELECT c.*, count(a.id) as numOfArticles FROM category c 
                LEFT JOIN article_category ac on c.id = ac.category_id
                LEFT JOIN article a ON ac.article_id = a.id
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
                LEFT JOIN article ar on a.id = ar.author_id 
                GROUP BY a.id
                ";
  return run($sql);
}

function getAuthor(int $id)
{
  $sql = "SELECT *, count(ar.id) as numOfArticles FROM author a INNER JOIN article ar on a.id = ar.author_id WHERE a.id = :id";
  $data = [
    "id" => $id,
  ];
  return run($sql, $data);
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

function createArticle(string $name, string $perex, array $categories, string $author, string $content, int $public)
{
  $sql = "INSERT INTO article (title, perex, author_id, text, created_at, public) VALUES (:name, :perex, :author, :content, NOW(), :public)";
  $data = [
    "name" => $name,
    "perex" => $perex,
    "author" => $author,
    "content" => $content,
    "public" => $public,
  ];
  $id = insert($sql, $data);
  insertArticleCategories($id, $categories);
  return $id;
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

function updateArticle(int $articleId, string $name, string $perex, array $categories, string $author, string $content, int $public)
{
  $sql = "UPDATE article SET title = :name, perex = :perex, text = :content, author_id = :author, public = :public WHERE id = :id";
  $data = [
    "id" => $articleId,
    "name" => $name,
    "perex" => $perex,
    "author" => $author,
    "content" => $content,
    "public" => $public,
  ];
  update($sql, $data);
  updateArticleCategories($articleId, $categories);
}

function updateArticleCategories(int $articleId, array $categories)
{
  $sql = "DELETE FROM article_category WHERE article_id = :articleId";
  $data = [
    "articleId" => $articleId,
  ];
  run($sql, $data);

  insertArticleCategories($articleId, $categories);
}

function createAuthor(string $name, string $surname)
{
  $sql = "INSERT INTO author (name, surname) VALUES (:name, :surname)";
  $data = [
    "name" => $name,
    "surname" => $surname,
  ];

  return insert($sql, $data);
}

function updateAuthor(int $id, string $name, string $surname)
{
  $sql = "UPDATE author set name = :name, surname = :surname WHERE id = :id";
  $data = [
    "name" => $name,
    "surname" => $surname,
    "id" => $id,
  ];

  update($sql, $data);
}
function deleteAuthor(int $id)
{
  $sql = "DELETE FROM author WHERE id = :id";
  $data = [
    "id" => $id,
  ];

  run($sql, $data);
}

function deleteArticle(int $id)
{
  $sql = "DELETE FROM article WHERE id = :id";
  $data = [
    "id" => $id,
  ];

  run($sql, $data);
}
