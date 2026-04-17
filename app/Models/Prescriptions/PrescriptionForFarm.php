<?php

namespace App\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;
//use EloquentFilter\Filterable;

class PrescriptionForfarm extends Model
{
    //use Filterable;

    public $table = 'prescriptions_formfarm';
    public $timestamps = false;

    public function forfarmsimpl_type()
    {
        return $this->belongsTo('App\Models\Prescriptions\ForfarmsimplType', 'cod_forfar_simplificada', 'codigoforfarsimpl');
    }

    public function forfarm_type()
    {
        return $this->belongsTo('App\Models\Prescriptions\ForfarmsimplType', 'cod_forfar', 'codigoformafarmaceutica');
    }

    public function prescription()
    {
        return $this->belongsTo('App\Models\Prescriptions\Prescription', 'cod_nacion', 'cod_nacion');
    }

    public function prescription_pactivos()
    {
        return $this->hasMany('App\Models\Prescriptions\PrescriptionPactivo');
    }

    public function prescription_excipientes()
    {
        return $this->hasMany('App\Models\Prescriptions\PrescriptionExcipiente');
    }

    public function prescription_vias_admin()
    {
        return $this->hasMany('App\Models\Prescriptions\PrescriptionVadmin');
    }
}
