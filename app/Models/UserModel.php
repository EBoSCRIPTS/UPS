<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'password',
        'profile_picture',
        'role_id',
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

    public function isAdmin()
    {
        return $this->role_id == 1;
    }

    public function isManager()
    {
        return $this->role_id == 3;
    }

    public function isWriter()
    {
        return $this->is_writer == 1;
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(EmployeeInformationModel::class, 'id', 'user_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(RolesModel::class, 'role_id', 'id');
    }
}
