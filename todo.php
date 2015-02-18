<?php
require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use ZymTodo\Command\AddTodoCommand;
use ZymTodo\Command\DoneTodoCommand;
use ZymTodo\Command\TodoCommand;

$application = new Application("ToDo", "0.1");
$application->add(new TodoCommand());
$application->add(new AddTodoCommand());
$application->add(new DoneTodoCommand());
$application->run();

// http://symfony.com/doc/current/components/console/introduction.html
// http://symfony.com/doc/current/components/console/helpers/questionhelper.html
