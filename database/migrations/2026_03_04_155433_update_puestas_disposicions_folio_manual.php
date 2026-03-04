<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('puestas_disposicions', function (Blueprint $table) {
            $table->dropColumn('folio_num');
            $table->string('folio', 60)->change();
        });
    }

    public function down(): void
    {
        Schema::table('puestas_disposicions', function (Blueprint $table) {
            $table->unsignedInteger('folio_num')->after('hecho_id');
            $table->string('folio', 20)->change();
        });
    }
};
