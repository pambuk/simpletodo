<?php
require __DIR__.'/vendor/autoload.php';

use ZymTodo\TodoApplication;

$application = new TodoApplication();
$application->run();