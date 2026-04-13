<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void //Abre la tabla user para editarla y añade los campos de perfil a la tabla de usuarios
    {
        Schema::table('users', function (Blueprint $table) { 
            $table->string('avatar')->nullable()->after('role'); //Añade el campo avatar a la tabla de usuarios
            $table->string('country')->nullable()->after('avatar'); //Añade el campo country a la tabla de usuarios
            $table->date('birth_date')->nullable()->after('country'); //Añade el campo birth_date a la tabla de usuarios
        });
    }

    public function down(): void 
    { 
        Schema::table('users', function (Blueprint $table) { //Es el deshacer la migracion y volver a como estaba antes
            $table->dropColumn(['avatar', 'country', 'birth_date']); 
        });
    }
};
