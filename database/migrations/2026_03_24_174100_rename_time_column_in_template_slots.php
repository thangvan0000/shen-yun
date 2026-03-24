<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('template_slots', function (Blueprint $table) {
            $table->renameColumn('time', 'start_time');
        });
    }

    public function down(): void
    {
        Schema::table('template_slots', function (Blueprint $table) {
            $table->renameColumn('start_time', 'time');
        });
    }
};
