<?php

declare(strict_types=1);

function templateLayout(string $title, string $content, ?int $reload = 0) {

    $title = htmlentities($title);

    $reloadHtml = '';
    if ($reload > 0) {
        $reloadHtml = '<meta http-equiv="refresh" content="'.$reload.'" />';
    }

    $layout = <<<HTML
        <!DOCTYPE html>
        <html>
            <head>
                <title>$title</title>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0">

                <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
                $reloadHtml
            </head>
            <body>
                <header>
                    <h1>Site checker</h1>
                </header>

                <main>
                    $content
                </main>

                <footer>
                    <p>a <a href="https://wilk.tech">wilk.tech</a> site</p>
                </footer>
            </body>
        </html>
        HTML;

    return $layout;
}

