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
        Schema::create('urgency', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('deal_id')->index('urgency_deal_16');
            $table->enum('type', ['Baixa', 'Média', 'Alta', 'Data'])->nullable();
            $table->date('limit_date')->nullable();
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
        Schema::dropIfExists('urgency');
    }
};
