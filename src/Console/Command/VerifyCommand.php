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
class VerifyCommand extends CommandAbstract
{
    /**
     * Configuration for the console command
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('verify')
            ->setDescription('Adds a comment to a specified Jira ticket')
            ->addOption('path', null, InputOption::VALUE_OPTIONAL, 'The path to the package to verify. Default \'.\'');
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
            sprintf('Verifying checksum manifest for %s', $input->getOption('path'))
        );
    }
    /**
     * Verifies all hashes in the manifest against the target path
     * @param InputInterface $input The console input object
     * @param OutputInterface $output The console output object
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $package = new PackageChecksum();
        $result = $package->verifyHashesForPath($input->getOption('path'));
        if (!$result) {
            $output->writeln(
                sprintf(
                    '<error>✗ Failed.</error>' . PHP_EOL . '%d of %d files do not match',
                    $package->getNumFailures(),
                    $package->getNumFilesChecked()
                )
            );
            if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                foreach ($package->getFailures() as $failure) {
                    $output->writeln(sprintf('%s does not match', $failure));
                }
            }
            return null;
        }
        $output->writeln(
            sprintf(
                '<info>✓ Done.</info>' . PHP_EOL . '%d files checked successfully',
                $package->getNumFilesChecked()
            )
        );
    }
}
