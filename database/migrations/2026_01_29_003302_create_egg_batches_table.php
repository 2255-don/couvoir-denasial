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
        Schema::create('egg_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->dateTime('loading_date');
            $table->dateTime('transfer_date');
            $table->dateTime('hatching_date');
            $table->foreignId('current_unit_id')->nullable()->constrained('units')->onDelete('set null');
            $table->enum('status', ['incubating', 'hatching', 'done'])->default('incubating');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egg_batches');
    }
};
