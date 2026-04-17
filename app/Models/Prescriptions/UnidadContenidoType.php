<?php

namespace App\Http\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;

class UnidadContenidoType extends Model
{
    public $table = 'unidad_contenido_types';
    public $primaryKey = 'codigounidadcontenido';
    public $incrementing = false;
    public $timestamps = false;

    public function prescriptions()
    {
        return $this->hasMany('App\Http\Models\Prescriptions\Prescription', 'unid_contenido', 'codigounidadcontenido');
    }
}
