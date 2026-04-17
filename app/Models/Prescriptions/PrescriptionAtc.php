<?php

namespace App\Http\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;

class PrescriptionAtc extends Model
{
    public $table = "prescriptions_atcs";
    public $timestamps = false;

    public function atc_type()
    {
        return $this->belongsTo('App\Http\Models\Prescriptions\AtcType', 'cod_atc', 'codigoatc');
    }

    public function prescription()
    {
        return $this->belongsTo('App\Http\Models\Prescriptions\Prescription', 'cod_nacion', 'cod_nacion');
    }

    public function prescription_desaconsejados()
    {
        return $this->hasMany('App\Http\Models\Prescriptions\PrescriptionDesaconsejado');
    }

    public function prescription_interacciones()
    {
        return $this->hasMany('App\Http\Models\Prescriptions\PrescriptionInteraccion');
    }

    public function prescription_duplicidades()
    {
        return $this->hasMany('App\Http\Models\Prescriptions\PrescriptionDuplicidad');
    }
}
