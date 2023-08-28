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
        Schema::create('blog_data', function (Blueprint $table) {
            $table->id();
            $table->string('blog_data_name');
            $table->text('blog_data');
            $table->integer('image_id')->nullable();
            $table->integer('video_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_data');
    }
};
