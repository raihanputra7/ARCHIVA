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
        Schema::table('surat_keluar', function (Blueprint $table) {
            if (! Schema::hasColumn('surat_keluar', 'parent_surat_masuk_id')) {
                $table->foreignId('parent_surat_masuk_id')
                    ->nullable()
                    ->after('nomor_agenda')
                    ->constrained('surat_masuk')
                    ->nullOnDelete();

                $table->index('parent_surat_masuk_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_keluar', function (Blueprint $table) {
            if (Schema::hasColumn('surat_keluar', 'parent_surat_masuk_id')) {
                $table->dropForeign(['parent_surat_masuk_id']);
                $table->dropIndex(['parent_surat_masuk_id']);
                $table->dropColumn('parent_surat_masuk_id');
            }
        });
    }
};
