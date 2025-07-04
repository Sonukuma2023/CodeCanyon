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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->unsignedBigInteger('category_id');
            $table->decimal('regular_license_price', 10, 2);
            $table->decimal('extended_license_price', 10, 2);
            $table->json('general_files')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('inline_preview')->nullable();
            $table->json('main_files')->nullable();
            $table->json('preview')->nullable();
            $table->json('live_preview')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
