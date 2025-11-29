<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            if (!Schema::hasColumn('menu_items', 'image')) {
                $table->string('image')->nullable()->after('image_path');
            }
            if (!Schema::hasColumn('menu_items', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_available');
            }
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            if (Schema::hasColumn('menu_items', 'image')) {
                $table->dropColumn('image');
            }
            if (Schema::hasColumn('menu_items', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};

