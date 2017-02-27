<?php
namespace Inachis\Component\PackageIntegrityChecker\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Inachis\Component\PackageIntegrityChecker\Console\Command\CreateCommand;
use Inachis\Component\PackageIntegrityChecker\Console\Command\VerifyCommand;

/**
 * Application class
 */
class Application extends BaseApplication
{
    const NAME = 'Package Integrity Checker';
    const VERSION = '1.0.0';

    public function __construct()
    {
        parent::__construct(static::NAME, static::VERSION);

        $this->addCommands(array(
            new CreateCommand(),
            new VerifyCommand()
        ));
    }
}
