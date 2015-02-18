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
        if (\strlen($_item) > 0) {
            $tm = new TodoManager(new Filesystem());
            $item = new TodoItem($_item);
            $item->replaceDate();
            
            $number = (count($tm->getItems()) > 0) ? count($tm->getItems()) : 1;
            
            $f = \fopen('storage/'. TodoManager::ITEMS, 'a');
            \fputs($f, $number .'. '. \trim($item->render()) ."\n");
            \fclose($f);
            
            $output->writeln('Item added');
        }
    }

}