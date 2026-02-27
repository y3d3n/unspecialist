<?php

header("Content-Type: application/json");

// CONFIG
$rss_url = "https://rss.beehiiv.com/feeds/bGv92l1wSR.xml";
$cache_file = __DIR__ . "/rss-cache.json";
$cache_time = 600; // 10 minutes

// Serve cache if fresh
if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_time) {
    echo file_get_contents($cache_file);
    exit;
}

$rss = simplexml_load_file($rss_url, null, LIBXML_NOCDATA);

if (!$rss) {
    if (file_exists($cache_file)) {
        echo file_get_contents($cache_file);
        exit;
    }
    echo json_encode(["error" => "Unable to fetch RSS"]);
    exit;
}

$items = [];
$count = 0;

foreach ($rss->channel->item as $item) {
    if ($count >= 5)
        break;

    $namespaces = $item->getNamespaces(true);
    $image = "";

    // 1️⃣ Try media:content
    if (isset($namespaces['media'])) {
        $media = $item->children($namespaces['media']);
        if (isset($media->content)) {
            $image = (string) $media->content->attributes()->url;
        } elseif (isset($media->thumbnail)) {
            $image = (string) $media->thumbnail->attributes()->url;
        }
    }

    // 2️⃣ If no media image, try extracting from description
    if (!$image) {
        $description = (string) $item->description;
        if (preg_match('/<img.+src=["\'](.+?)["\']/', $description, $matches)) {
            $image = $matches[1];
        }
    }

    $items[] = [
        "title" => (string) $item->title,
        "link" => (string) $item->link,
        "pubDate" => date("F j, Y", strtotime((string) $item->pubDate)),
        "description" => strip_tags((string) $item->description),
        "image" => $image
    ];

    $count++;
}

$json_output = json_encode($items);
file_put_contents($cache_file, $json_output);

echo $json_output;