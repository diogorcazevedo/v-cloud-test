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
        Schema::table('messages', function (Blueprint $table) {
            $table->foreign(['deal_id'], 'message_deal_7')->references(['id'])->on('deals')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['user_id'], 'message_user_14')->references(['id'])->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign('message_deal_7');
            $table->dropForeign('message_user_14');
        });
    }
};
