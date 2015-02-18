<?php
namespace ZymTodo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use ZymTodo\Manager\TodoManager;

/**
 * @todo finish several todos at once
 */
class DoneTodoCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('t:done')
            ->setDescription('Finish todo')
            ->addArgument(
                'number',
                InputArgument::REQUIRED,
                "Todo number from the active todos list"
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $number = $input->getArgument('number');
        $tm = new TodoManager(new Filesystem());
            
        if ($tm->removeByNumber($number)) {
            $output->writeln("Todo finished - good job!");
        } else {
            $output->writeln("Can't find todo with this number, check again.");
        }
    }

}