<?php
require "utils.php";
session_start();
if (!empty($_POST) && isset($_POST)) {
    $comment = createComment($_SESSION["user"]["email"], $_POST["content"], $_GET["id"]);
    header("LOCATION: /twe_news/article.php?id=" . $_GET["id"]);
}
?>

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
if (isset($_GET["id"])) {
    $article = getArticle($_GET["id"]);
    if (!$article) {
        header("Location: /twe_news/");
    }
    $categories = getCategoriesForArticle($_GET["id"]);
    $comments = getCommentsForArticle($_GET["id"]);
}
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
              <p href="/twe_news/signOut.php" aria-current="page" class="m-0"><?php echo $_SESSION["user"]["name"] ?> <?php echo $_SESSION["user"]["surname"] ?></p><a href="/twe_news/signOut.php" class="block py-2 pr-4 pl-3 text-white font-bold hover:text-red rounded md:bg-dark md:p-0">Odhlásit</a>
            </li>
          <?php endif; ?>
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
              <a class="cursor-pointer text-yellow underline" href="/twe_news/index.php?catId=<?php echo $c["id"] ?>">
                <p><?php echo $c["name"] ?>
              </a>
            <?php endforeach; ?>
          </div>
          <div class="mt-4">
            <h1 class="text-5xl text-yellow"><?php echo $article[0]["title"] ?></h1>
            <h1 class="text-2xl text-white mt-2"><?php echo $article[0]["perex"] ?></h1>
            <?php if (isset($_SESSION["user"]) && ($_SESSION["user"]["role"] == "admin") || $_SESSION["user"]["id"] == $article[0]["author_id"]) : ?>
              <div class="flex gap-2 mt-2">
                <a href="/twe_news/addArticle.php?id=<?php echo $article[0]["id"] ?>" class="text-white hover:text-yellow cursor-pointer">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                  </svg>
                </a>
                <a href="/twe_news/deleteArticle.php?id=<?php echo $article[0]["id"] ?>" class="text-white hover:text-red cursor-pointer">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                  </svg>
                </a>
              </div>
            <?php endif; ?>
            <p><?php echo date_format(date_create($article[0]["created_at"]), "d.m.Y H:i") ?> <a class="underline text-yellow" href="index.php?autId=<?php echo $article[0]["authorId"] ?>"><?php echo $article[0]["authorName"] ?></a></p>
            <div class="mb-5">
              <?php echo $article[0]["text"] ?>
            </div>
          </div>
        </article>
        <div class="">
          <h1 class="text-3xl font-bold mb-4">Komentáře</h1>
          <?php if (IsSignedIn()) : ?>
            <form class="" action="" method="POST">
              <div class="w-full flex flex-col gap-2">
                <label for="chat" class="block mb-2 text-sm font-medium text-white">Your message</label>
                <div class="flex w-full items-center">
                  <textarea id="chat" name="content" rows="1" class="block bg-dark p-2.5 w-full text-sm text-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your message..." required></textarea>
                  <button type="submit" class="ml-2 inline-flex justify-center p-2 text-yellow rounded-full cursor-pointer hover:bg-violet">
                    <svg aria-hidden="true" class="w-6 h-6 rotate-90" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                      <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                    </svg>
                    <span class="sr-only">Send message</span>
                  </button>
                </div>
              </div>
        </div>
        </form>
      <?php else : ?>
        <p class="mb-4">Pro napsání komentáře je nutné se přihlásit! <a href="/twe_news/login.php" class="text-yellow underline">Přihlásit se</a></p>
      <?php endif; ?>
      <hr class="mb-4">
      <?php foreach ($comments as $c) : ?>
        <div class="border rounded-lg p-4 mb-4">
          <p class="font-normal text-grey m-0"><?php echo date_format(date_create($c["submitted_at"]), "d.m.Y H:i")  ?></p>
          <div class="flex gap-2">
            <img class="w-11 border rounded-full p-1" src="https://avatars.dicebear.com/api/adventurer/<?php echo $c["email"] ?>.svg" alt="" srcset="">
            <h5 class="text-2xl font-bold tracking-tight text-white"><?php echo $c["email"] ?> napsal:</h5>
          </div>
          <p class="mt-0.5 font-normal text-white"><?php echo $c["content"] ?></p>
        </div>
      <?php endforeach; ?>
      </div>
    </div>
    </div>
  </main>
  <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script>
</body>

</html>
