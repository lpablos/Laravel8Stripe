<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('amount'); 
            $table->string('billing_details_name');
            $table->date('created');
            $table->string('currency');
            $table->string('stripe_id');
            $table->string('payment_method');
            $table->string('payment_method_card_fingerprint');
            $table->string('status');
            $table->string('outcome_network_status');
            $table->string('outcome_reason')->nullable();
            $table->string('outcome_seller_message'); 
            $table->string('metadata_product_id'); 
            $table->string('source_id'); 
            $table->text('response');            
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
        Schema::dropIfExists('payments');
    }
}
