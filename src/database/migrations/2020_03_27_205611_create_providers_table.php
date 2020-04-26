<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_user');
            $table->integer('id_category');            
            $table->enum('condition_iva', [0, 1])->nullable();
            $table->enum('retains_gross_income', [0, 1])->nullable();
            $table->string('retains_gross_income_porcentage')->nullable();
            $table->string('invoice_type');

            // data contact
            $table->string('contact_name');
            $table->string('contact_cargo');
            $table->string('contact_telephone');
            $table->string('contact_email');
            
            $table->string('contact_name_2')->nullable();
            $table->string('contact_cargo_2')->nullable();
            $table->string('contact_telephone_2')->nullable();
            $table->string('contact_email_2')->nullable();

            $table->string('contact_name_3')->nullable();
            $table->string('contact_cargo_3')->nullable();
            $table->string('contact_telephone_3')->nullable();
            $table->string('contact_email_3')->nullable();

            // data bank
            $table->string('bank')->nullable();
            $table->string('type_account')->nullable();
            $table->string('number_account')->nullable();
            $table->string('cbu')->nullable();
            $table->string('alias_account')->nullable();

            $table->string('bank_2')->nullable();
            $table->string('type_account_2')->nullable();
            $table->string('number_account_2')->nullable();
            $table->string('cbu_2')->nullable();
            $table->string('alias_account_2')->nullable();

            $table->string('bank_3')->nullable();
            $table->string('type_account_3')->nullable();
            $table->string('number_account_3')->nullable();
            $table->string('cbu_3')->nullable();
            $table->string('alias_account_3')->nullable();

            // data secure
            $table->string('secure_number_poliza')->nullable();
            $table->timestamp('secure_expiration')->nullable();

            $table->string('secure_number_poliza_2')->nullable();
            $table->timestamp('secure_expiration_2')->nullable();

            $table->string('secure_number_poliza_3')->nullable();
            $table->timestamp('secure_expiration_3')->nullable();

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
        Schema::dropIfExists('providers');
    }
}
