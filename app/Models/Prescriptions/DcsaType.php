<?php

namespace App\Http\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;

class DcsaType extends Model
{
    public $table = "dcsa_types";
    public $primaryKey = 'codigodcsa';
    public $incrementing = false;
    public $timestamps = false;

    public function prescriptions()
    {
        return $this->hasMany('App\Http\Models\Prescriptions\Prescription', 'cod_dcsa', 'codigodcsa');
    }
}
