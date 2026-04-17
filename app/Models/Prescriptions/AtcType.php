<?php

namespace App\Http\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;

class AtcType extends Model
{
    public $table = "atc_types";
    public $primaryKey = 'nroatc';
    public $incrementing = false;
    public $timestamps = false;


    public function prescription_atcs()
    {
        return $this->hasMany('App\Http\Models\Prescriptions\PrescriptionAtc', 'cod_atc', 'codigoatc');
    }
}
