<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anfis_results', function (Blueprint $table) {
            $table->id();
            $table->integer('no')->nullable();
            $table->float('y_aktual', 8, 4)->nullable();
            $table->float('y_pred', 8, 4)->nullable();
            $table->float('error_abs', 8, 4)->nullable();
            $table->float('error_rel', 8, 2)->nullable();
            $table->float('rmse', 8, 4)->nullable();
            $table->float('mad', 8, 4)->nullable();
            $table->float('aare', 8, 2)->nullable();
            $table->float('akurasi', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anfis_results');
    }
};
