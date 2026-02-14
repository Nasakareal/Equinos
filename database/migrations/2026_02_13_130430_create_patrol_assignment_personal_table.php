<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patrol_assignment_personal', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('patrol_assignment_id');
            $table->unsignedBigInteger('personal_id');
            $table->string('rol')->nullable();
            $table->timestamps();
            $table->foreign('patrol_assignment_id')->references('id')->on('patrol_assignments')->onDelete('cascade');
            $table->foreign('personal_id')->references('id')->on('personals')->onDelete('cascade');
            $table->unique(['patrol_assignment_id', 'personal_id'], 'uniq_assignment_personal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patrol_assignment_personal');
    }
};
