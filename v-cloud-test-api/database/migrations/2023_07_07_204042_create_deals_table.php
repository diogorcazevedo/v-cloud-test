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
        Schema::create('deals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index('deal_user_3');
            $table->enum('type', ['Venda', 'Troca', 'Desejo'])->nullable();
            $table->double('value')->nullable();
            $table->string('description', 191)->nullable();
            $table->tinyInteger('accepted')->nullable()->default(0);
            $table->tinyInteger('active')->nullable()->default(1);
            $table->string('trade_for', 191)->nullable();
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
        Schema::dropIfExists('deals');
    }
};
