<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_budgets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_project');
            $table->string('id_subitem');
            $table->integer('unit');
            $table->integer('quantity');
            $table->float('unit_cost', 10, 2);
            $table->float('subtotal', 10, 2);
            $table->float('tax', 3, 2);
            $table->float('total', 10, 2);
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
        Schema::dropIfExists('project_budgets');
    }
}
