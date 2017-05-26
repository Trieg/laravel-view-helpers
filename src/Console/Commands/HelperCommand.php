<?php

/**
 * This file is part of the Laravel View Helpers package.
 *
 * @website http://cubes.rs/
 * @author  Djordje Stojiljkovic <djordje.stojiljkovic@cubes.rs>
 *
 */

namespace Cubes\View\Helpers\Console\Commands;

use Illuminate\Console\Command;

class HelperCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:helper {name} {--dummy=} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Helper class';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get arguments
        $helperName = $this->argument('name');
        $dummyData  = $this->option('dummy');
        $answer     = $this->confirm(
            'Helper with name ' . ucfirst($helperName) . ' will be created. Continue [y|n]?'
        );

        if ($answer === true) {
            $config     = app()->make('config');
            $helpersDir = app_path()  . '/' . $config->get('helpers.directory', 'Helpers');
            $helperPath = $helpersDir . '/' . ucfirst($helperName) . '.php';

            if (file_exists($helperPath)) {
                $this->error('Oops, Helper with name: ' . ucfirst($helperName) . ' already exist.');
                exit();
            }

            if (!file_exists($helpersDir)) {
                mkdir($helpersDir, 0777, true);
            }

            if ($this->isWritable($helpersDir) === true) {

                // show some animated progress
                $this->showProgress();

                // if dummy data is triggered
                if ($dummyData == true) {
                    $helperClassPlaceholder = str_replace(
                        'Placeholder',
                        ucfirst($helperName),
                        file_get_contents(__DIR__.'/../../Resources/Placeholder.php')
                    );
                    $fp = fopen($helperPath, "wb");
                    fwrite($fp, $helperClassPlaceholder);
                    fclose($fp);
                    $this->info('Dummy methods extracted successfully.');
                } else {
                    $fp = fopen($helperPath, "wb");
                    fwrite(
                        $fp,
                        str_replace(
                            'PlaceholderEmpty',
                            ucfirst($helperName),
                            file_get_contents(__DIR__ . '/../../Resources/PlaceholderEmpty.php')
                        )
                    );
                }
                $this->info('Helper class: ' . ucfirst($helperName) . ' created successfully. Happy coding!' );
            }

        }
    }

    /**
     * isWritable method check if passed directory is writable.
     *
     * @param $helpersDir
     * @return mixed
     */
    private function isWritable($helpersDir)
    {
        if (is_writable($helpersDir)) {
            return true;
        }

        $this->error(
            'Oops, make sure you grant permission for Helpers directory because currently you don\'t have one.'
        ); exit();
    }

    /**
     * showProgress method used to show animated progress during helper creation.
     */
    private function showProgress()
    {
        $this->output->progressStart(3);
        for ($i = 0; $i < 3; $i++) {
            sleep(1);
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
    }
}
