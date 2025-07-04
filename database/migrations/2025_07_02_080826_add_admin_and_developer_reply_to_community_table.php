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
        Schema::table('community', function (Blueprint $table) {
            $table->text('admin_reply')->nullable()->after('comment');
            $table->text('developer_reply')->nullable()->after('admin_reply');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('community', function (Blueprint $table) {
            $table->dropColumn(['admin_reply', 'developer_reply']);
        });
    }
};
