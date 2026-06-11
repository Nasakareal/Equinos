<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOficioLetterheadToWhatsappAiProfilesTable extends Migration
{
    public function up()
    {
        Schema::table('whatsapp_ai_profiles', function (Blueprint $table) {
            $table->longText('oficio_letterhead_text')->nullable();
            $table->timestamp('oficio_letterhead_updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('whatsapp_ai_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'oficio_letterhead_text',
                'oficio_letterhead_updated_at',
            ]);
        });
    }
}
