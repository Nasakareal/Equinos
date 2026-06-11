<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappAiProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('whatsapp_ai_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 32)->unique();
            $table->string('assistant_name')->nullable();
            $table->string('welcome_template_name')->nullable();
            $table->string('welcome_template_language', 16)->nullable();
            $table->timestamp('welcome_template_sent_at')->nullable();
            $table->string('welcome_template_message_id')->nullable();
            $table->json('welcome_template_payload')->nullable();
            $table->timestamp('last_welcome_attempt_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp_ai_profiles');
    }
}
