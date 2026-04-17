<?php

namespace App\Http\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;

class DcpType extends Model
{
    public $table = "dcp_types";
    public $primaryKey = 'codigodcp';
    public $incrementing = false;
    public $timestamps = false;

    public function prescriptions()
    {
        return $this->hasMany('App\Http\Models\Prescriptions\Prescription', 'cod_dcp', 'codigodcp');
    }

}
