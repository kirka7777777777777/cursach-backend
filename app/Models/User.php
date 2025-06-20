<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; // Если планируется верификация почты
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // <-- ДОБАВИТЬ ЭТУ СТРОКУ

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // <-- ДОБАВИТЬ HasApiTokens и HasFactory

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ]; // <-- ДОБАВИТЬ ЭТОТ БЛОК!

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ]; // <-- ДОБАВИТЬ ЭТОТ БЛОК!

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 10+ автоматически хеширует пароль, если это есть
    ]; // <-- ДОБАВИТЬ ЭТОТ БЛОК!

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($roles)
    {
        if (is_array($roles)) {
            return $this->roles()->whereIn('name', $roles)->exists();
        }

        return $this->roles()->where('name', $roles)->exists();
    }

    public function cardsAssigned()
    {
        return $this->hasMany(Card::class, 'assigned_to');
    }

    public function cardsCreated()
    {
        return $this->hasMany(Card::class, 'created_by');
    }
}
