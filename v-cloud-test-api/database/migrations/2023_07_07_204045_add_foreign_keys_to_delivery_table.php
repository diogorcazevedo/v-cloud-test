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
        Schema::table('delivery', function (Blueprint $table) {
            $table->foreign(['deal_id'], 'delivery_deal_5')->references(['id'])->on('deals')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['user_id'], 'delivery_user_4')->references(['id'])->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery', function (Blueprint $table) {
            $table->dropForeign('delivery_deal_5');
            $table->dropForeign('delivery_user_4');
        });
    }
};
