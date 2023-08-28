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
        Schema::table('blog_data', function (Blueprint $table) {
            $table->unsignedBigInteger('blog_type_id');
            $table->foreign('blog_type_id')->references('id')->on('blog_type')->
            onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_data', function (Blueprint $table) {
            $table->dropForeign('blog_type_id');
            $table->dropColumn('blog_type_id');
        });
    }
};
