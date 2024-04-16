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
        Schema::table('users', function (Blueprint $table) {

            // Ajouter les nouvelles colonnes avant de modifier les colonnes existantes
            $table->string('login', 30)->after('id');
            $table->string('langue', 2)->after('email');

            // Définir la contrainte d'unicité sur la colonne login
            $table->unique('login', 'users_login_unique');
        });

        Schema::table('users', function (Blueprint $table) {
            //retirer la colonne email,name
            $table->dropColumn('email');
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_login_unique');
            $table->dropColumn('langue');
            $table->dropColumn('login');
            $table->string('email')->after('id');
            $table->string('name')->after('email');
        });
    }
};
