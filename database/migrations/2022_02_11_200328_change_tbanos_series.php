<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeTbanosSeries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbanos_series', function (Blueprint $table) {
            $table->unsignedInteger('bncc_serie')->nullable()->unique()->after('tbmodalidades_ensino_id');
        });
        DB::statement("UPDATE tbanos_series SET bncc_serie = 1 where tbanos_series_descricao = '1Âº Ano' and where tbanos_series_ativo = 'S'");
        DB::statement("UPDATE tbanos_series SET bncc_serie = 2 where tbanos_series_descricao = '2Âº Ano' and where tbanos_series_ativo = 'S'");
        DB::statement("UPDATE tbanos_series SET bncc_serie = 3 where tbanos_series_descricao = '3Âº Ano' and where tbanos_series_ativo = 'S'");
        DB::statement("UPDATE tbanos_series SET bncc_serie = 4 where tbanos_series_descricao = '4Âº Ano' and where tbanos_series_ativo = 'S'");
        DB::statement("UPDATE tbanos_series SET bncc_serie = 5 where tbanos_series_descricao = '5Âº Ano' and where tbanos_series_ativo = 'S'");
        DB::statement("UPDATE tbanos_series SET bncc_serie = 6 where tbanos_series_descricao = '6Âº Ano' and where tbanos_series_ativo = 'S'");
        DB::statement("UPDATE tbanos_series SET bncc_serie = 7 where tbanos_series_descricao = '7Âº Ano' and where tbanos_series_ativo = 'S'");
        DB::statement("UPDATE tbanos_series SET bncc_serie = 8 where tbanos_series_descricao = '8Âº Ano' and where tbanos_series_ativo = 'S'");
        DB::statement("UPDATE tbanos_series SET bncc_serie = 9 where tbanos_series_descricao = '9Âº Ano' and where tbanos_series_ativo = 'S'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbanos_series', function (Blueprint $table) {
            $table->dropColumn('bncc_serie');
        });
    }
}
