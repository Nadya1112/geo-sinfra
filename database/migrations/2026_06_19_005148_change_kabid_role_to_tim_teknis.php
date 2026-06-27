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
        $driver = DB::connection()->getDriverName();
        // First change column to string or enum with new value
        if ($driver !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'surveyor', 'kabid', 'tim_teknis') DEFAULT 'surveyor'");
        }
        DB::table('users')->where('role', 'kabid')->update(['role' => 'tim_teknis']);
        // Then restrict back if needed, or leave it
        if ($driver !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'surveyor', 'tim_teknis') DEFAULT 'surveyor'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();
        if ($driver !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'surveyor', 'kabid', 'tim_teknis') DEFAULT 'surveyor'");
        }
        DB::table('users')->where('role', 'tim_teknis')->update(['role' => 'kabid']);
        if ($driver !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'surveyor', 'kabid') DEFAULT 'surveyor'");
        }
    }
};
