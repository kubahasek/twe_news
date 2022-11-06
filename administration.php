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
$categories = getCategories();
$authors = getAuthors();
$articles = getArticles(true);

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
            <a href="/twe_news/addArticle.php" class="block py-2 pr-4 pl-3 text-white hover:text-yellow rounded md:bg-dark md:p-0 " aria-current="page">Přidat</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <main>
    <div class="container mx-auto mt-5 md:p-0 px-2">
      <h1 class="text-white text-5xl uppercase font-bold">Administrace</h1>
      <div class="mb-4">
        <div class="flex items-center gap-2">
          <h2 class="text-white text-3xl uppercase font-bold">Kategorie - </h2>
          <a class="cursor-pointer hover:text-green"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="12" y1="8" x2="12" y2="16"></line>
              <line x1="8" y1="12" x2="16" y2="12"></line>
            </svg>
          </a>
        </div>
        <table class="mt-4 text-sm text-left text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-center text-white uppercase bg-violet">
            <tr>
              <th scope="col" class="py-3 px-6">
                Název
              </th>
              <th scope="col" class="py-3 px-6">
                Akce
              </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($categories as $c) : ?>
              <tr class="bg-dark text-white border-b dark:bg-gray-900 dark:border-gray-700">
                <td class="p-2"><?= $c["name"] ?></td>
                <td class="flex p-2 justify-center">
                  <a class="hover:text-yellow cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                  </a>
                  <a class="hover:text-red cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                      <line x1="9" y1="9" x2="15" y2="15"></line>
                      <line x1="15" y1="9" x2="9" y2="15"></line>
                    </svg>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="mb-4">
        <div class="flex items-center gap-2">
          <h2 class="text-white text-3xl uppercase font-bold">Autoři - </h2>
          <a href="/twe_news/authorForm.php" class="cursor-pointer hover:text-green"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="12" y1="8" x2="12" y2="16"></line>
              <line x1="8" y1="12" x2="16" y2="12"></line>
            </svg>
          </a>
        </div>
        <table class="mt-4 text-sm text-left text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-center text-white uppercase bg-violet">
            <tr>
              <th scope="col" class="py-3 px-6">
                Jméno
              </th>
              <th scope="col" class="py-3 px-6">
                Akce
              </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($authors as $a) : ?>
              <tr class="bg-dark text-white border-b dark:bg-gray-900 dark:border-gray-700">
                <td class="p-2"><?= $a["authorName"] ?></td>
                <td class="flex p-2 justify-center">
                  <a href="/twe_news/authorForm.php?id=<?= $a["authorId"] ?>" class="hover:text-yellow cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                  </a>
                  <a class="hover:text-red cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                      <line x1="9" y1="9" x2="15" y2="15"></line>
                      <line x1="15" y1="9" x2="9" y2="15"></line>
                    </svg>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="mb-4">
        <h2 class="text-white text-3xl uppercase font-bold">Články</h2>
        <table class="mt-4 text-sm text-left text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-center text-white uppercase bg-violet">
            <tr>
              <th scope="col" class="py-3 px-6">
                Název
              </th>
              <th scope="col" class="py-3 px-6">
                Akce
              </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($articles as $a) : ?>
              <tr class="bg-dark text-white border-b dark:bg-gray-900 dark:border-gray-700">
                <td class="p-2"><?= $a["title"] ?></td>
                <td class="flex p-2 justify-center">
                  <a class="hover:text-yellow cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                  </a>
                  <a class="hover:text-red cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                      <line x1="9" y1="9" x2="15" y2="15"></line>
                      <line x1="15" y1="9" x2="9" y2="15"></line>
                    </svg>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
  <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script>
</body>

</html>
