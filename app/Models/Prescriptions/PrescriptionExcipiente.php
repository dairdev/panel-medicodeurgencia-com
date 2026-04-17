<?php

namespace App\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;

class PrescriptionExcipiente extends Model
{
    public $table = "prescriptions_excipientes";
    public $timestamps = false;

    public function excipiente_type()
    {
        return $this->belongsTo('App\Models\Prescriptions\ExcipienteType', 'cod_excipiente', 'codigoedo');
    }

    public function prescription_forfarm()
    {
        return $this->belongsTo('App\Models\Prescriptions\PrescriptionForFarm');
    }
}
