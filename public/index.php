<?php

use App\Core\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

ini_set('max_execution_time', 0);

return static function (array $context): Kernel {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
