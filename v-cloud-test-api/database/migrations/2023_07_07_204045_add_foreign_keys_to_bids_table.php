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
        Schema::table('bids', function (Blueprint $table) {
            $table->foreign(['deal_id'], 'bid_deal_1')->references(['id'])->on('deals')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id'], 'bid_user_2')->references(['id'])->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bids', function (Blueprint $table) {
            $table->dropForeign('bid_deal_1');
            $table->dropForeign('bid_user_2');
        });
    }
};
