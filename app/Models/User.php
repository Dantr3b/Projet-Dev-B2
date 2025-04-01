<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasFactory, Notifiable;

    protected $table = 'users'; // Nom exact de la table
    protected $primaryKey = 'user_id'; // Définition de la clé primaire
    public $incrementing = true; // Permet l'auto-incrémentation
    protected $keyType = 'int'; // Indique que la clé est un entier
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['username', 'email', 'password'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
