<?php

declare(strict_types=1);

function templateLayout($title, $content) {

    $title = htmlentities($title);

    $layout = <<<HTML
        <!DOCTYPE html>
        <html>
            <head>
                <title>$title</title>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0">

                <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
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

