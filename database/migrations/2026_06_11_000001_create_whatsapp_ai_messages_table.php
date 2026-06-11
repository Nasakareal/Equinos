<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappAiMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('whatsapp_ai_messages', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 32)->index();
            $table->string('direction', 16);
            $table->longText('body')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['phone', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp_ai_messages');
    }
}
