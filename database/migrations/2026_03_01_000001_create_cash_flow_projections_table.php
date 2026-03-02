<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_flow_projections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('date');
            $table->decimal('expected_income', 12, 2)->default(0);
            $table->decimal('expected_expense', 12, 2)->default(0);
            $table->enum('status', ['planned', 'completed', 'cancelled'])->default('planned');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_flow_projections');
    }
};
