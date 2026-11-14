<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->string('name', 100);
            $t->decimal('price', 10, 2);
            $t->unsignedInteger('stock_quantity')->default(0);
            $t->string('image')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('customers', function (Blueprint $t) {
            $t->id();
            $t->string('full_name', 100);
            $t->string('email', 100);
            $t->string('phone', 20)->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('sales', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->restrictOnDelete();
            $t->foreignId('customer_id')->nullable()->constrained()->restrictOnDelete();
            $t->foreignId('sold_by')->constrained('users')->restrictOnDelete();
            $t->unsignedInteger('quantity');
            $t->decimal('total_price', 10, 2);
            $t->uuid('submission_token')->unique();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('products');
    }
};
