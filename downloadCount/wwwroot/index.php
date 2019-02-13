<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', __DIR__ . DS);
define('APP_PATH',  dirname(__DIR__).DS.'application'.DS);
define('THINK_PATH', realpath(__DIR__ . '/../think5_framework').DS);
define('EXTEND_PATH',   APP_PATH . 'extend' . DS);
define('VENDOR_PATH',   APP_PATH . 'vendor' . DS);
define('RUNTIME_PATH',  APP_PATH . 'runtime' . DS);

require THINK_PATH.'start.php';
