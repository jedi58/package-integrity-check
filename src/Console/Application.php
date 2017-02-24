<?php
namespace Inachis\Component\FileIntegrityCheck\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Inachis\Component\FileIntegrityCheck\Console\Command\CreateCommand;
use Inachis\Component\FileIntegrityCheck\Console\Command\VerifyCommand;

/**
 * Application class for handling console access to Jira
 */
class Application extends BaseApplication
{
    const NAME = 'File Integrity Checker';
    const VERSION = '1.0.0';

    public function __construct()
    {
        parent::__construct(static::NAME, static::VERSION);

        $this->addCommands(array(
            new CreateComment(),
            new VerifyCommand()
        ));
    }
}
