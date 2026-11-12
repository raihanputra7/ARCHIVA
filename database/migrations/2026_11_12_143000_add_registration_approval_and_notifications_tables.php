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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_wa', 20)->nullable()->after('email');
            $table->string('requested_role', 32)->nullable()->after('role');
            $table->string('status', 20)->default('pending')->index()->after('requested_role');
            $table->text('rejected_reason')->nullable()->after('status');
            $table->foreignId('approved_by')->nullable()->after('rejected_reason')->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->boolean('notify_email')->default(true)->after('approved_at');
        });

        // Pastikan seluruh akun yang sudah ada tetap aktif dan terverifikasi emailnya
        DB::table('users')->update([
            'status' => 'active',
            'notify_email' => true,
        ]);

        DB::table('users')
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => now()]);

        Schema::create('email_verification_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('token_hash', 64)->unique();
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('surat_id')->nullable()->constrained('surat_masuk')->nullOnDelete();
            $table->foreignId('disposisi_id')->nullable()->constrained('disposisi')->nullOnDelete();
            $table->string('channel', 32)->index();
            $table->string('recipient', 191)->nullable();
            $table->string('status', 20)->index();
            $table->text('error')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('admin_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('target_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 64)->index();
            $table->json('details')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_audit_logs');
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('email_verification_tokens');

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('approved_by');
            $table->dropColumn([
                'phone_wa',
                'requested_role',
                'status',
                'rejected_reason',
                'approved_at',
                'notify_email',
            ]);
        });
    }
};
