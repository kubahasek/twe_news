<?php
require_once "utils.php";
if(!isset($_GET["id"])) {
    header("LOCATION: /twe_news/?toast=true&message=Je potřeba ID článku&color=red&redirect=/twe_news");
    die();
}

$article = getArticle($_GET["id"]);
$content = "<h1>" . $article[0]["title"] . "</h1>";
if (isset($article[0]["image"])) {
    $content .= "<img src='" . $article[0]["image"] ."'/>";
}
$content .=  $article[0]["text"];
$stylesheet = file_get_contents('article.css');

// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';
// Create an instance of the class:
$mpdf = new \Mpdf\Mpdf();

// Write some HTML code:
$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($content);

// Output a PDF file directly to the browser
$mpdf->Output(trim($article[0]["title"]).'.pdf', \Mpdf\Output\Destination::DOWNLOAD);