<?php

declare(strict_types=1);

function writeLineToLogFile(string $file, string $line): void
{
    $fp = fopen($file, 'a');

    fwrite($fp, $line.PHP_EOL);
    fclose($fp);
}


function getLastLineOfFile(string $file): string
{
    if (!file_exists($file)) {
        return '';
    }
    $fp = fopen($file, 'r');

    $pos = -1;
    $line = '';
    $c = '';

    do {
        if ($c !== PHP_EOL) {
            $line = $c . $line;
        }
        fseek($fp, $pos--, SEEK_END);
        $c = fgetc($fp);

    } while (!(strlen($line) && $c === PHP_EOL) && $c !== false);

    fclose($fp);

    return $line;
}
