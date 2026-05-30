<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_reflections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type'); // quran, hadith, reflection
            $table->longText('body');
            $table->string('source')->nullable();
            $table->date('publish_date')->index();
            $table->string('status')->default('draft')->index(); // draft, published, archived
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_reflections');
    }
};
