<?php

require __DIR__ . '/../vendor/autoload.php';

use MatthiasMullie\Minify;

$minifier = new Minify\JS(__DIR__ . '/../src/AjaxCrud.js');
$minifier->minify(__DIR__ . '/../dist/js/AjaxCrud.min.js');