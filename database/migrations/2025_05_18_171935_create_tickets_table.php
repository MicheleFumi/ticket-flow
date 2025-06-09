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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string("titolo")->nullable();
            $table->text("commento")->nullable();
            $table->foreignId('status_id')->default(1);
            $table->foreignId('technician_id')->nullable()->constrained('technicians')->onDelete('set null');
            $table->foreignId("chiuso_da")->nullable()->constrained('technicians')->onDelete('set null');
            $table->timestamp('data_assegnazione')->nullable();
            $table->timestamp('data_chiusura')->nullable();
            $table->text("note_chiusura")->nullable();
            $table->boolean("is_reported")->default(0);
            $table->text("commento_report")->nullable();
            $table->foreignId('reportato_da')->nullable()->constrained('technicians')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
