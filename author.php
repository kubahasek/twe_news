<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" />
  <link rel="stylesheet" href="output.css">
  <title>The #1 trusted news source!</title>
</head>
<?php
require "utils.php";
session_start();
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
            <a href="/twe_news/author.php" class="block py-2 pr-4 pl-3 text-dark bg-yellow rounded md:bg-dark md:text-yellow md:p-0" aria-current="page">Autoři</a>
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
              <a href="/twe_news/addArticle.php" class="block py-2 pr-4 pl-3 text-white hover:text-yellow rounded md:bg-dark md:p-0 " aria-current="page">Přidat</a>
            </li>
          <?php endif; ?>
          <?php if (isset($_SESSION["user"])) : ?>
            <li class="flex gap-2 items-center">
              <p href="/twe_news/signOut.php" aria-current="page"><?php echo $_SESSION["user"]["name"] ?> <?php echo $_SESSION["user"]["surname"] ?></p><a href="/twe_news/signOut.php" class="block py-2 pr-4 pl-3 text-white font-bold hover:text-red rounded md:bg-dark md:p-0">Odhlásit</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <main>
    <div class="container mx-auto mt-5 md:p-0 px-2">
      <h1 class="text-white text-5xl uppercase font-bold">Autoři</h1>
      <?php foreach ($authors as $a) : ?>
        <div class="flex gap-2 items-center">
          <h1 class="text-3xl"><a class="cursor-pointer text-yellow underline" href="/twe_news/index.php?autId=<?php echo $a["authorId"] ?>"><?php echo $a["authorName"] ?></a> - <?php echo $a["numOfArticles"] ?> <?php echo $a["numOfArticles"] > 1 ? "články" : "článek" ?></h1>
            <?php if (isset($_SESSION["user"]) && $_SESSION["user"]["role"] == "admin") : ?>
            <div class="flex gap-2">
              <a href="/twe_news/userForm.php?id=<?php echo $a["authorId"] ?>" class="hover:text-yellow cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
              </a>
            </div>
            <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </main>
  <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script>
</body>

</html>
