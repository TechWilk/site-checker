<?php

declare(strict_types=1);

require_once __DIR__ . '/layout.php';

function templateReport($results) {

    $textResults = json_encode($results);

    $resultsHtml = [];

    foreach ($results as $type => $checks) {

        $checksHtml = '';
        foreach ($checks as $name => $tasks) {

            $tasksHtml = '';
            foreach ($tasks as $taskName => $result) {
                $tasksHtml .= renderTaskRow($taskName, $result);
            }
            $checksHtml .= <<<CHECK
                <tr>
                    <td colspan="2">$name</td>
                </tr>
                $tasksHtml
                CHECK;
        }


        $resultsHtml[] = <<<RESULT
            <h2>$type</h2>
            <table>$checksHtml</table>
            RESULT;
    }

    $content = implode('', $resultsHtml);

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

