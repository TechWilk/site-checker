<?php

declare(strict_types=1);

namespace TechWilk\SiteChecks;

interface SiteCheck
{
    /**
     * @return SiteCheck[]
     */
    public function run(string $siteUrl): array;
}
