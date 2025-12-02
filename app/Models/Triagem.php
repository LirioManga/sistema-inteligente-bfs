<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Triagem extends Model
{
    use HasFactory;
    protected $table = 'triagem';
    protected $fillable = [
        'paciente_id',
        'sintomas',
        'categoria',
        'gravidade',
        'departamento',
    ];

    public function paciente()
    {
        return $this->belongsTo(User::class, 'paciente_id');
    }
}
