<?php

declare(strict_types=1);

require_once __DIR__ . '/layout.php';

function templateReport($uri, $results) {

    $textResults = json_encode($results);

    $resultsHtml = [];

    foreach ($results as $type => $checks) {

        $checksHtml = '';
        foreach ($checks as $name => $tasks) {

            $passed = true;
            $tasksHtml = '';
            foreach ($tasks as $taskName => $result) {
                $passed = $result === false ? false : $passed;
                $tasksHtml .= renderTaskRow($taskName, $result);
            }

            $boxOpen = $passed ? '' : 'open';
            $statusText = $passed ? 'PASSED' : '<mark>FAILED</mark>';
            $checksHtml .= <<<CHECK
                <details $boxOpen>
                    <summary>$name ($statusText)</summary>
                    <table>
                        <tr>
                            <th>Check</th>
                            <th>Status</th>
                        </tr>
                        $tasksHtml
                    </table>
                </details>
                CHECK;
        }


        $resultsHtml[] = <<<RESULT
            <h2>$type</h2>
            $checksHtml
            RESULT;
    }

    $uriString = htmlentities((string)$uri);
    $resultsHtmlString = implode('', $resultsHtml);

    $content = <<<HTML
        <p>Report for <code>$uriString</code></p>
        <a href="/">check another site</a>
        $resultsHtmlString
        HTML;

    return templateLayout('Report', $content);
}

function renderTaskRow($taskName, $result) {
    $result = (
        $result === true
        ? 'PASSED'
        : (
            $result === null
            ? 'skipped'
            : 'FAILED'
        )
    );

    return <<<TASK
        <tr>
            <td>$taskName</td>
            <td>$result</td>
        </tr>
        TASK;
}

