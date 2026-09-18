<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('warehouse_id')->nullable()->after('user_id')
                ->constrained()->nullOnDelete();
            $table->enum('status', ['selesai', 'dibatalkan'])->default('selesai')->after('change');
            $table->foreignId('canceled_by')->nullable()->after('status')
                ->constrained('users')->nullOnDelete();
            $table->timestamp('canceled_at')->nullable()->after('canceled_by');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('warehouse_id');
            $table->dropConstrainedForeignId('canceled_by');
            $table->dropColumn(['status', 'canceled_at']);
        });
    }
};