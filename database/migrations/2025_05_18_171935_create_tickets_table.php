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
            $table->boolean("is_reported")->default(0);
            $table->text("commento_report")->nullable();
            $table->foreignId('reportato_da')->nullable()->constrained('technicians')->onDelete('set null');
            $table->timestamp('report_date')->nullable();
            $table->boolean("is_deleted")->default(0);
            $table->boolean("is_reopened")->default(0);
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
