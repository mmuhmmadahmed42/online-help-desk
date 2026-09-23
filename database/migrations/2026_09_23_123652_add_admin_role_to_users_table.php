<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'project_manager', 'backend_team', 'frontend_team', 'admin') DEFAULT 'user'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'project_manager', 'backend_team', 'frontend_team') DEFAULT 'user'");
    }
};