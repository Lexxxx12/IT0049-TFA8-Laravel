<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_accounts', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('session_version')->default(1);
        });
        Schema::table('sales', function (Blueprint $table) {
            $table->string('product_name', 100)->nullable();
            $table->string('customer_name', 100)->nullable();
            $table->string('staff_name', 100)->nullable();
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropColumn(['product_name', 'customer_name', 'staff_name', 'unit_price']);
        });
        Schema::table('customer_accounts', fn (Blueprint $table) => $table->dropColumn(['is_active', 'session_version']));
    }
};
