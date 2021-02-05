<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class AddColumn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'column:add
            {--t | type= : Type of column}
            {column_name    : Name of column}
            {table_name : Name of table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add column for table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $objectArguments = $this->arguments();
        $objectOptions = $this->options();
        $table_name = $objectArguments['table_name'];
        $column_name = $objectArguments['column_name'];
        $type = $objectOptions['type'];
        if(Schema::hasTable($table_name)){
            $this->info("Table existed!");
        }
        else{
            $this->error("Table doesn't exist!");
        }
    }
}
