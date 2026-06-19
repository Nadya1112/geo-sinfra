<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First change column to string or enum with new value
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'surveyor', 'kabid', 'tim_teknis') DEFAULT 'surveyor'");
        DB::table('users')->where('role', 'kabid')->update(['role' => 'tim_teknis']);
        // Then restrict back if needed, or leave it
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'surveyor', 'tim_teknis') DEFAULT 'surveyor'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'surveyor', 'kabid', 'tim_teknis') DEFAULT 'surveyor'");
        DB::table('users')->where('role', 'tim_teknis')->update(['role' => 'kabid']);
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'surveyor', 'kabid') DEFAULT 'surveyor'");
    }
};
