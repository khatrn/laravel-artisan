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
            {--i | isNull= : Null or not null(Choose y or n)}
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
        $type = $objectOptions['type'] != null ? $objectOptions['type'] : 'text';
        if($objectOptions['isNull']=='y'){
            $isNull = '->nullable()';
        }
        else if($objectOptions['isNull']=='n'){
            $isNull = '';
        }
        else{
            $this->error('Only choose y or n');
        }

        if(Schema::hasTable($table_name)){
            if(Schema::hasColumn($table_name,$column_name)){
                $this->info("Column '$column_name' in table '$table_name' existed!");
            }
            else{
                //$this->error("Column '$column_name' in table '$table_name' doesn't exist!");
                $path = base_path()."/database/migrations/".date('Y_m_d_His')."_add_column_$column_name"."_for_table_$table_name.php";
                $myfile = fopen($path,"w");
                $txt = "<?php

                    use Illuminate\Database\Migrations\Migration;
                    use Illuminate\Database\Schema\Blueprint;
                    use Illuminate\Support\Facades\Schema;

                    class AddColumn".ucfirst($column_name)."ForTable".ucfirst($table_name)." extends Migration
                    {
                        /**
                         * Run the migrations.
                         *
                         * @return void
                         */
                        public function up()
                        {
                            Schema::table('$table_name', function (Blueprint \$table) {
                                //
                                \$table->$type('$column_name')$isNull;
                            });
                        }

                        /**
                         * Reverse the migrations.
                         *
                         * @return void
                         */
                        public function down()
                        {
                            Schema::table('users', function (Blueprint \$table) {
                                //
                            });
                        }
                    }";
                fwrite($myfile,$txt);
                fclose($myfile);
            }
        }
        else{
            $this->error("Table '$table_name' existed!");
        }
    }
}
