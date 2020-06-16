<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectRealCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_real_costs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_project');

            // information get from project_budgets
            $table->string('id_item');
            $table->string('id_subitem');
            $table->integer('quantity');
            $table->float('unit_cost', 10, 2);
            $table->float('tax', 5, 2);
            $table->float('budget_total', 10, 2);

            $table->float('paid', 10, 2);
            $table->float('balance_paid', 10, 2);
            $table->float('balance_item', 10, 2);
            $table->float('budget_variation_pesos', 10, 2);
            $table->float('budget_variation_tax', 10, 2);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_real_costs');
    }
}
