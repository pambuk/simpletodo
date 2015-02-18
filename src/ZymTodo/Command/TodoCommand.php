<?php
namespace ZymTodo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use ZymTodo\Manager\TodoManager;

class TodoCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('t:list')
            ->setDescription('List todo items')
            ->addArgument(
                'extra',
                InputArgument::OPTIONAL,
                "archived | @date | #tag"
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tm = new TodoManager(new Filesystem());
        $tm->setExtra($input->getArgument('extra'));

        $todoList = $tm->getItems();
        if ($todoList->isLoaded()) {            
            $todoList->reverse();
            
            foreach ($todoList as $k => $item) {
                if ($item->render()) {
                    $output->writeln($item->render());
                }
            }
        } else {
            $output->writeln("Todo list is empty");
        }
    }

}