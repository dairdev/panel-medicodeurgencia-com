<?php

namespace App\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;

class PrescriptionNotasseguridad extends Model
{
    public $table = 'prescriptions_notasseguridades';
    public $primaryKey = 'numero_nota_seguridad';
    public $incrementing = false;
    public $timestamps = false;

    public function prescription()
    {
        return $this->belongsTo('App\Models\Prescriptions\Prescription', 'cod_nacion', 'cod_nacion');
    }
}
