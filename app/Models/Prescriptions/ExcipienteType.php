<?php

namespace App\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;

class ExcipienteType extends Model
{
    public $table = "excipientes_types";
    public $primaryKey = 'codigoedo';
    public $incrementing = false;
    public $timestamps = false;

    public function prescription_excipientes()
    {
        return $this->hasMany('App\Models\Prescriptions\PrescriptionExcipiente', 'cod_excipiente', 'codigoedo');
    }

}
