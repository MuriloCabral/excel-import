<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBnccSeries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('bncc_series');
        Schema::create('bncc_series', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bncc_id')->required();
            $table->foreign('bncc_id')
                ->references('id')
                ->on('bnccs')
                ->onDelete('cascade');
            $table->unsignedInteger('serie_id')->required();
            $table->foreign('serie_id')
                ->references('bncc_serie')
                ->on('tbanos_series')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bncc_series');
    }
}
