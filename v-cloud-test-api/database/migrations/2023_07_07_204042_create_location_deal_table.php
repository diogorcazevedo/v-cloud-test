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
        Schema::create('location_deal', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('deal_id')->index('location_deal_location_7');
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->integer('zip_code')->nullable();
            $table->string('state', 20)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('address', 191)->nullable();
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
        Schema::dropIfExists('location_deal');
    }
};
