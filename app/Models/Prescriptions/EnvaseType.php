<?php

namespace App\Http\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;

class EnvaseType extends Model
{
    public $table = 'envases_types';
    public $primaryKey = 'codigoenvase';
    public $incrementing = false;
    public $timestamps = false;

    public function prescriptions()
    {
        return $this->hasMany('App\Http\Models\Prescriptions\Prescription', 'cod_envase', 'codigoenvase');
    }
}
