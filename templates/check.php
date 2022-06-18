<?php

declare(strict_types=1);

require_once __DIR__ . '/layout.php';

function templateCheck(string $site) {

    $site = htmlentities($site, ENT_QUOTES);

    $content = <<<HTML
        <form>
            <label>
                Site
                <input type="text" name="site" value="$site"/>
            </label>
            <input type="submit" value="Run checks" />
        </form>
        HTML;

    return templateLayout('Check', $content);
}

