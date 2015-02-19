<?php
namespace ZymTodo;

use Symfony\Component\Console\Application;
use ZymTodo\Command\AddTodoCommand;
use ZymTodo\Command\DoneTodoCommand;
use ZymTodo\Command\TodoCommand;

class TodoApplication extends Application
{
    public function __construct($name = 'Todo', $version = '0.2') {
        parent::__construct($name, $version);
        
        $this->add(new TodoCommand());
        $this->add(new AddTodoCommand());
        $this->add(new DoneTodoCommand());
    }
}
