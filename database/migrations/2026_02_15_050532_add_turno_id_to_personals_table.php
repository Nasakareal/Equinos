<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personals', function (Blueprint $table) {
            $table->unsignedBigInteger('turno_id')->nullable()->after('area_id');
            $table->foreign('turno_id')->references('id')->on('turnos');
            $table->index('turno_id');
        });
    }

    public function down(): void
    {
        Schema::table('personals', function (Blueprint $table) {
            $table->dropForeign(['turno_id']);
            $table->dropIndex(['turno_id']);
            $table->dropColumn('turno_id');
        });
    }
};
