<?php

declare(strict_types=1);

require_once __DIR__ . '/layout.php';

function templateCheck(string $site) {

    $site = htmlentities($site, ENT_QUOTES);

    $content = <<<HTML
        <p>
            Check for common developer mistakes on a new or existing website and across the domain, including
            <ul>
                <li>Favicon</li>
                <li>404 page</li>
                <li>Robots.txt & Sitemap.xml</li>
                <li><code>./well-known</code> urls</li>
                <!-- <li>HTML validation</li> -->
                <!-- <li>SEO and meta tags</li> -->
                <!-- <li>schema.org enrichment data</li> -->
            </ul>
        </p>
        <form action="/report" method="get">
            <label>
                Site
                <input type="text" name="site" value="$site"/>
            </label>
            <input type="submit" value="Run checks" />
        </form>
        <h2>Why does this exist?</h2>
        <p>
            As a developer, I wanted a <strong>quick way to get a <em>pretty good</em> idea of a site's strengths and weaknesses</strong>.
            Whether it's for a side-project, taking over responsibility or just helping out with an existing website, or as a "go-live" checklist, it's easy to forget or miss important things.
        </p>
        <p>
            Lots of site checkers online are designed for SEO and content producers (and do a great job for them), but they <strong>miss out a lot of the technical checks that developers care about</strong>.
            Instead we need to piece together and run a handful of different tools to get a wholistic picture of how their site is performing.
        </p>
        <p>
            This tool aims to give a <em>broad but wholistic overview of the main issues</em> and highlight areas that have been missed, or need focus.
            It complements, but doesn't replace, other checkers which are still going to be more detailed and up-to-date in their results.
        </p>
        <p>

        </p>
        HTML;

    return templateLayout('Check', $content);
}
