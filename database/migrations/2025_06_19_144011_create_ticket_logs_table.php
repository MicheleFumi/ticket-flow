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

            $table->unsignedBigInteger('assegnato_a')->nullable();
            $table->foreign('assegnato_a')->references('id')->on('technicians')->onDelete('set null');

            $table->unsignedBigInteger('riaperto_da_user')->nullable();
            $table->foreign('riaperto_da_user')->references('id')->on('users')->onDelete('set null');

            $table->unsignedBigInteger('riaperto_da_admin')->nullable();
            $table->foreign('riaperto_da_admin')->references('id')->on('technicians')->onDelete('set null');

            $table->unsignedBigInteger('chiuso_da')->nullable();
            $table->foreign('chiuso_da')->references('id')->on('technicians')->onDelete('set null');

            $table->text("note_riapertura")->nullable();
            $table->text("note_chiusura")->nullable();
            $table->timestamp('data_assegnazione')->nullable();
            $table->timestamp('data_riapertura')->nullable();
            $table->timestamp('data_chiusura')->nullable();
            $table->timestamps();
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
