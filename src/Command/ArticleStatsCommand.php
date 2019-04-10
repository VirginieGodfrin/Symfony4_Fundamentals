<?php

// create command with maker Bundle
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ArticleStatsCommand extends Command
{
    protected static $defaultName = 'article:stats';

    protected function configure()
    {
        // Each command can have:
            // a description 
            // arguments: which are strings passed after the command andoptions
            // option: prefixed with -- ex: --format=something
        // add --help to any command to get all the info about it
        $this
            ->setDescription('Hello, here is the article Stats')
            ->addArgument('slug', InputArgument::OPTIONAL, 'The article\'s slug')
            ->addOption('format', null, InputOption::VALUE_REQUIRED, 'Output format', 'text')
        ;
    }

    // Notice that execute() has two arguments: 
        // $input: lets us read arguments and options 
        // $output: is all about printing things
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // SymfonyStyle : a full of fun fun methods !!
        $io = new SymfonyStyle($input, $output);
        // Get the argument value
        $slug = $input->getArgument('slug');

        // data for fun
        $data = [
            'slug' => $slug,
            'hearts' => rand(10, 100),
        ];

        // the logic:
        // let do it with 
            // php bin/console article:stats khaaaaaan
            // php bin/console article:stats khaaaaaan --format=json
        switch ($input->getOption('format')) { 
            case 'text':
                // fun fun mthd : print a list of thing
                $io->listing($data);
                break; 
            case 'json':
                // fun fun mthd : print raw text
                $io->write(json_encode($data));
                break;
            default:
                throw new \Exception('What kind of crazy format is that!?');
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
