<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('stock_ins', 'status')) {
            Schema::table('stock_ins', function (Blueprint $table) {
                $table->enum('status', ['selesai', 'dibatalkan'])->default('selesai')->after('total_cost');
            });
        }

        if (! Schema::hasColumn('stock_ins', 'canceled_by')) {
            Schema::table('stock_ins', function (Blueprint $table) {
                $table->foreignId('canceled_by')->nullable()->after('status')
                    ->constrained('users')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('stock_ins', 'canceled_at')) {
            Schema::table('stock_ins', function (Blueprint $table) {
                $table->timestamp('canceled_at')->nullable()->after('canceled_by');
            });
        }

        // warehouse_id TETAP nullable di database (karena terikat FK nullOnDelete),
        // tapi tetap kita wajibkan lewat validasi di StockInController.
    }

    public function down(): void
    {
        Schema::table('stock_ins', function (Blueprint $table) {
            if (Schema::hasColumn('stock_ins', 'canceled_by')) {
                $table->dropConstrainedForeignId('canceled_by');
            }
            $table->dropColumn(['status', 'canceled_at']);
        });
    }
};