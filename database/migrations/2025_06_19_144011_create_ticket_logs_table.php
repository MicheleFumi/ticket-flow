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
        Schema::create('ticket_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('assegnato_a')->constrained('technicians')->nullable()->onDelete('set null');
            $table->foreignId("riaperto_da_user")->constrained("users")->nullable()->onDelete('set null');
            $table->foreignId("riaperto_da_admin")->constrained("technicians")->nullable()->onDelete('set null');
            $table->foreignId("chiuso_da")->constrained('technicians')->nullable()->onDelete('set null');
            $table->text("note_riapertura");
            $table->text("note_chiusura")->nullable();
            $table->timestamp('data_assegnazione')->nullable();
            $table->timestamp('data_riapertura')->nullable();
            $table->timestamp('data_chiusura')->nullable();
            $table->$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_logs');
    }
};
