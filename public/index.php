<?php

// Enable error reporting for development
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Load the app config
require_once __DIR__ . '/../app/config.php';

// Include the core bootstrap
require_once CORE_PATH . '/App.php';

// Run the application (bootstrap + router)
App::run();
