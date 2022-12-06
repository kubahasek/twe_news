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
if (!IsSignedIn() || IsSignedIn() && $_SESSION["user"]["role"] != "admin") {
    if(!IsSignedIn()) {
      header("LOCATION: /twe_news/login.php?msg=needlogin");
      die(); 
    }
    header("LOCATION: /twe_news/?toast=true&message=Je potřeba účet administrátora&color=red&redirect=/twe_news");
    die();
}
$categories = getCategories();
$authors = getAuthors();
$articles = getArticles(true);
$comments = getAllComments();
$users = getAllUsers();

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
              <a href="/twe_news/administration.php" class="block py-2 pr-4 pl-3 text-dark bg-yellow rounded md:bg-dark md:text-yellow md:p-0" aria-current="page">Administrace</a>
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
      <h1 class="text-white text-5xl uppercase font-bold">Administrace</h1>
      <div class="mb-4">
        <div class="flex items-center gap-2">
          <h2 class="text-white text-3xl uppercase font-bold">Kategorie - </h2>
          <a href="/twe_news/categoryForm.php" class="cursor-pointer hover:text-green"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                <td class="p-2"><?php echo $c["name"] ?></td>
                <td class="flex p-2 justify-center">
                  <a href="/twe_news/categoryForm.php?id=<?php echo $c["id"] ?>" class="hover:text-yellow cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                  </a>
                  <a href="/twe_news/deleteCategory.php?id=<?php echo $c["id"] ?>" class="hover:text-red cursor-pointer">
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
          <h2 class="text-white text-3xl uppercase font-bold">Články - </h2>
          <a href="/twe_news/addArticle.php" class="cursor-pointer hover:text-green"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
            <?php foreach ($articles as $a) : ?>
              <tr class="bg-dark text-white border-b dark:bg-gray-900 dark:border-gray-700">
                <td class="p-2"><?php echo $a["title"] ?></td>
                <td class="flex p-2 justify-center">
                  <a href="/twe_news/addArticle.php?id=<?php echo $a["id"] ?>" class="hover:text-yellow cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                  </a>
                  <a href="/twe_news/deleteArticle.php?id=<?php echo $a["id"] ?>" class="hover:text-red cursor-pointer">
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
          <h2 class="text-white text-3xl uppercase font-bold">Uživatelé</h2>
        </div>
        <table class="mt-4 text-sm text-left text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-center text-white uppercase bg-violet">
            <tr>
              <th scope="col" class="py-3 px-6">
                Jméno
              </th>
              <th scope="col" class="py-3 px-6">
                Role
              </th>
              <th scope="col" class="py-3 px-6">
                Akce
              </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $u) : ?>
              <tr class="bg-dark text-white border-b dark:bg-gray-900 dark:border-gray-700">
                <td class="p-2"><?php echo $u["enabled"] ? "" : "<del>" ?><?php echo $u["name"] ?> <?php echo $u["surname"] ?><?php echo $u["enabled"] ? "" : "</del>" ?></del></td>
                <td class="p-2"><?php echo $u["role"] ?> </td>
                <td class="flex p-2 justify-center">
                  <a href="/twe_news/userForm.php?id=<?php echo $u["id"] ?>" class="hover:text-yellow cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                  </a>
                  <?php if ($u["enabled"] == true) : ?>
                    <a href="/twe_news/disableUser.php?id=<?php echo $u["id"] ?>" class="hover:text-red cursor-pointer">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M22 2 2 22"></path>
                      </svg>
                    </a>
                  <?php else : ?>
                    <a href="/twe_news/enableUser.php?id=<?php echo $u["id"] ?>" class="hover:text-red cursor-pointer">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                      </svg>
                    </a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="mb-4">
        <div class="flex items-center gap-2">
          <h2 class="text-white text-3xl uppercase font-bold">Komentáře</h2>
        </div>
        <table class="mt-4 text-sm text-left text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-center text-white uppercase bg-violet">
            <tr>
              <th scope="col" class="py-3 px-6">
                Email
              </th>
              <th scope="col" class="py-3 px-6">
                Komentář
              </th>
              <th scope="col" class="py-3 px-6">
                Článek
              </th>
              <th scope="col" class="py-3 px-6">
                Akce
              </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($comments as $c) : ?>
              <tr class="bg-dark text-white border-b dark:bg-gray-900 dark:border-gray-700">
                <td class="p-2"><?php echo $c["email"] ?></td>
                <td class="p-2"><?php echo $c["content"] ?></td>
                <td class="p-2"><?php echo $c["title"] ?></td>
                <td class="flex p-2 justify-center">
                  <a href="/twe_news/deleteComment.php?id=<?php echo $c["id"] ?>" class="hover:text-red cursor-pointer">
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
