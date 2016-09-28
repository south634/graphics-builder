<?php

require_once 'config.php';
require_once UTILS . '/Files.php';

if (!isset($_GET['filePaths'])) {
    exit('No file paths were set to create image');
}

// Set up transparent image
$image = imagecreatetruecolor(IMAGE_WIDTH, IMAGE_HEIGHT);
imagesavealpha($image, true);
$transparency = imagecolorallocatealpha($image, 0, 0, 0, 127);
imagefill($image, 0, 0, $transparency);

// Get filePaths. Need to be in reverse order for layering
$filePaths = array_reverse($_GET['filePaths']);

// Add layers to image
foreach ($filePaths as $filePath) {
    $layer = imagecreatefrompng(IMAGES . '/' . $filePath);
    imagecopy($image, $layer, 0, 0, 0, 0, IMAGE_WIDTH, IMAGE_HEIGHT);
}

// Create the png image
imagepng($image, OUTPUT_FILENAME, 9);

// Download png image and delete it from server
if (file_exists(OUTPUT_FILENAME)) {

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename(OUTPUT_FILENAME));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize(OUTPUT_FILENAME));
    readfile(OUTPUT_FILENAME);

    // Delete file from server
    unlink(OUTPUT_FILENAME);
}
