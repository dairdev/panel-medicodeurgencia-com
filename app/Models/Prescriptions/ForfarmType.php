<?php

namespace App\Http\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;

class ForfarmType extends Model
{
    public $table = "forfarm_types";
    public $primaryKey = 'codigoformafarmaceutica';
    public $incrementing = false;
    public $timestamps = false;

    public function prescription_forfarms()
    {
        return $this->hasMany('App\Http\Models\Prescriptions\PrescriptionForFarm', 'cod_forfar', 'codigoformafarmaceutica');
    }

    public function forfarmsimpl_type()
    {
        return $this->belongsTo('App\Http\Models\Prescriptions\ForFarmsimplType', 'codigoforfarsimpl', 'codigoforfarsimpl');
    }
}
