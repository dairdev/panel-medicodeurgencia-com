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
        $medicine_data = Prescription::where('cod_nacion', $cod_nacion)->first();
      // dd($medicine_data->prescription_forfarm);
        $medicine['des_nomco'] = $medicine_data->des_nomco;
        $medicine['url_fictec'] = $medicine_data->url_fictec;
        $medicine['url_prosp'] = $medicine_data->url_prosp;
        $medicine['nro_definitivo'] = $medicine_data->nro_definitivo;
        $medicine['situacionregistro'] = $medicine_data->situreg->situacionregistro;
        $medicine['des_prese'] = $medicine_data->des_prese;
        if($medicine_data->lab_titular){
            $medicine['lab_titular']['laboratorio'] = $medicine_data->lab_titular->laboratorio;
            $medicine['lab_titular']['direccion'] = $medicine_data->lab_titular->direccion;
            $medicine['lab_titular']['localidad'] = $medicine_data->lab_titular->localidad;
            $medicine['lab_titular']['codigopostal'] = $medicine_data->lab_titular->codigopostal;
            $medicine['lab_titular']['cif'] = $medicine_data->lab_titular->cif;    
        }

        if($medicine_data->lab_comercial){
            $medicine['lab_comercial']['laboratorio'] = $medicine_data->lab_comercial->laboratorio;
            $medicine['lab_comercial']['direccion'] = $medicine_data->lab_comercial->direccion;
            $medicine['lab_comercial']['localidad'] = $medicine_data->lab_comercial->localidad;
            $medicine['lab_comercial']['codigopostal'] = $medicine_data->lab_comercial->codigopostal;
            $medicine['lab_comercial']['cif'] = $medicine_data->lab_comercial->cif;    
        }

        $medicine['prescription_pactivos'] = [];
        foreach ($medicine_data->prescription_forfarm->prescription_pactivos as $pas) {
            if($pas->principio_activo_type){
                $medicine['prescription_pactivos'][] = $pas->principio_activo_type->principioactivo;
            }
        }

        $medicine['prescription_excipientes'] = [];
        foreach ($medicine_data->prescription_forfarm->prescription_excipientes as $exc) {
            if($exc->excipiente_type){
                $medicine['prescription_excipientes'][] = $exc->excipiente_type->edo;
            }
        }

        $medicine['similar'] = [];
        if($medicine_data->similar){
            foreach($medicine_data->similar as $similar){
                $medicine['similar'][] = [
                    'slug' => $similar->slug,
                    'cod_nacion' => $similar->cod_nacion,
                    'des_prese' => $similar->des_prese,
                    'cod_sitreg_presen' => $similar->cod_sitreg_presen,
                    'situacionregistro' => $similar->situreg_presen->situacionregistro,
                    /*'fec_sitreg_presen' => $similar->fec_sitreg_presen->format('d/m/Y'),*/
                    'fec_sitreg_presen' => $similar->fec_sitreg_presen,
                    'sw_comercializado' => $similar->sw_comercializado
                ];
            }
        }

        $medicine['notas'] = [];
        if($medicine_data->notas_seguridad){
            foreach ($medicine_data->notas_seguridad as $nota) {
                $medicine['notas'][] = [
                    'url_nota_seguridad' => $nota->url_nota_seguridad,
                    'numero_nota_seguridad' => $nota->numero_nota_seguridad,
                    'referencia_nota_seguridad' => $nota->referencia_nota_seguridad,
                    'asunto_nota_seguridad' => $nota->asunto_nota_seguridad,
                    'fecha_nota_seguridad' => $nota->fecha_nota_seguridad,
                ];    
            }    
        }


        echo json_encode($medicine);
    }
}
