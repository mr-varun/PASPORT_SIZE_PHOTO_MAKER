<?php
// removebg.php
// Simple proxy to call remove.bg API. Does not save files to disk.
// Configure your API key by setting the environment variable REMOVE_BG_API_KEY
// or edit $apiKey below (not recommended for public repos).

error_reporting(0);
header('Access-Control-Allow-Origin: same-origin');

$apiKey = getenv('REMOVE_BG_API_KEY') ?: null;

if (empty($apiKey)) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Remove.bg API key not configured. Set REMOVE_BG_API_KEY environment variable.']);
    exit;
}

if (empty($_FILES['image']) || !is_uploaded_file($_FILES['image']['tmp_name'])) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No image uploaded']);
    exit;
}

$file = $_FILES['image'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.remove.bg/v1.0/removebg');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [ 'X-Api-Key: ' . $apiKey ]);

$post = [
    'image_file' => new CURLFile($file['tmp_name'], $file['type'], $file['name']),
    'size' => 'auto'
];
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

if (curl_errno($ch)) {
    http_response_code(502);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Request failed', 'details' => curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

if ($httpCode !== 200) {
    http_response_code($httpCode ?: 500);
    header('Content-Type: application/json');
    // API returns JSON error message; forward it
    echo $response;
    exit;
}

// Success — return raw image data
if ($contentType) header('Content-Type: ' . $contentType);
echo $response;
exit;
