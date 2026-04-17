<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prescriptions\Prescription;
use App\Models\Prescriptions\PrincipioActivoType;
use App\Models\Prescriptions\Laboratorio;

class MedicinesController extends Controller
{

    public function searchMedicines(Request $request){
        $medicines = Prescription::where('des_prese', 'LIKE', '%'.$request->get('q').'%')
            ->select('des_prese as id', 'des_prese as text', 'cod_nacion')
            ->orderBy('des_prese', 'ASC')
            ->take(30)
            ->get();

        if($medicines->count() == 0){
            $medicines = [
                ['id' => $request->get('q'), 'text' => $request->get('q'), 'cod_nacion' => 999999111]
            ];
        }
        $data = [
            'items' => $medicines
        ];

        echo json_encode($data);
    }

    public function searchShowGet($text = ''){
        $medicines = Prescription::select(
            'prescriptions.cod_nacion', 
            'prescriptions.des_nomco', 
            'prescriptions.slug', 
            'prescriptions.des_prese'
        )
            ->leftJoin('prescriptions_formfarm', 'prescriptions.cod_nacion', '=', 'prescriptions_formfarm.cod_nacion')
            ->leftJoin('prescriptions_pactivos', 'prescriptions_formfarm.id', '=', 'prescriptions_pactivos.prescription_forfarm_id')
            ->leftJoin('principios_activos_types', 'prescriptions_pactivos.cod_principio_activo', '=', 'principios_activos_types.nroprincipioactivo')
            ->where('des_prese', 'like', '%'.$text.'%')
            ->orWhere('principioactivo', 'like', '%'.$text.'%' )
            ->orderBy('des_nomco', 'ASC')
            ->paginate(50);

        echo json_encode($medicines);
    }

    public function medicineShow(Request $request, $cod_nacion){
        //$medicine_data = Prescription::where('cod_nacion', $cod_nacion)->first();
        $medicine_data = file_get_contents('https://clinic.recetamedica.es/medicines/api-medicine/'.$cod_nacion);
        echo $medicine_data;
        
    }
}
