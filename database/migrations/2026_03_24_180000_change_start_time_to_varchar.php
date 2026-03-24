<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE template_slots ALTER COLUMN start_time TYPE varchar(10)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE template_slots ALTER COLUMN start_time TYPE time');
    }
};
