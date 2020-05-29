<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJeebTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jeeb_transactions', function (Blueprint $table) {
            $table->id();
            $table->string("orderNo");
            $table->string("refrenceNo");
            $table->string("stateId");
            $table->string("coin")->nullable();
            $table->string("address")->nullable();
            $table->string("transactionId")->nullable();
            $table->string("baseValue");
            $table->string("value")->nullable();
            $table->string("paidValue")->nullable();
            $table->boolean('isConfirmed')->default(0);
            $table->string("finalizedTime")->nullable();
            $table->string("expirationTime");
            $table->string("token");
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
        Schema::dropIfExists('jeeb_transactions');
    }
}
