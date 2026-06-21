<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetaMessageIdToWhatsappAiMessagesTable extends Migration
{
    public function up()
    {
        Schema::table('whatsapp_ai_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('whatsapp_ai_messages', 'meta_message_id')) {
                $table->string('meta_message_id', 191)->nullable()->unique()->after('phone');
            }
        });
    }

    public function down()
    {
        Schema::table('whatsapp_ai_messages', function (Blueprint $table) {
            if (Schema::hasColumn('whatsapp_ai_messages', 'meta_message_id')) {
                $table->dropUnique(['meta_message_id']);
                $table->dropColumn('meta_message_id');
            }
        });
    }
}
