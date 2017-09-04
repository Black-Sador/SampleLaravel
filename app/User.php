<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    // TODO C'est le modèle de user : la représentation directe de ce qu'il y a entre la base de données et les controllers. On peut directement ajouter de la data grâce aux models

    protected $fillable = [
        'id',
        'gender',
        'name',
        'first_name',
        'email',
        'password',
    ];

    public static function getByMail($email)
    {
        return self::where('email', $email)->first();
    }

}