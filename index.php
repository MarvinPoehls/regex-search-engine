<?php
$SUGGESTION_LIMIT = 3;
$engine = getRequestParameter('engine');
$query = getRequestParameter('query');
$config = [
    'www.google.com' => [
        'searchDivId' => 'main',
        'pattern' => '/<a[^>]+href="\/url\?q=([^&]+)&[^>]*>(?:(?!<\/a>).)*?<h3[^>]*><div[^>]+>(.*?)<\/div><\/h3.*?(?<=<div class="BNeawe s3v9rd AP7Wnd">)([^<]+)(?=<\/div><\/div><\/div><\/div><\/div><\/div>)/',
        'utf8_encode' => true,
    ],
    'www.bing.com' => [
        'searchDivId' => 'b_results',
        'pattern' => '/<h2[^>]*><a[^"]*"(.*?)"[^>]*>(.*?)<\/a><\/h2>.*?(?<=<span class="algoSlug_icon" data-priority="2">WEB<\/span>)(.*?)(?=<\/p>)/',
        'utf8_encode' => false,
    ],
];

if (isset($config[$engine])) {
    $config = $config[$engine];
}

if ($engine && $query) {
    $url = "https://".$engine."/search?q=".urlencode($query);

    $content = getContent($url, $config['searchDivId']);

    preg_match_all($config['pattern'], $content, $matches);
}

include "view.php";

function getRequestParameter($key, $default = null) {
    if (isset($_REQUEST[$key])) {
        $value = trim($_REQUEST[$key]);

        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    return $default;
}

function getContent($url, $searchDivId): string
{
    $html = file_get_contents($url);
    $startPos = strpos($html, '<div id="' . $searchDivId . '"');
    $startPos = strpos($html, '>', $startPos) + 1;
    return substr($html, $startPos);
}

