<?php

namespace Inachis\Component\PackageIntegrityChecker\Console\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Inachis\Component\PackageIntegrityChecker\PackageChecksum;
use Inachis\Component\PackageIntegrityChecker\Console\Command\CommandAbstract;

/**
 * Defines the create command for the console application
 */
class CreateCommand extends CommandAbstract
{
    /**
     * Configuration for the console command
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('create')
            ->setDescription('Creates a checksum file for use in verifying package integrity')
            ->addOption('path', null, InputOption::VALUE_OPTIONAL, 'The path to the package to create checksum file for');
    }
    /**
     * Configures the interactive part of the console application
     * @param InputInterface $input The console input object
     * @param OutputInterface $output The console output object
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (empty($input->getOption('path'))) {
            $input->setOption(
                'path',
                '.'
            );
        }
        $output->writeln(
            sprintf('Creating checksum manifest for %s', $input->getOption('path'))
        );
    }
    /**
     * Creates a checksum manifest file for the target path
     * @param InputInterface $input The console input object
     * @param OutputInterface $output The console output object
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $package = new PackageChecksum();
        $package->createHashesForPath($input->getOption('path'));
        $package->writeToFile();
        $output->writeln(
            sprintf(
                '<info>✓ Done.</info>' . PHP_EOL . 'Checksum manifest for %d files created',
                $package->getNumFilesChecked()
            )
        );
    }
}
