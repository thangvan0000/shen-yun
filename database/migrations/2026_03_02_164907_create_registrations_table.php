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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_session_id')->constrained('event_sessions')->cascadeOnDelete();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone', 32)->nullable();

            $table->unsignedInteger('adult_count')->default(0);
            $table->unsignedInteger('ntl_count')->default(0);
            $table->unsignedInteger('ntl_new_count')->default(0);
            $table->unsignedInteger('child_count')->default(0);
            $table->unsignedInteger('total_count');

            $table->boolean('attend_with_guest')->default(false);
            $table->string('status', 32)->default('confirmed');

            $table->timestamps();

            $table->index(['event_session_id', 'created_at']);
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
