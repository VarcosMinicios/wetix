<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkout_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->string('charge_id')->nullable();
            $table->string('price')->nullable();
            $table->string('tax')->nullable();
            $table->string('commission')->nullable();
            $table->string('quantity')->nullable();
            $table->string('discount')->nullable();
            $table->string('total_early_bird_dicount')->nullable();
            $table->string('currencyText')->nullable();
            $table->string('currencyTextPosition')->nullable();
            $table->string('currencySymbol')->nullable();
            $table->string('currencySymbolPosition')->nullable();
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('address')->nullable();
            $table->string('paymentMethod')->nullable();
            $table->string('gatewayType')->nullable();
            $table->string('paymentStatus')->nullable();
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
        Schema::dropIfExists('checkout_data');
    }
};
