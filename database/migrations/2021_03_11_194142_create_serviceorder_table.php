<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceorderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serviceorder', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedBigInteger('car_id');
            $table->string('trouble', 500)->nullable();
            $table->integer('status')->nullable()->index('status');
            $table->string('schedule', 500)->nullable();
            $table->string('note', 500)->nullable();
            $table->index(['car_id', 'status', 'schedule'], 'car_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('serviceorder');
    }
}
