<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_user');

            // data contact counter, secretary
            $table->string('contact_last_name')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_dni')->nullable();
            $table->string('contact_telephone')->nullable();
            $table->string('contact_other_telephone')->nullable();
            $table->string('contact_email')->nullable();

            // data billing
            $table->string('billing_razon_social')->nullable();
            $table->string('billing_cuit')->nullable();
            $table->string('billing_gross_income')->nullable();
            $table->enum('billing_condition_iva', [0, 1])->nullable();
            $table->integer('billing_id_country')->nullable();
            $table->integer('billing_id_province')->nullable();
            $table->string('billing_location')->nullable();
            $table->string('billing_address')->nullable();
            $table->integer('billing_address_number')->nullable();
            $table->string('billing_piso_depto')->nullable();
            $table->integer('billing_cp')->nullable();
            
            $table->string('billing_razon_social_2')->nullable();
            $table->string('billing_cuit_2')->nullable();
            $table->string('billing_gross_income_2')->nullable();
            $table->enum('billing_condition_iva_2', [0, 1])->nullable();
            $table->integer('billing_id_country_2')->nullable();
            $table->integer('billing_id_province_2')->nullable();
            $table->string('billing_location_2')->nullable();
            $table->string('billing_address_2')->nullable();
            $table->integer('billing_address_number_2')->nullable();
            $table->string('billing_piso_depto_2')->nullable();
            $table->integer('billing_cp_2')->nullable();

            // data payment
            $table->string('payment_type')->nullable();
            $table->string('payment_card')->nullable();
            $table->string('payment_card_number')->nullable();
            $table->string('payment_card_name')->nullable();
            $table->string('payment_card_date_expiration')->nullable();
            $table->string('payment_card_code_expiration')->nullable();

            $table->string('payment_type_2')->nullable();
            $table->string('payment_card_2')->nullable();
            $table->string('payment_card_number_2')->nullable();
            $table->string('payment_card_name_2')->nullable();
            $table->string('payment_card_date_expiration_2')->nullable();
            $table->string('payment_card_code_expiration_2')->nullable();

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
        Schema::dropIfExists('clients');
    }
}
