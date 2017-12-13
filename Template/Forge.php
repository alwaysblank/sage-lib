<?php

namespace Roots\Sage\Template;

use Illuminate\Console\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Roots\Sage\Container;
use Symfony\Component\Finder\Finder;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Filesystem\Filesystem;
use Roots\Sage\Template\Console\Commands\WipeCommand;

class Forge
{
    /** @var Application */
    public $app;

    public function __construct()
    {
        $app = new Application;
        $app->add(new WipeCommand);
        $this->app = $app;
    }
}
