<?php


declare(strict_types=1);

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
        Schema::create('price_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('country_code', 2)->nullable();
            $table->string('currency_code', 3)->nullable();
            $table->decimal('price', 10, 2);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('priority')->default(100);
            $table->timestamps();

            $table->foreign('country_code')->references('code')->on('countries')->onDelete('set null');
            $table->foreign('currency_code')->references('code')->on('currencies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_lists');
    }
};
