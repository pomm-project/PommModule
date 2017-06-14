#!/usr/bin/env php
<?php
$cliConfig = require __DIR__ . '/../config/cli.config.php';
ini_set('display_errors', true);
// Setup autoloading
require __DIR__ . '/../../../autoload.php';

// Run the application!
Zend\Mvc\Application::init($cliConfig)->run();
