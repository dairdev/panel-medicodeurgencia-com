<?php

namespace App\Http\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;

class ViaAdministracionType extends Model
{
    public $table = "vias_administracion_types";
    public $primaryKey = 'codigoviaadministracion';
    public $incrementing = false;
    public $timestamps = false;

    public function prescription_vadmins()
    {
        return $this->hasMany('App\Http\Models\Prescriptions\PrescriptionVadmin', 'codigoviaadministracion', 'cod_via_admin');
    }
}
