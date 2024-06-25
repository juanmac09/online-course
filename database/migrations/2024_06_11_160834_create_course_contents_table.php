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
        Schema::create('course_contents', function (Blueprint $table) {
            $table->id();
            $table -> string('title');
            $table -> string('description');
            $table -> string('content');
            $table -> foreignId('course_id') -> constrained('courses');
            $table -> boolean('status') -> default(1);
            $table -> boolean('public') -> default(1);
            $table -> integer('order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_contents');
    }
};
