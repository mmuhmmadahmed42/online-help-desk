<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            // Unique reference number shown to the user, e.g. HD-2026-0001
            $table->string('reference_number')->unique();

            // Ticket creator
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->text('description');

            // open        -> just created, not yet assigned by PM
            // in_progress -> PM has assigned it to a team
            // completed   -> team marked it as completed
            // closed      -> fully closed out
            $table->enum('status', ['open', 'in_progress', 'completed', 'closed'])
                  ->default('open');

            // Which team it was routed to by the Project Manager
            $table->enum('assigned_team', ['backend', 'frontend'])->nullable();

            // Project Manager who made the assignment
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamp('assigned_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};