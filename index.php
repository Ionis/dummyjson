<?php
require_once 'db.php';
require_once 'vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

function fetchAndSaveData($type, $searchRequest = ''): void
{
    $client = HttpClient::create();
    $url = 'https://dummyjson.com/' . $type . '/search?q=' . urlencode($searchRequest);

    try {
        $response = $client->request('GET', $url);
        $data = $response->toArray();

        foreach ($data[$type] as $item) {
            if ($type == 'products') {
                saveProduct([
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'brand' => $item['brand'],
                    'category' => $item['category'],
                    'image' => $item['thumbnail']
                ]);
            }
            // Для других типов данных
            // if ($type == 'users') { ... }
        }

        echo ucfirst($type) . " успешно сохранены.";
    } catch (TransportExceptionInterface $e) {
        echo "Ошибка при получении товаров.: " . $e->getMessage();
    }
}

// Получение и сохранение всех продуктов iPhone
fetchAndSaveData('products', 'iPhone');