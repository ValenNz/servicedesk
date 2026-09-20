<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'file_name')) {
                $table->string('file_name')->nullable()->after('file_path');
            }
            
            if (!Schema::hasColumn('tickets', 'file_size')) {
                $table->string('file_size')->nullable()->after('file_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['file_name', 'file_size']);
        });
    }
};