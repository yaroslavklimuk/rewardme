<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_rewards', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable(true)->default(null);
            $table->float('amount', 8, 2);
            $table->boolean('payed')->default(false);
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
        Schema::dropIfExists('cash_rewards');
    }
}
