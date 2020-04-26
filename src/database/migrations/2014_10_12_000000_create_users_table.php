<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('type_dni')->nullable();
            $table->string('dni')->nullable();
            $table->string('cuit')->nullable();
            $table->string('gender')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('birthdate')->nullable();
            $table->integer('id_study')->nullable();
            $table->string('id_nationality')->nullable();
            $table->integer('id_country')->nullable();
            $table->integer('id_province')->nullable();
            $table->string('location')->nullable();
            $table->string('address')->nullable();
            $table->integer('address_number')->nullable();
            $table->string('piso_depto')->nullable();
            $table->integer('cp')->nullable();
            $table->string('telephone')->nullable();
            $table->string('other_telephone')->nullable();
            $table->enum('visa', [0, 1])->nullable();
            $table->string('permissions')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('is_client', [0, 1])->nullable();
            $table->enum('is_provider', [0, 1])->nullable();
            $table->enum('is_active', [1, 0])->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
