<?php
require "utils.php";
session_start();
if (!IsSignedIn() || IsSignedIn() && $_SESSION["user"]["role"] != "admin") {
    header("LOCATION: /twe_news/login.php?msg=needlogin");
    die();
}
if (!empty($_POST) && isset($_POST) && isset($_GET["id"])) {
    updateUser($_GET["id"], $_POST["name"], $_POST["surname"], $_POST["email"], $_POST["role"]);
    header("LOCATION: /twe_news/administration.php");
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
if (isset($_GET["id"])) {
    $user = getUser($_GET["id"]);
    $roles = getAllRoles();
} else {
    header("LOCATION: /twe_news/");
    die();
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
      <h1 class="text-white text-3xl md:text-5xl uppercase font-bold">Upravit uživatele</h1>
      <div class="text-white mt-4">
        <form class="flex flex-col gap-4" action="" method="post">
          <div>
            <label for="userName" class="block mb-2 text-sm font-medium text-white">Jméno</label>
            <input type="text" id="userName" name="name" value="<?php echo isset($_GET["id"]) ? $user[0]["name"] : null ?>" class="bg-dark border border-gray-300 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
          </div>
          <div>
            <label for="userSurname" class="block mb-2 text-sm font-medium text-white">Příjmení</label>
            <input type="text" id="userSurname" name="surname" value="<?php echo isset($_GET["id"]) ? $user[0]["surname"] : null ?>" class="bg-dark border border-gray-300 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
          </div>
          <div>
            <label for="userEmail" class="block mb-2 text-sm font-medium text-white">Email</label>
            <input type="email" id="userEmail" name="email" value="<?php echo isset($_GET["id"]) ? $user[0]["email"] : null ?>" class="bg-dark border border-gray-300 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
          </div>
          <div>
            <label for="roles" class="block mb-2 text-sm font-medium text-white">Role</label>
            <select id="roles" name="role" class="bg-dark border border-gray-300 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
              <?php foreach ($roles as $r) : ?>
                <option value="<?php echo $r['id'] ?>" <?php echo (isset($_GET["id"]) && $user[0]["role_id"] == $r["id"] ? "selected" : "") ?>><?php echo $r["name"] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" class="text-dark bg-yellow hover:bg-violet focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"><?php echo isset($_GET["id"]) ? "Upravit" : "Přidat" ?></button>
        </form>
      </div>
  </main>
  <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script>
</body>

</html>
