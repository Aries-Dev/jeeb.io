<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJeebTransactionWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jeeb_transaction_wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jeeb_transaction_id')->nullable();
            $table->string("coin");
            $table->string("value");
            $table->string("address");
            $table->timestamps();

            $table->foreign('jeeb_transaction_id')
                ->references('id')
                ->on('jeeb_transactions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jeeb_transaction_wallets');
    }
}
