<?php
if (getenv('DEBUG') === 'true') {
    echo "Contents of composer.json:\n";
    echo file_get_contents(__DIR__ . '/composer.json');
    echo "\n\nJSON validation:\n";
    echo json_decode(file_get_contents(__DIR__ . '/composer.json')) ? "Valid JSON" : "Invalid JSON: " . json_last_error_msg();
    exit;
}