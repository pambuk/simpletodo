<?php
namespace ZymTodo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use ZymTodo\Manager\TodoItem;
use ZymTodo\Manager\TodoManager;

class AddTodoCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('t:add')
            ->setDescription('Add todo item')
            ->addArgument(
                'todo',
                InputArgument::REQUIRED,
                "Todo item description #tag @time"
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $_item = $input->getArgument('todo');
        $tm = new TodoManager(new Filesystem());
        $item = new TodoItem($_item);
        $item->replaceDate();
        $tm->saveItem($item);

        $output->writeln('Item added');
    }

}