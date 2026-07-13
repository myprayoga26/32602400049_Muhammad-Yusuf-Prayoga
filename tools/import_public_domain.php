<?php

$books = [
    13 => 'https://www.gutenberg.org/files/1342/1342-0.txt',
    14 => 'https://www.gutenberg.org/files/84/84-0.txt',
    15 => 'https://www.gutenberg.org/files/11/11-0.txt',
    16 => 'https://www.gutenberg.org/files/35/35-0.txt',
];

$mysqli = new mysqli('localhost', 'root', '', 'literia_app');
if ($mysqli->connect_errno) {
    fwrite(STDERR, "Database connection failed: {$mysqli->connect_error}\n");
    exit(1);
}

$mysqli->set_charset('utf8mb4');

function fetchText(string $url): string
{
    $context = stream_context_create([
        'http' => [
            'timeout' => 30,
            'header' => "User-Agent: LITERIA local development importer\r\n",
        ],
    ]);

    $text = file_get_contents($url, false, $context);
    if ($text === false) {
        throw new RuntimeException("Failed to download {$url}");
    }

    $text = preg_replace('/^\xEF\xBB\xBF/', '', $text);
    $text = str_replace(["\r\n", "\r"], "\n", $text);

    $startPatterns = [
        '/\*\*\* START OF (?:THE|THIS) PROJECT GUTENBERG EBOOK .*?\*\*\*/is',
        '/\*\*\* START OF THE PROJECT GUTENBERG EBOOK .*?\*\*\*/is',
    ];

    foreach ($startPatterns as $pattern) {
        if (preg_match($pattern, $text, $match, PREG_OFFSET_CAPTURE)) {
            $text = substr($text, $match[0][1] + strlen($match[0][0]));
            break;
        }
    }

    $endPatterns = [
        '/\*\*\* END OF (?:THE|THIS) PROJECT GUTENBERG EBOOK .*?\*\*\*/is',
        '/End of the Project Gutenberg.*$/is',
    ];

    foreach ($endPatterns as $pattern) {
        if (preg_match($pattern, $text, $match, PREG_OFFSET_CAPTURE)) {
            $text = substr($text, 0, $match[0][1]);
            break;
        }
    }

    $text = preg_replace("/[ \t]+\n/", "\n", $text);
    $text = preg_replace("/\n{4,}/", "\n\n\n", $text);

    return trim($text);
}

$stmt = $mysqli->prepare("UPDATE buku SET reading_text = ?, read_access = 'public_domain', source_name = 'Project Gutenberg' WHERE id_buku = ?");
if (!$stmt) {
    fwrite(STDERR, "Prepare failed: {$mysqli->error}\n");
    exit(1);
}

foreach ($books as $id => $url) {
    try {
        $text = fetchText($url);
        $stmt->bind_param('si', $text, $id);
        $stmt->execute();
        echo "Imported book {$id}: " . strlen($text) . " bytes\n";
    } catch (Throwable $e) {
        fwrite(STDERR, "Book {$id} failed: {$e->getMessage()}\n");
    }
}

$stmt->close();
$mysqli->close();
