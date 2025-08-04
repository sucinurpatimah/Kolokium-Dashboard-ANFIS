<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('anfis_results', function (Blueprint $table) {
            $table->string('indikator_kinerja')->after('no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('anfis_results', function (Blueprint $table) {
            $table->dropColumn('indikator_kinerja');
        });
    }
};
