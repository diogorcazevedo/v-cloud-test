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
        Schema::create('steps', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('deal_id')->index('step_deal_16');
            $table->unsignedInteger('delivery_id')->index('step_delivery_16');
            $table->unsignedInteger('user_id')->index('step_user_17');
            $table->dateTime('outcoming_date')->nullable();
            $table->dateTime('incoming_date')->nullable();
            $table->string('location', 191)->nullable();
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
        Schema::dropIfExists('steps');
    }
};
