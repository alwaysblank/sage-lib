<?php

namespace Roots\Sage\Template\Console\Commands;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class WipeCommand extends Command
{
    /** @var Collection */
    protected $directory;

    /** {@inheritdoc} */
    protected $description = 'Remove your Blade templates.';

    /** {@inheritdoc} */
    protected function configure()
    {
        parent::configure();
        $this->default = 'cache/blade';
        $this->addOption(
            'directory',
            null,
            InputOption::VALUE_REQUIRED,
            "Cache directory (from theme root) <comment>[default: \"{$this->default}\"]</comment>",
            null
        );
    }

    /**
     * Compile the full path to our directory, and make sure it
     * exists.
     *
     * @param string $directory
     * @return string|void
     */
    private function determineDirectory($directory = false)
    {
        $user_dir_full = false;

        // If we were passed a directory, process it a little bit.
        if ($directory) :
            $user_dir = trim($directory, " /\\");
            $user_dir_full = sprintf("%s/%s", $this->root, $directory);
        endif;

        // Return the user directory, if set.
        // Otherwise, return the default directory.
        if ($user_dir_full) :
            if (!file_exists($user_dir_full)) :
                $this->error("Your directory `{$user_dir_full}` does not exist!");
                die();
            endif;
            return $user_dir_full;
        else :
            return sprintf("%/%", $this->root, $this->default);
        endif;
    }

    private function wipe($directory)
    {
        $finder = new Finder();
        $filesystem = new Filesystem();
        $all_files = array_map(function ($file) {
            return $file->getRealPath();
        }, iterator_to_array($finder->files()->in($directory)));

        $success = $filesystem->delete($all_files);

        if ($success) {
            $this->line(sprintf("All files in `%s` removed!", $directory));
        } else {
            $this->error("Uh-oh, something went wrong!");
            die();
        }
    }


    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $real_directory = $this->determineDirectory(
            $this->option('directory') ?: $this->default
        );

        $this->wipe($real_directory);
    }
}
