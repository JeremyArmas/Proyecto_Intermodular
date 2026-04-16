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
        Schema::create('administrators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_super_admin')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        // Migrar administradores existentes de la tabla 'users'
        $admins = DB::table('users')->where('role', 'admin')->get();

        foreach ($admins as $admin) {
            DB::table('administrators')->insert([
                'name' => $admin->name,
                'email' => $admin->email,
                'password' => $admin->password,
                'is_super_admin' => false,
                'created_at' => $admin->created_at,
                'updated_at' => $admin->updated_at,
            ]);
        }

        DB::table('users')->where('role', 'admin')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrators');
    }
};
