<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personals', function (Blueprint $table) {
            $table->string('actividad')->nullable()->after('dependencia');
        });

        DB::table('personals')
            ->whereIn('cargo', ['CUARTELERO', 'ARRENDADOR'])
            ->update([
                'actividad' => DB::raw('cargo')
            ]);
    }

    public function down(): void
    {
        Schema::table('personals', function (Blueprint $table) {
            $table->dropColumn('actividad');
        });
    }
};
