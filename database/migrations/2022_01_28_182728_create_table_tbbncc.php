<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTbbncc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('bnccs');
        Schema::create('bnccs', function (Blueprint $table) {
            $table->id();

            $table->integer('tipo_bncc')->nullable();
            $table->integer('disciplina')->nullable();
            $table->string('componente', 55)->nullable();
            $table->string('ano_faixa', 59)->nullable();
            $table->string('campos_de_atuacao', 100)->nullable();
            $table->string('praticas_de_linguagem', 113)->nullable();
            $table->string('objetos_de_conhecimento', 800)->nullable();
            $table->string('habilidades', 1511)->nullable();

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
        Schema::dropIfExists('bnccs');
    }
}
