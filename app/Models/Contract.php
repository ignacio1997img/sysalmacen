<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory;

    protected $connection = 'mamore';
    protected $table = 'contracts';
    protected $fillable = ['person_id', 'cargo_id', 'job_id', 'direccion_administrativa_id', 'unidad_administrativa_id'];

    public function unidad()
    {
        return $this->belongsTo(Unit::class, 'unidad_administrativa_id');
    }
}
