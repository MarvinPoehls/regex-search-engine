<?php
$SUGGESTION_LIMIT = 3;
$engine = getRequestParameter('engine');
$query = getRequestParameter('query');
$config = [
    'www.google.com' => [
        'searchDivId' => 'main',
        'pattern' => '/<a[^>]+href="\/url\?q=([^&]+)&[^>]*>(?:(?!<\/a>).)*?<h3[^>]*><div[^>]+>(.*?)<\/div><\/h3/',
        'utf8_encode' => true,
    ],
    'www.bing.com' => [
        'searchDivId' => 'b_mcw',
        'pattern' => '/<h2[^>]*><a[^"]*"(.*?)"[^>]*>(.*?)<\/a><\/h2>/',
        'utf8_encode' => false,
    ],
];
$config = $config[$engine];

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

