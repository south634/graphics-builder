<?php

define('UTILS', __DIR__ . '/utils');
define('IMAGES', __DIR__ . '/images');

define('IMAGE_WIDTH', 640);
define('IMAGE_HEIGHT', 480);
define('OUTPUT_FILENAME', 'image.png');

define('ALLOWED_FILES', serialize(array(
    'jpg',
    'jpeg',
    'png'
)));
