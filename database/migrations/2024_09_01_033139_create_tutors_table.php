<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tutors', function (Blueprint $table) {
            $table->id();
            $table->string('avatar')->nullable();
            $table->string('name', 255)->index('idx_name');
            $table->string('email')->unique();
            $table->decimal('hourly_rate', 8, 2)->index('idx_hourly_rate');
            $table->text('bio')->nullable();
            $table->json('subjects');
            $table->timestamps();

            // Adding multi-valued index for JSON array subjects
            if (! config('app.env') == 'testing') {
                $table->index([DB::raw("((CAST(subjects->'$[*]' AS CHAR(255) ARRAY)))")], 'idx_subjects');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutors');
    }
};
