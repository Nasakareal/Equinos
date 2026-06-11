<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappAiMemoriesTable extends Migration
{
    public function up()
    {
        Schema::create('whatsapp_ai_memories', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 32)->index();
            $table->text('fact');
            $table->string('source')->nullable();
            $table->boolean('trusted')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->index(['phone', 'trusted']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp_ai_memories');
    }
}
