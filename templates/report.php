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
            $statusText = $passed ? 'PASSED' : 'FAILED';
            $checksHtml .= <<<CHECK
                <details $boxOpen>
                    <summary>$name ($statusText)</summary>
                    <table>$tasksHtml</table>
                </details>
                CHECK;
        }


        $resultsHtml[] = <<<RESULT
            <h2>$type</h2>
            $checksHtml
            RESULT;
    }

    $content = '<p>Report for <code>'.htmlentities((string)$uri).'</code></p>' . implode('', $resultsHtml);

    return templateLayout('Report', $content);
}

function renderTaskRow($taskName, $result) {
    $result = (
        $result === true
        ? 'PASSED'
        : (
            $result === null
            ? 'skipped'
            : '<strong>FAILED</strong>'
        )
    );

    return <<<TASK
        <tr>
            <td>$taskName</td>
            <td>$result</td>
        </tr>
        TASK;
}

