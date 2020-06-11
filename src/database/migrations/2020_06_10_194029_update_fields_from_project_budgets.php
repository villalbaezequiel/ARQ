<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateFieldsFromProjectBudgets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        
        Schema::table('project_budgets', function (Blueprint $table) {
            $table->dropColumn('tax');
        });

        Schema::table('project_budgets', function (Blueprint $table) {
            $table->string('unit')->change();
            $table->string('id_item')->after('id_project');
            $table->float('tax', 5, 2)->after('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
