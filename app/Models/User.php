<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends \TCG\Voyager\Models\User
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'funcionario_id',
        'email',
        'password',
        'role_id',
        'sucursal_id',
        'subSucursal_id',
        'direccionAdministrativa_id',
        'unidadAdministrativa_id',
        'contract_id'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unidadAdministrativa_id');
    }

    public function direction()
    {
        return $this->belongsTo(Direction::class, 'direccionAdministrativa_id');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin(){
        return $this->hasRole(['admin']);
    }
}
