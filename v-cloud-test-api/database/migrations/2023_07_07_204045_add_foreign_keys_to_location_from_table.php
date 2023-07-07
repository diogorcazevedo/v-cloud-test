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
        Schema::table('location_from', function (Blueprint $table) {
            $table->foreign(['id'], 'location_from_deal_location_7')->references(['id'])->on('deals')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['user_id'], 'location_from_user_location_9')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('location_from', function (Blueprint $table) {
            $table->dropForeign('location_from_deal_location_7');
            $table->dropForeign('location_from_user_location_9');
        });
    }
};
