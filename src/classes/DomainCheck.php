<?php

declare(strict_types=1);

namespace TechWilk\SiteChecks;

use League\Uri\Components\Host;

interface DomainCheck
{
    public function run(Host $domain): bool;
}
