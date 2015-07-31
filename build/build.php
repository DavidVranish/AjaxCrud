<?php

require __DIR__ . '/../vendor/autoload.php';

use MatthiasMullie\Minify;

$minifier = new Minify\JS(__DIR__ . '/../src/config.js');
$minifier->add(__DIR__ . '/../src/events.js');
$minifier->add(__DIR__ . '/../src/rows.js');
$minifier->add(__DIR__ . '/../src/modals.js');
$minifier->add(__DIR__ . '/../src/sorting.js');
$minifier->add(__DIR__ . '/../src/validation.js');
$minifier->add(__DIR__ . '/../src/end.js');
$minifier->compile(__DIR__ . '/../dist/js/AjaxCrud.js');
$minifier->minify(__DIR__ . '/../dist/js/AjaxCrud.min.js');