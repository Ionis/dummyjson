<?php
require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('.');
$dotenv->load();

$mysqli = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

function saveProduct($product): void
{
    global $mysqli;
    $stmt = $mysqli->prepare("INSERT INTO products (id, title, description, price, brand, category, image) VALUES (?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description), price = VALUES(price), brand = VALUES(brand), category = VALUES(category), image = VALUES(image)");
    $stmt->bind_param("issdsss", $product['id'], $product['title'], $product['description'], $product['price'], $product['brand'], $product['category'], $product['image']);
    $stmt->execute();
}