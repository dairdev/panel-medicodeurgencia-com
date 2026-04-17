<?php

namespace App\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Cviebrock\EloquentSluggable\Sluggable;
//use EloquentFilter\Filterable;

class PrincipioActivoType extends Model
{
    //use Sluggable, SluggableScopeHelpers, Filterable;
    //use Sluggable, SluggableScopeHelpers;

    public $table = "principios_activos_types";
    public $primaryKey = 'nroprincipioactivo';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['slug'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable() : array {
        return [
            'slug' => [
                'source' => 'principioactivo'
            ]
        ];
    }

    public function prescription_pactivos()
    {
        return $this->hasMany('App\Models\Prescriptions\PrescriptionPactivo', 'cod_principio_activo', 'nroprincipioactivo');
    }
}
