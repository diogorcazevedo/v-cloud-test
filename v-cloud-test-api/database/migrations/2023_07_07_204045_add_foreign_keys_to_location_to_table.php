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
        Schema::table('location_to', function (Blueprint $table) {
            $table->foreign(['deal_id'], 'location_from_deal_location_10')->references(['id'])->on('deals')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['id'], 'location_from_user_location_11')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('location_to', function (Blueprint $table) {
            $table->dropForeign('location_from_deal_location_10');
            $table->dropForeign('location_from_user_location_11');
        });
    }
};
