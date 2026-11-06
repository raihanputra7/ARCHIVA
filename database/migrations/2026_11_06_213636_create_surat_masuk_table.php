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
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_agenda', 50)->unique();
            $table->string('nomor_surat_asli', 100)->nullable();
            $table->string('pengirim', 255);
            $table->text('perihal');
            $table->date('tanggal_surat');
            $table->date('tanggal_diterima');
            $table->string('kategori', 100);
            $table->string('sifat', 50)->default('Biasa');
            $table->string('status', 50)->default('Diterima');
            $table->text('catatan')->nullable();

            // Berkas fisik di disk privat (storage/app/private/surat_masuk)
            $table->string('file_path', 500)->nullable();
            $table->string('file_name', 255)->nullable();
            $table->string('file_mime', 100)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['tanggal_diterima', 'status']);
            $table->index('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuk');
    }
};
