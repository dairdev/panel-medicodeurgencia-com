<?php

namespace App\Http\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;

class PrescriptionVadmin extends Model
{
    public $table = "prescriptions_vadmin";
    public $timestamps = false;

    public function prescription_forfarm()
    {
        return $this->belongsTo('App\Http\Models\Prescriptions\PrescriptionForFarm');
    }

    public function via_admin_type()
    {
        return $this->belongsTo('App\Http\Models\Prescriptions\ViaAdministracionType', 'cod_via_admin', 'codigoviaadministracion');
    }
}
