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
        Schema::table('location_deal', function (Blueprint $table) {
            $table->foreign(['deal_id'], 'location_deal_location_7')->references(['id'])->on('deals')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('location_deal', function (Blueprint $table) {
            $table->dropForeign('location_deal_location_7');
        });
    }
};
