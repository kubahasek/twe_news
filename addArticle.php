<?php
require "utils.php";
session_start();

if (!IsSignedIn() || (IsSignedIn() && $_SESSION["user"]["role"] != "admin")) {
    if ($_SESSION["user"]["role"] != "author") {
        header("LOCATION: /twe_news/login.php?msg=needlogin");
        die();
    }
}
$categoriesForArticleIds = [];
if (!empty($_POST) && isset($_POST) && !isset($_GET["id"])) {
    $fileName = $_FILES['image']['name'];
    $fileTmpName  = $_FILES['image']['tmp_name'];
    $fileExtension = strtolower(end(explode('.',$fileName)));
    $uploadPath = "uploads/" . basename(uniqid()). "." . $fileExtension;
    move_uploaded_file($fileTmpName, $uploadPath);
    $articleId = createArticle($_POST["articleName"], $_POST["perex"], $_POST["category"], $_POST["author"], $_POST["articleContent"], $uploadPath, isset($_POST['public']) ? 1 : 0);
    header("Location: /twe_news/article.php?id=" . $articleId);
} else if (!empty($_POST) && isset($_POST) && isset($_GET["id"])) {
    $article = getArticle($_GET["id"]);
    unlink($article[0]["image"]);
    $fileName = $_FILES['image']['name'];
    $fileTmpName  = $_FILES['image']['tmp_name'];
    $fileExtension = strtolower(end(explode('.',$fileName)));
    $uploadPath = "uploads/" . basename(uniqid()). "." . $fileExtension;
    move_uploaded_file($fileTmpName, $uploadPath);
    updateArticle($_GET["id"], $_POST["articleName"], $_POST["perex"], $_POST["category"], $_POST["author"], $_POST["articleContent"], $uploadPath, isset($_POST['public']) ? 1 : 0);
    header("Location: /twe_news/article.php?id=" . $_GET["id"]);
}

if (isset($_GET["id"])) {
    $article = getArticle($_GET["id"]);
    if ($_SESSION["user"]["id"] != $article[0]["author_id"] && $_SESSION["user"]["role"] != "admin") {
        header("LOCATION: /twe_news?toast=true&message=Nelze editovat článek jiného autora.&color=red&redirect=/twe_news");
        die();
    }
    $categoriesForArticle = getCategoriesForArticle($_GET["id"]);
    $categoriesForArticleIds = array_map(
        function ($c) {
            return $c["id"];
        },
        $categoriesForArticle
    );
}

?>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" />
  <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
  <link rel="stylesheet" href="quill.css">
  <link rel="stylesheet" href="article.css">
  <link rel="stylesheet" href="output.css">
  <title>The #1 trusted news source!</title>
</head>
<?php

$categories = getCategories();
$authors = getAuthors();

?>

<body class="bg-dark text-white">
  <!-- colors: https://coolors.co/2e3532-ffbf00-c9c5cb-648767-7f2ccb -->
  <nav class="bg-violet text-gray border-gray-200 px-2 sm:px-4 py-2.5 rounded shadow-xl">
    <div class="container flex flex-wrap justify-between items-center mx-auto">
      <a href="/twe_news/" class="flex items-center">
        <span class="self-center text-white text-4xl font-semibold whitespace-nowrap dark:text-white">PHP News</span>
      </a>
      <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 ml-3 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="white" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
        </svg>
      </button>
      <div class="hidden w-full md:block md:w-auto" id="navbar-default">
        <ul class="flex flex-col p-4 mt-4 bg-dark rounded-lg border border-gray-100 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:border-0">
          <li>
            <a href="/twe_news/" class="block py-2 pr-4 pl-3 text-white hover:text-yellow rounded md:bg-dark md:p-0 " aria-current="page">Zprávy</a>
          </li>
          <li>
            <a href="/twe_news/category.php" class="block py-2 pr-4 pl-3 text-white hover:text-yellow rounded md:bg-dark md:p-0 " aria-current="page">Kategorie</a>
          </li>
          <li>
            <a href="/twe_news/author.php" class="block py-2 pr-4 pl-3 text-white hover:text-yellow rounded md:bg-dark md:p-0 " aria-current="page">Autoři</a>
          </li>
          <?php if (!isset($_SESSION["user"])) : ?>
            <li>
              <a href="/twe_news/login.php" class="block py-2 pr-4 pl-3 text-white hover:text-yellow rounded md:bg-dark md:p-0 " aria-current="page">Přihlásit</a>
            </li>
          <?php elseif (isset($_SESSION["user"]) && $_SESSION["user"]["role"] == "admin") : ?>
            <li>
              <a href="/twe_news/administration.php" class="block py-2 pr-4 pl-3 text-white hover:text-yellow rounded md:bg-dark md:p-0 " aria-current="page">Administrace</a>
            </li>
          <?php elseif (isset($_SESSION["user"]) && $_SESSION["user"]["role"] == "author") : ?>
            <li>
              <a href="/twe_news/addArticle.php" class="block py-2 pr-4 pl-3 text-dark bg-yellow rounded md:bg-dark md:text-yellow md:p-0" aria-current="page">Přidat</a>
            </li>
          <?php endif; ?>
          <?php if (isset($_SESSION["user"])) : ?>
            <li class="flex gap-2 items-center">
              <p href="/twe_news/signOut.php" aria-current="page" class="m-0"><?php echo $_SESSION["user"]["name"] ?> <?php echo $_SESSION["user"]["surname"] ?></p><a href="/twe_news/signOut.php" class="block py-2 pr-4 pl-3 text-white font-bold hover:text-red rounded md:bg-dark md:p-0">Odhlásit</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <main>
    <div class="container mx-auto mt-5 md:p-0 px-2">
      <h1 class="text-white text-3xl md:text-5xl uppercase font-bold">Přidat článek</h1>
      <div class="text-white mt-4">
        <form class="flex flex-col gap-4" action="" method="post" enctype="multipart/form-data">
          <div>
            <label for="articleName" class="block mb-2 text-sm font-medium text-white">Název článku</label>
            <input type="text" id="articleName" name="articleName" value="<?php echo isset($_GET["id"]) ? $article[0]["title"] : null ?>" class="bg-dark border border-gray-300 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Sick name man..." required>
          </div>
          <div>
            <label for="category" class="block mb-2 text-sm font-medium text-white">Kategorie</label>
            <div class="flex gap-2 items-center">
              <?php foreach ($categories as $c) : ?>
                    <?php if (in_array($c["id"], $categoriesForArticleIds)) : ?>
                  <div>
                    <input id="checkbox-<?php echo $c["id"] ?>" name="category[]" type="checkbox" value="<?php echo $c["id"] ?>" checked class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500">
                    <label for="checkbox-<?php echo $c["id"] ?>" class="text-sm font-medium text-white"><?php echo $c["name"] ?></label>
                  </div>
                <?php else : ?>
                  <div>
                    <input id="checkbox-<?php echo $c["id"] ?>" name="category[]" type="checkbox" value="<?php echo $c["id"] ?>" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500">
                    <label for="checkbox-<?php echo $c["id"] ?>" class="text-sm font-medium text-white"><?php echo $c["name"] ?></label>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>
          <div>
            <label for="authors" class="block mb-2 text-sm font-medium text-white">Autor</label>
            <select id="authors" name="author" class="bg-dark disabled:opacity-[40%] border border-gray-300 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" <?php echo isset($_SESSION["user"]) && $_SESSION["user"]["role"] == "author" ? "disabled" : "" ?> required>
              <option <?php echo isset($_GET["id"]) ? "" : "selected" ?> hidden>Vyberte autora</option>
              <?php foreach ($authors as $a) : ?>
                <option value="<?php echo $a['authorId'] ?>" <?php echo (isset($_GET["id"]) && $article[0]["authorId"] == $a["authorId"]) || (isset($_SESSION["user"]) && $_SESSION["user"]["id"] === $a["authorId"]) ? "selected" : "" ?>><?php echo $a["authorName"] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="perex" class="block mb-2 text-sm font-medium text-white dark:text-gray-300">Perex</label>
            <input type="text" id="perex" name="perex" value="<?php echo isset($_GET["id"]) ? $article[0]["perex"] : null ?>" class="bg-dark border border-gray-300 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Sick name man..." required>
          </div>
          <div>
            <label for="photo" class="block mb-2 text-sm font-medium text-white dark:text-gray-300">Foto</label>
            <input type="file" id="photo" name="image" class="bg-dark text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Sick name man..." required>
          </div>
          <div>
            <label class="block mb-2 text-sm font-medium text-white dark:text-gray-300">Obsah článku</label>
            <div id="toolbar-container" class="text-white"></div>
            <div id="editor-container" class="text-white" style="height: 400px"></div>
          </div>
          <div>
            <input type="text" name="articleContent" id="articleContent" hidden>
          </div>
          <div>
            <input type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500" <?php echo isset($article[0]["public"]) && $article[0]["public"] == 1 ? "checked" : "" ?> value="1" name="public" id="publicCheckbox">
            <label for="publicCheckbox">Veřejný</label>
          </div>
          <button type="submit" class="text-dark bg-yellow hover:bg-violet focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"><?php echo isset($_GET["id"]) ? "Upravit" : "Přidat" ?></button>
          <?php echo isset($_SESSION["user"]) && $_SESSION["user"]["role"] == "author" ? "<input type='hidden' value='".$_SESSION["user"]["id"]."' name='author' >" : "" ?>
        </form>
      </div>
  </main>
  <script type="text/javascript">
    let htmlFromDB = null;
    htmlFromDB = <?php if (isset($_GET["id"])) {
                    echo json_encode(($article[0]["text"]));
} ?>;
  </script>
  <script src="./Quill.js"></script>
  <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script>
</body>

</html>
