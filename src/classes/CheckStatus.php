<?php

declare(strict_types=1);

namespace TechWilk\SiteChecks;

class CheckStatus
{
    protected $name;
    protected $status;

    public function __construct(string $name, ?bool $status)
    {
        $this->name = $name;
        $this->status = $status;
    }
}
