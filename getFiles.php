<?php

require_once 'config.php';
require_once UTILS . '/Files.php';

$files = new Files();

if (!empty($_GET['folder'])) {
    $files->getThumbnails($_GET['folder']);
}
else {
    header('Content-Type: application/json');
    echo json_encode(array(
        'error' => 'No folder was set to retrieve files from'
    ));
}
