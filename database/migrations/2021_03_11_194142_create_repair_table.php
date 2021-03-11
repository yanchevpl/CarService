<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepairTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repair', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('serviceorder_id')->index('serviceorder_id');
            $table->string('operation', 500)->default('none');
            $table->string('part_name', 500)->default('none');
            $table->float('price', 10, 0)->default(0);
            $table->float('labor', 10, 0)->default(0);
            $table->string('comment', 500)->nullable();
            $table->primary(['id', 'serviceorder_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repair');
    }
}
