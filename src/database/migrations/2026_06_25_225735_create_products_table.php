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
        Schema::create('products', function (Blueprint $table) {
            $table->string('url')->primary();
            $table->string('product_id')->nullable();
            $table->string('title');
            $table->string('manufacturer')->nullable();
            $table->string('oem_pn')->nullable();
            $table->string('condition')->nullable();
            $table->double('price_eur')->nullable();
            $table->double('weight_kg')->nullable();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
        });

        DB::statement('ALTER TABLE products ADD COLUMN embedding vector(1024)');
        DB::statement('CREATE INDEX products_emb_idx ON products USING hnsw (embedding vector_cosine_ops)');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
