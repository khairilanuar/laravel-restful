<?php

declare(strict_types=1);
if (! function_exists('xdebug_set_filter')) {
    throw new Exception('xdebug_set_filter not available on system');
}

xdebug_set_filter(
    \XDEBUG_FILTER_CODE_COVERAGE,
    \XDEBUG_PATH_WHITELIST,
    [
        __DIR__.'/src/Util',
    ]
);
