<?php

namespace App\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;
//use EloquentFilter\Filterable;

class PrescriptionPactivo extends Model
{
    //use FIlterable;

    public $table = "prescriptions_pactivos";
    public $timestamps = false;

    public function prescription_forfarm()
    {
        return $this->belongsTo('App\Models\Prescriptions\PrescriptionForFarm');
    }

    public function principio_activo_type()
    {
        return $this->belongsTo('App\Models\Prescriptions\PrincipioActivoType', 'cod_principio_activo', 'nroprincipioactivo');
    }
}
