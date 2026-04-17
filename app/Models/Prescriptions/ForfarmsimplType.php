<?php

namespace App\Http\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;

class ForfarmsimplType extends Model
{
    public $table = "forfarmsimpl_types";
    public $primaryKey = 'codigoforfarsimpl';
    public $incrementing = false;
    public $timestamps = false;

    public function prescription_forfarms()
    {
        return $this->hasMany('App\Http\Models\Prescriptions\PrescriptionForFarm', 'cod_forfar_simplificada', 'codigoforfarsimpl');
    }

    public function forfarm_types()
    {
        return $this->hasMany('App\Http\Models\Prescriptions\ForfarmType', 'codigoforfarsimpl', 'codigoforfarsimpl');
    }
}
