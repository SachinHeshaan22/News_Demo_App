<?php

// Test script to verify all API endpoints
echo "Testing News API Endpoints\n";
echo "=========================\n\n";

$baseUrl = 'http://127.0.0.1:8000/api';

// Test 1: Get all news
echo "1. Testing GET /news\n";
$response = file_get_contents($baseUrl . '/news');
$data = json_decode($response, true);
echo "Status: " . (isset($data['success']) && $data['success'] ? "SUCCESS" : "FAILED") . "\n";
echo "News items found: " . count($data['data'] ?? []) . "\n\n";

// Test 2: Get specific news
echo "2. Testing GET /news/1\n";
$response = file_get_contents($baseUrl . '/news/1');
$data = json_decode($response, true);
echo "Status: " . (isset($data['success']) && $data['success'] ? "SUCCESS" : "FAILED") . "\n";
echo "Title: " . ($data['data']['title'] ?? 'Not found') . "\n\n";

// Test 3: Test validation error
echo "3. Testing POST /news with validation error\n";
$postData = json_encode([
    'title' => '', // Empty title should fail validation
    'category' => 'technology',
    'url' => 'https://example.com',
    'date' => '2026-02-12'
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => $postData
    ]
]);

$response = @file_get_contents($baseUrl . '/news', false, $context);
$data = json_decode($response, true);
echo "Status: " . (isset($data['success']) && !$data['success'] ? "SUCCESS (Validation error caught)" : "UNEXPECTED") . "\n";
if (isset($data['errors'])) {
    echo "Validation errors: " . json_encode($data['errors']) . "\n";
}
echo "\n";

echo "API Tests Completed!\n";