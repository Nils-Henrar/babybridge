<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login',
        'firstname',
        'lastname',
        'email',
        'langue',
        'password',
        'phone',
        'address',
        'postal_code',
        'city',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    public $timestamps = true;

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function worker()
    {
        return $this->hasOne(Worker::class);
    }

    public function tutor()
    {
        return $this->hasOne(ChildTutor::class);
    }

    public function adminlte_image()
    {
        return 'https://picsum.photos/300/300';
    }

    public function adminlte_desc()
    {
        return $this->login;
    }

    public function adminlte_profile_url()
    {
        return 'profile/username';
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }


    public function assignRole($role)
    {
        $roleId = Role::where('role', $role)->first()->id;

        $this->roles()->attach($roleId);
    }

    public function sendIdentifiersByEmail($firstname, $lastname)
    {
        // Générez le login
        do {
            $login = substr($firstname, 0, 2) . substr($lastname, 0, 2) . rand(10, 999);
        } while (User::where('login', $login)->exists());

        // Générez le mot de passe
        $password = substr($firstname, 0, 2) . substr($lastname, 0, 2) . rand(10, 999);

        // Envoyez les identifiants par e-mail
        Mail::raw("Votre login est : $login \nVotre mot de passe est : $password", function ($message) {
            $message->to($this->email)->subject('Vos identifiants');
        });

        // Retournez les identifiants pour référence
        return [
            'login' => $login,
            'password' => $password
        ];
    }
}
