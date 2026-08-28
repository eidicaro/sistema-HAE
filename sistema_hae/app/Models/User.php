<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    public const ROLE_PROFESSOR = 'professor';

    public const ROLE_COORDENADOR = 'coordenador';

    public const ROLE_DIRECAO = 'direcao';

    public const ROLES = [
        self::ROLE_PROFESSOR,
        self::ROLE_COORDENADOR,
        self::ROLE_DIRECAO,
    ];

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'curso',
    ];

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

    // Relaiconamentos

    public function haes()
    {
        return $this->hasMany(Haes::class, 'user_id');
    }

    public function pareceres()
    {
        return $this->hasMany(Parecer::class, 'user_id');
    }

    public function decisoes()
    {
        return $this->hasMany(Decisao::class, 'avaliador_id');
    }

    public function haesComoRelator()
    {
        return $this->belongsToMany(Haes::class, 'relatores', 'user_id', 'hae_id');
    }
}
