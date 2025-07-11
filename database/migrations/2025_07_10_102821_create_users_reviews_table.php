<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');  
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');    
            $table->unsignedTinyInteger('rating');  
            $table->string('comment');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users_reviews');
    }
};

