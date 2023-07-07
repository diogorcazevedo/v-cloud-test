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
        Schema::table('urgency', function (Blueprint $table) {
            $table->foreign(['deal_id'], 'urgency_deal_16')->references(['id'])->on('deals')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('urgency', function (Blueprint $table) {
            $table->dropForeign('urgency_deal_16');
        });
    }
};
