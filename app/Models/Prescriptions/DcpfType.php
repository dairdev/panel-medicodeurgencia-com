<?php

namespace App\Http\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;

class DcpfType extends Model
{
    public $table = "dcpf_types";
    public $primaryKey = 'codigodcpf';
    public $incrementing = false;
    public $timestamps = false;

    public function prescriptions()
    {
        return $this->hasMany('App\Http\Models\Prescriptions\Prescription', 'cod_dcpf', 'codigodcpf');
    }
}
