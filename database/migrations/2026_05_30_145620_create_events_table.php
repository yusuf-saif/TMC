<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->string('speaker_name')->nullable();
            $table->dateTime('event_date');
            $table->dateTime('end_date')->nullable();
            $table->string('location_type'); // online, in_person, hybrid
            $table->string('location_detail')->nullable();
            $table->string('cover_image')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->string('status')->default('draft')->index(); // draft, published, cancelled, completed
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
