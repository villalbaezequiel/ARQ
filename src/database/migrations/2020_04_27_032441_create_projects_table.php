<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('start_date')->nullable();
            $table->string('finish_date')->nullable();
            $table->string('id_client')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('observations')->nullable();
            $table->integer('id_country')->nullable();
            $table->integer('id_province')->nullable();
            $table->string('location')->nullable();
            $table->string('address')->nullable();
            $table->integer('address_number')->nullable();
            $table->string('piso_depto')->nullable();
            $table->integer('cp')->nullable();
            $table->string('is_active')->nullable();
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
        Schema::dropIfExists('projects');
    }
}
