<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     * TODO Appels possible via php artisan migrate (migrate:rollback pour executer le "down", et migrate:refresh pour faire les deux à la suite, en commancant pas le rollback)
     */
    public function up()
    {
        // Création de la table "users" ayant pour colonnes : id, gender, name, first_name, email et un mot de passe + des timestamps automatiquement gérés par Laravel
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('gender');
            $table->string('name');
            $table->string('first_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
