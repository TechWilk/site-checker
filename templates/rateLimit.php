<?php

declare(strict_types=1);

require_once __DIR__ . '/layout.php';

function templateRateLimit($uri) {
    $uriString = htmlentities((string)$uri);

    $content = <<<HTML
        <p>Report for <code>$uriString</code></p>
        <a href="/">check another site</a>

        <p>Loading...</p>
        HTML;

    return templateLayout('Report', $content, 5);
}
