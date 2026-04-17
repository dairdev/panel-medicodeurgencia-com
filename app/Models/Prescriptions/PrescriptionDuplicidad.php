<?php

namespace App\Http\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;

class PrescriptionDuplicidad extends Model
{
    public $table = "prescriptions_duplicidades";
    public $timestamps = false;

    public function prescription_atc()
    {
        return $this->belongsTo('App\Http\Models\Prescriptions\PrescriptionAtc');
    }
}
