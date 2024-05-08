<?php

namespace Sokeio\Devtool\Console;

use Illuminate\Console\Command;
use Sokeio\Devtool\GenerateCrud;
use Sokeio\Devtool\GenerateModel;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'devtool:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tableJson = json_decode(file_get_contents(__DIR__ . '/crud_Tag.json'),true);
        // GenerateCrud::generate(2);
        GenerateModel::generate($tableJson['module'], $tableJson['table_name'], 'abc', $tableJson['fields'],true);
        //
        $this->info('ok');
        return 0;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
