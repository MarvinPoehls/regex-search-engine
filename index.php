<?php

const PAGE_COUNT = 3;
$engine = getRequestParameter('engine');
$query = getRequestParameter('query');
$config = [
    'www.google.com' => [
        'searchDivId' => 'main',
        'pattern' => '/<a[^>]+href="\/url\?q=([^&]+)&[^>]*>(?:(?!<\/a>).)*?<h3[^>]*><div[^>]+>(.*?)<\/div><\/h3.*?(?<=<div class="BNeawe s3v9rd AP7Wnd">).*?<div class="BNeawe s3v9rd AP7Wnd">(.*?)(?=<\/div><\/div><\/div>)/',
        'utf8_encode' => true,
        'pagination_parameter' => 'start',
    ],
    'www.bing.com' => [
        'searchDivId' => 'b_results',
        'pattern' => '/<li class="b_algo".*?<h2[^>]*><a.*?href="([^"]*)"[^>]*>(.*?)<\/a><\/h2>.*?(?<=<p class="b_lineclamp\d b_algoSlug"><span class="algoSlug_icon" data-priority="\d">WEB<\/span>)(.*?)(?=<\/p>)/',
        'utf8_encode' => false,
        'pagination_parameter' => 'first',
    ],
];

if (isset($config[$engine])) {
    $config = $config[$engine];
}

if ($engine && $query) {
    $matches = [];
    for ($i = 0; $i < PAGE_COUNT * 10; $i += 10) {
        $url = "https://".$engine."/search?q=".urlencode($query)."&".$config['pagination_parameter']."=".$i;

        $content = getContent($url, $config['searchDivId']);

        preg_match_all($config['pattern'], $content, $match);

        foreach ($match as $key => $value) {
            $matches[$key] = isset($matches[$key]) ? array_merge($matches[$key], $value) : $value;
        }
    }

    foreach ($matches as $key => $selection)
        foreach ($selection as $index => $text)
            $matches[$key][$index] = strip_tags($text);
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

