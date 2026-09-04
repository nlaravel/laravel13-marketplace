<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table): void {
            $table->foreignId('seller_order_id')
                ->after('order_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->index('seller_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table): void {
            $table->dropForeign(['seller_order_id']);
            $table->dropIndex(['seller_order_id']);
            $table->dropColumn('seller_order_id');
        });
    }
};
