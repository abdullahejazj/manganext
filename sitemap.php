<?php

// Function to fetch manga data from AniList GraphQL API
function fetchMangaData()
{
    $url = 'https://graphql.anilist.co';
    $query = '{
        Page(perPage: 100, page: 1) {
            pageInfo {
                total
                perPage
                currentPage
                lastPage
                hasNextPage
            }
            media(type: MANGA, sort: TRENDING_DESC) {
                id
                title {
                    romaji
                }
            }
        }
    }';

    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => json_encode(array('query' => $query)),
        ),
    );

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($response !== false) {
        $data = json_decode($response, true);
        return $data['data']['Page']['media'];
    }

    return [];
}

// Function to generate sitemap XML content
function generateSitemapXml($mangaData)
{
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

    foreach ($mangaData as $manga) {
        $url = 'https://example.com/manga/' . $manga['id']; // Replace 'example.com' with your website domain
        $xml .= "\t<url>" . PHP_EOL;
        $xml .= "\t\t<loc>{$url}</loc>" . PHP_EOL;
        $xml .= "\t</url>" . PHP_EOL;
    }

    $xml .= '</urlset>';
    return $xml;
}

// Fetch manga data from AniList GraphQL API
$mangaData = fetchMangaData();

// Generate sitemap XML content
$sitemapXml = generateSitemapXml($mangaData);

// Send appropriate headers to indicate XML content
header('Content-Type: application/xml');
header('Content-Disposition: attachment; filename="sitemap.xml"');

// Output the sitemap XML content
echo $sitemapXml;
