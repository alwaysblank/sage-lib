<?php

namespace Roots\Sage\Template;

use Illuminate\Console\OutputStyle;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends BaseApplication
{
    public function __construct($name = 'Sage Templates', $version = '1.0.0')
    {
        $this->isWordPress();
        parent::__construct($name, $version);
    }

    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        $input = $input ?: new ArgvInput();
        $output = new OutputStyle($input, $output ?: new ConsoleOutput);
        parent::run($input, $output);
    }

    protected function isWordPress()
    {

        if (!array_reduce(
            ['/resources/style.css', '/package.json', '/composer.json'],
            function ($carry, $file) {
                return $carry && file_exists(getcwd().$file);
            },
            true
        )) {
            (new OutputStyle(new ArgvInput(), new ConsoleOutput))
                ->block("sage-lib must be called from your theme root.", null, 'error', '  ', true);
            die();
        }
    }
}
