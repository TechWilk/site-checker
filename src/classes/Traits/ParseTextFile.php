<?php

declare(strict_types=1);

namespace TechWilk\SiteChecks\Traits;

trait ParseTextFile
{
    protected function parseTextFile(string $text): array
    {
        // remove comments
        $text = preg_replace('/#.*$/m', '', $text);

        // todo: consider pgp signatures on security.txt

        $lines = explode(PHP_EOL, $text);

        $parsed = [];
        foreach ($lines as $line) {
            if (empty(trim($line))) {
                continue;
            }

            $splitLine = explode(':', $line, 2);

            $parsed[trim($splitLine[0])][] = trim($splitLine[1] ?? '');
        }

        return $parsed;
    }
}
