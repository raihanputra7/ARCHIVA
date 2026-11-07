<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Kolom tambahan pada tabel surat_masuk
        Schema::table('surat_masuk', function (Blueprint $table) {
            if (! Schema::hasColumn('surat_masuk', 'asal')) {
                $table->string('asal', 20)->default('eksternal')->after('tanggal_diterima');
            }
            if (! Schema::hasColumn('surat_masuk', 'nomor_surat')) {
                $table->string('nomor_surat', 100)->nullable()->after('nomor_agenda');
            }
            if (! Schema::hasColumn('surat_masuk', 'ringkasan')) {
                $table->text('ringkasan')->nullable()->after('perihal');
            }
            if (! Schema::hasColumn('surat_masuk', 'dicatat_oleh')) {
                $table->foreignId('dicatat_oleh')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            }

            // Index pencarian
            $table->index('nomor_surat');
        });

        // Sinkronisasi data awal surat_masuk jika ada baris sebelumnya
        DB::table('surat_masuk')->whereNull('nomor_surat')->update([
            'nomor_surat' => DB::raw('nomor_surat_asli'),
            'ringkasan' => DB::raw('catatan'),
            'dicatat_oleh' => DB::raw('created_by'),
        ]);

        // 2. Tabel Pivot surat_penerima (Multi-recipient & Tracking Baca)
        if (! Schema::hasTable('surat_penerima')) {
            Schema::create('surat_penerima', function (Blueprint $table) {
                $table->id();
                $table->foreignId('surat_masuk_id')->constrained('surat_masuk')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->timestamp('dibaca_pada')->nullable();
                $table->timestamps();

                $table->unique(['surat_masuk_id', 'user_id']);
                $table->index(['user_id', 'dibaca_pada']);
            });
        }

        // 3. Kolom tambahan pada tabel disposisi untuk fleksibilitas nama kolom
        Schema::table('disposisi', function (Blueprint $table) {
            if (! Schema::hasColumn('disposisi', 'dari_user_id')) {
                $table->foreignId('dari_user_id')->nullable()->after('surat_masuk_id')->constrained('users')->cascadeOnDelete();
            }
            if (! Schema::hasColumn('disposisi', 'ke_user_id')) {
                $table->foreignId('ke_user_id')->nullable()->after('dari_user_id')->constrained('users')->cascadeOnDelete();
            }
            if (! Schema::hasColumn('disposisi', 'status_tindak_lanjut')) {
                $table->string('status_tindak_lanjut', 50)->default('menunggu')->after('batas_waktu');
            }
        });

        // Sinkronisasi kolom disposisi jika ada data awal
        DB::table('disposisi')->whereNull('dari_user_id')->update([
            'dari_user_id' => DB::raw('from_user_id'),
            'ke_user_id' => DB::raw('to_user_id'),
            'status_tindak_lanjut' => DB::raw('LOWER(status)'),
        ]);

        // 4. Tabel riwayat_status (Audit trail per transisi status)
        if (! Schema::hasTable('riwayat_status')) {
            Schema::create('riwayat_status', function (Blueprint $table) {
                $table->id();
                $table->foreignId('surat_masuk_id')->constrained('surat_masuk')->cascadeOnDelete();
                $table->string('dari_status', 50)->nullable();
                $table->string('ke_status', 50);
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->text('catatan')->nullable();
                $table->timestamp('created_at')->useCurrent();

                $table->index(['surat_masuk_id', 'created_at']);
                $table->index(['user_id', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_status');

        Schema::table('disposisi', function (Blueprint $table) {
            if (Schema::hasColumn('disposisi', 'status_tindak_lanjut')) {
                $table->dropColumn('status_tindak_lanjut');
            }
            if (Schema::hasColumn('disposisi', 'ke_user_id')) {
                $table->dropForeign(['ke_user_id']);
                $table->dropColumn('ke_user_id');
            }
            if (Schema::hasColumn('disposisi', 'dari_user_id')) {
                $table->dropForeign(['dari_user_id']);
                $table->dropColumn('dari_user_id');
            }
        });

        Schema::dropIfExists('surat_penerima');

        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->dropIndex(['nomor_surat']);
            if (Schema::hasColumn('surat_masuk', 'dicatat_oleh')) {
                $table->dropForeign(['dicatat_oleh']);
                $table->dropColumn('dicatat_oleh');
            }
            if (Schema::hasColumn('surat_masuk', 'ringkasan')) {
                $table->dropColumn('ringkasan');
            }
            if (Schema::hasColumn('surat_masuk', 'nomor_surat')) {
                $table->dropColumn('nomor_surat');
            }
            if (Schema::hasColumn('surat_masuk', 'asal')) {
                $table->dropColumn('asal');
            }
        });
    }
};
