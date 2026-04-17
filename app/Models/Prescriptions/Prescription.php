<?php

namespace App\Models\Prescriptions;

use Illuminate\Database\Eloquent\Model;
//use EloquentFilter\Filterable;

class Prescription extends Model
{

    public $table = "prescriptions";
    public $primaryKey = 'cod_nacion';
    public $incrementing = false;
    public $timestamps = false;

    protected $dates = [
        'fecha_autorizacion',
        'fec_sitreg_presen',
        'fecha_situacion_registro',
        'fec_comer'
    ];

    public function dcpf_type()
    {
        return $this->belongsTo('App\Models\Prescriptions\DcpfType', 'cod_dcpf', 'codigodcpf');
    }

    public function dcp_type()
    {
        return $this->belongsTo('App\Models\Prescriptions\DcpType', 'cod_dcpf', 'codigodcpf');
    }

    public function dcsa_type()
    {
        return $this->belongsTo('App\Models\Prescriptions\DcsaType', 'cod_dcsa', 'codigodcsa');
    }

    public function envase_type()
    {
        return $this->belongsTo('App\Models\Prescriptions\EnvaseType', 'cod_envase', 'codigoenvase');
    }

    public function lab_titular()
    {
        return $this->belongsTo('App\Models\Prescriptions\Laboratorio', 'laboratorio_titular', 'codigolaboratorio');
    }

    public function lab_comercial()
    {
        return $this->belongsTo('App\Models\Prescriptions\Laboratorio', 'laboratorio_comercializador', 'codigolaboratorio');
    }

    public function unidad_contenido_type()
    {
        return $this->belongsTo('App\Models\Prescriptions\UnidadContenidoType', 'unid_contenido', 'codigounidadcontenido');
    }

    public function situreg()
    {
        return $this->belongsTo('App\Models\Prescriptions\Situacion', 'cod_sitreg', 'codigosituacionregistro');
    }

    public function situreg_presen()
    {
        return $this->belongsTo('App\Models\Prescriptions\Situacion', 'cod_sitreg_presen', 'codigosituacionregistro');
    }

    public function prescription_atc()
    {
        return $this->hasOne('App\Models\Prescriptions\PrescriptionAtc', 'cod_nacion', 'cod_nacion');
    }

    public function notas_seguridad()
    {
        return $this->hasMany('App\Models\Prescriptions\PrescriptionNotasseguridad', 'cod_nacion', 'cod_nacion');
    }

    public function prescription_forfarm()
    {
        return $this->hasOne('App\Models\Prescriptions\PrescriptionForFarm', 'cod_nacion', 'cod_nacion');
    }

    public function similar()
    {
        return $this->hasMany('App\Models\Prescriptions\Prescription', 'nro_definitivo', 'nro_definitivo');
    }
}
