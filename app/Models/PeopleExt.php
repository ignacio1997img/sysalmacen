<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeopleExt extends Model
{
    use HasFactory;

    protected $fillable = [
        'people_id',
        'direccionAdministrativa_id',
        'cargo',
        'start',
        'finish',
        'observation',
        'status',
        'registerUser_id',
        'deleted_at'
    ];


    public function people()
    {
        return $this->belongsTo(Person::class, 'people_id');
    }
    public function direction()
    {
        return $this->belongsTo(Direction::class, 'direccionAdministrativa_id');
    }
}
