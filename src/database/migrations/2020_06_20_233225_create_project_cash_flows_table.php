<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectCashFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_cash_flows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_project');
            $table->timestamp('date');
            $table->string('type');
            $table->float('amount', 12, 2);
            $table->float('tax', 5, 2)->nullable();
            $table->string('payment_type');
            $table->integer('id_item')->nullable();
            $table->integer('id_subitem')->nullable();
            $table->string('detail')->nullable();
            $table->integer('id_provider')->nullable();
            $table->string('clarification')->nullable();
            $table->integer('id_user');
            $table->integer('id_client')->nullable();
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
        Schema::dropIfExists('project_cash_flows');
    }
}
