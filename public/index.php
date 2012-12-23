<?php

// Define path to application directory
defined('APPLICATION_PATH')
        || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define path to application directory
defined('BASE_PATH')
        || define('BASE_PATH', realpath(dirname(__FILE__) . '/..'));
// Define path to upload directory
defined('UPLOAD_PATH')
        || define('UPLOAD_PATH', realpath(dirname(__FILE__) . '/destiny/admin/uploads'));

// Define application environment
defined('APPLICATION_ENV')
        || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
            realpath(APPLICATION_PATH . '/../library'),
            get_include_path(),
        )));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
                APPLICATION_ENV,
                BASE_PATH . '/configs/application.ini'
);
$application->bootstrap()
        ->run();