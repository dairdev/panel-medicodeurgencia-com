<?php

namespace App\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;

class Situacion extends Model
{
    public $table = "situaciones";
    public $primaryKey = 'codigosituacionregistro';
    public $incrementing = false;
    public $timestamps = false;

    public function presp_sitreg()
    {
        return $this->hasMany('App\Http\Models\Prescriptions\Prescription', 'cod_sitreg', 'codigosituacionregistro');
    }

    public function presp_sitreg_presen()
    {
        return $this->hasMany('App\Http\Models\Prescriptions\Prescription', 'cod_sitreg_presen', 'codigosituacionregistro');
    }
}
