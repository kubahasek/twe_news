<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" />
  <link rel="stylesheet" href="output.css">
  <link rel="stylesheet" href="article.css">
  <title>The #1 trusted news source!</title>
</head>
<?php
require "utils.php";
if (isset($_GET["id"])) {
  $article = getArticle($_GET["id"]);
  $categories = getCategoriesForArticle($_GET["id"]);
}
?>

<body class="bg-dark text-white">
  <!-- colors: https://coolors.co/2e3532-ffbf00-c9c5cb-648767-7f2ccb -->
  <nav class="bg-dark text-gray border-gray-200 px-2 sm:px-4 py-2.5 rounded shadow-xl">
    <div class="container flex flex-wrap justify-between items-center mx-auto">
      <a href="/twe_news/" class="flex items-center">
        <span class="self-center text-white text-4xl font-semibold whitespace-nowrap dark:text-white">PHP News</span>
      </a>
      <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 ml-3 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
        </svg>
      </button>
      <div class="hidden w-full md:block md:w-auto" id="navbar-default">
        <ul class="flex flex-col p-4 mt-4 bg-dark rounded-lg border border-gray-100 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:border-0">
          <li>
            <a href="/twe_news/" class="block py-2 pr-4 pl-3 text-dark bg-yellow rounded md:bg-dark md:text-yellow md:p-0" aria-current="page">Zprávy</a>
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
            <a href="#" class="block py-2 pr-4 pl-3 text-white hover:text-yellow rounded md:bg-dark md:p-0 " aria-current="page">Přidat</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <main>
    <div class="container mx-auto mt-5 md:p-0 px-2">
      <div class="mt-5 flex flex-col gap-4 md:m-0 mx-auto w-[90%] md:w-[70%]">
        <article>
          <div class="flex gap-2">
            <?php foreach ($categories as $c) : ?>
              <a class="cursor-pointer text-yellow underline" href="/twe_news/index.php?catId=<?= $c["id"] ?>">
                <p><?= $c["name"] ?>
              </a>
            <?php endforeach; ?>
          </div>
          <div class="mt-4">
            <h1 class="text-5xl text-yellow"><?= $article[0]["title"] ?></h1>
            <h1 class="text-2xl text-white mt-2"><?= $article[0]["perex"] ?></h1>
            <p><?= date_format(date_create($article[0]["created_at"]), "d.m.Y H:i") ?> <a class="underline text-yellow" href="index.php?autId=<?= $article[0]["authorId"] ?>"><?= $article[0]["authorName"] ?></a></p>
            <div class="mb-5">
              <?= $article[0]["text"] ?>
            </div>
          </div>
        </article>
      </div>
    </div>
  </main>
  <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script>
</body>

</html>
