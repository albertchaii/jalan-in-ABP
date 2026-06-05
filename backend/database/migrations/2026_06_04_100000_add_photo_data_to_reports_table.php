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
        Schema::table('reports', function (Blueprint $table) {
            // Foto disimpan langsung di database sebagai data URI Base64 (portable)
            $table->longText('photo_data')->nullable()->after('photo_path');
            $table->longText('ai_photo_data')->nullable()->after('ai_photo_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['photo_data', 'ai_photo_data']);
        });
    }
};
