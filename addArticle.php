<?php
require "utils.php";

if (!empty($_POST) && isset($_POST)) {
  createArticle($_POST["articleName"], $_POST["perex"], $_POST["category"], $_POST["author"], $_POST["articleContent"]);
}

?>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" />
  <script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>
  <link rel="stylesheet" href="quill.css">
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
          <li>
            <a href="#" class="block py-2 pr-4 pl-3 text-white hover:text-yellow rounded md:bg-dark md:p-0 " aria-current="page">Administrace</a>
          </li>
          <li>
            <a href="/twe_news/addArticle.php" class="block py-2 pr-4 pl-3 text-dark bg-yellow rounded md:bg-dark md:text-yellow md:p-0" aria-current="page">Přidat</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <main>
    <div class="container mx-auto mt-5 md:p-0 px-2">
      <h1 class="text-white text-3xl md:text-5xl uppercase font-bold">Přidat článek</h1>
      <div class="text-white mt-4">
        <form class="flex flex-col gap-4" action="" method="post">
          <div>
            <label for="articleName" class="block mb-2 text-sm font-medium text-white">Název článku</label>
            <input type="text" id="articleName" name="articleName" class="bg-dark border border-gray-300 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Sick name man..." required>
          </div>
          <div>
            <label for="category" class="block mb-2 text-sm font-medium text-white">Kategorie</label>
            <div class="flex gap-4 items-center">
              <?php foreach ($categories as $c) : ?>
                <input id="checkbox-<?= $c["id"] ?>" name="category[]" type="checkbox" value="<?= $c["id"] ?>" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500">
                <label for="checkbox-<?= $c["id"] ?>" class="ml-2 text-sm font-medium text-white"><?= $c["name"] ?></label>
              <?php endforeach; ?>
            </div>
          </div>
          <div>
            <label for="authors" class="block mb-2 text-sm font-medium text-white">Autor</label>
            <select id="authors" name="author" class="bg-dark border border-gray-300 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
              <option selected hidden>Vyberte autora</option>
              <?php foreach ($authors as $a) : ?>
                <option value="<?= $a['authorId'] ?>"><?= $a["authorName"] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="perex" class="block mb-2 text-sm font-medium text-white dark:text-gray-300">Perex</label>
            <input type="text" id="perex" name="perex" class="bg-dark border border-gray-300 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Sick name man..." required>
          </div>
          <div>
            <label class="block mb-2 text-sm font-medium text-white dark:text-gray-300">Obsah článku</label>
            <div id="editor" class="text-white" style="height: 400px"></div>
          </div>
          <div>
            <input type="text" name="articleContent" id="articleContent" hidden>
          </div>
          <button type="submit" class="text-dark bg-yellow hover:bg-violet focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Default</button>
        </form>
      </div>
  </main>
  <script src="./Quill.js"></script>
  <script>



  </script>
  <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script>
</body>

</html>
