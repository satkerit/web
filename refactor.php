<?php

$file = 'resources/views/frontend/home.blade.php';
$content = file_get_contents($file);

$lines = explode("\n", $content);
foreach ($lines as $i => $line) {
    if (strpos($line, '<!--') !== false || strpos($line, '{{--') !== false) {
        echo ($i + 1) . ": " . trim($line) . "\n";
    }
}
