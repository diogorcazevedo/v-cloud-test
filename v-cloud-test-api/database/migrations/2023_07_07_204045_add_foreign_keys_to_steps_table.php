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
        Schema::table('steps', function (Blueprint $table) {
            $table->foreign(['deal_id'], 'step_deal_16')->references(['id'])->on('deals')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['delivery_id'], 'step_delivery_16')->references(['id'])->on('delivery')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['user_id'], 'step_user_17')->references(['id'])->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('steps', function (Blueprint $table) {
            $table->dropForeign('step_deal_16');
            $table->dropForeign('step_delivery_16');
            $table->dropForeign('step_user_17');
        });
    }
};
