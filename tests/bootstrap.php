<?php

$projectRoot = dirname(__DIR__);
require_once($projectRoot . '/vendor/autoload.php');

// Enable AspectMock. This allows us to stub/double out static methods.
$kernel = \AspectMock\Kernel::getInstance();
$kernel->init([
    'debug' => true,
    // Any class that we want to stub/mock needs to be in included paths
    'includePaths' => [
        $projectRoot . '/vendor/simplesamlphp/simplesamlphp/',
    ],
]);
// AspectMock seems to have trouble with SSP's custom class loader. Explicitly load class names like 'ssp_modulename_*'.
//$kernel->loadFile($projectRoot . '/vendor/simplesamlphp/simplesamlphp/lib/SimpleSAML/Auth/State.php');
//$kernel->loadFile($projectRoot . '/vendor/simplesamlphp/simplesamlphp/lib/SimpleSAML/Session.php');

