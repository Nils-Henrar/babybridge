<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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

    public function children()
    {
        return $this->belongsToMany(Child::class, 'child_tutor');
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
        $user = $this->roles()->first()->role;

        if ($user == 'worker') {
            return route('worker.profile');
        } elseif ($user == 'tutor') {
            return route('tutor.profile');
        } else {
            return route('home');
        }
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }


    public function assignRole($role) // Ajoute un rôle à un utilisateur
    {
        $roleId = Role::where('role', $role)->first()->id;

        $this->roles()->attach($roleId);
    }
}
