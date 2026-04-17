<?php

namespace App\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;
//use EloquentFilter\Filterable;

class Laboratorio extends Model
{

    //use Filterable;

    public $table = "laboratorios";
    public $primaryKey = 'codigolaboratorio';
    public $incrementing = false;
    public $timestamps = false;

    public function presp_lab_titular()
    {
        return $this->hasMany('App\Models\Prescriptions\Prescription', 'laboratorio_titular', 'codigolaboratorio');
    }

    public function presp_lab_com()
    {
        return $this->hasMany('App\Models\Prescriptions\Prescription', 'laboratorio_comercializador', 'codigolaboratorio');
    }
}
