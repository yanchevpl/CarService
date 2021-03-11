<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToServiceorderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('serviceorder', function (Blueprint $table) {
            $table->foreign('car_id', 'serviceorder_ibfk_1')->references('id')->on('cars')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('status', 'serviceorder_ibfk_2')->references('id')->on('servicestatus')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('serviceorder', function (Blueprint $table) {
            $table->dropForeign('serviceorder_ibfk_1');
            $table->dropForeign('serviceorder_ibfk_2');
        });
    }
}
