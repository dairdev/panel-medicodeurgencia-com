<?php
/***
 * Controller generated using LaraAdmin
 * Help: https://laraadmin.com
 * LaraAdmin is open-sourced software licensed under the MIT license.
 * Developed by: Dwij IT Solutions
 * Developer Website: https://dwijitsolutions.com
 */

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Collective\Html\FormFacade as Form;
use App\Helpers\LAHelper;
use App\Models\LAModule;
use App\Models\LAModuleField;
use App\Models\LALog;

use App\Models\Specialty;
use App\Models\Upload;
use App\Models\Chapter;
use App\Models\Subchapter;

class SpecialtiesController extends Controller
{
    public $show_action = true;
    public $view_col = 'name';
	public $listing_cols = ['id', 'name', 'image', 'orden', 'pdf'];
	
	public $folders = [
        '1' => [
            'value' => 'cardiologia',
            'name' => 'Cardiología',
        ],
        '2' => [
            'value' => 'cirugia_general',
            'name' => 'Cirugía general',
        ],
        '3' => [
            'value' => 'cirugia_maxilofacial',
            'name' => 'Cirugía maxilofacial',
        ],
        '4' => [
            'value' => 'cirugia_plastica',
            'name' => 'Cirugía plástica',
        ],
        '5' => [
            'value' => 'cirugia_vascular',
            'name' => 'Cirugía vascular',
        ],
        '6' => [
            'value' => 'dermatologia',
            'name' => 'Dermatología',
        ],
        '7' => [
            'value' => 'digestivo',
            'name' => 'Digestivo',
        ],
        '8' => [
            'value' => 'endocrinologia',
            'name' => 'Endocrinología',
        ],
        '9' => [
            'value' => 'ginecologia',
            'name' => 'Ginecología',
        ],
        '10' => [
            'value' => 'hematologia',
            'name' => 'Hematología',
        ],
        '11' => [
            'value' => 'inmunologia',
            'name' => 'Inmunología',
        ],
        '12' => [
            'value' => 'medicina_interna',
            'name' => 'Medicina interna',
        ],
        '13' => [
            'value' => 'neumologia',
            'name' => 'Neumología',
        ],
        '14' => [
            'value' => 'nefrologia',
            'name' => 'Nefrología',
        ],
        '15' => [
            'value' => 'neurocirugia',
            'name' => 'Neurocirugía',
        ],
        '16' => [
            'value' => 'neurologia',
            'name' => 'Neurología',
        ],
        '17' => [
            'value' => 'oftalmologia',
            'name' => 'Oftalmología',
        ],
        '18' => [
            'value' => 'oncologia_medica',
            'name' => 'Oncología médica',
        ],
        '19' => [
            'value' => 'otorrinolaringologia',
            'name' => 'Otorrinolaringología',
        ],
        '20' => [
            'value' => 'pediatria_y_neonatologia',
            'name' => 'Pediatría y neonatología',
        ],
        '21' => [
            'value' => 'psiquiatria',
            'name' => 'Psiquiatría',
        ],
        '22' => [
            'value' => 'reumatologia',
            'name' => 'Reumatología',
        ], '',
        '23' => [
            'value' => 'traumatologia',
            'name' => 'Traumatología',
        ],
        '24' => [
            'value' => 'urologia',
            'name' => 'Urología',
        ],
        '25' => [
            'value' => 'procedimientos_en_urgencias',
            'name' => 'Procedimientos en urgencias',
        ],
        '26' => [
            'value' => 'dolor_en_urgencias',
            'name' => 'Dolor en urgencias',
        ],
        '27' => [
            'value' => 'intoxicaciones',
            'name' => 'Intoxicaciones',
        ],
        '28' => [
            'value' => 'infiltraciones_en_urgencias',
            'name' => 'Infiltraciones en urgencias',
        ],
        '29' => [
            'value' => 'rehabilitacion',
            'name' => 'Rehabilitación',
        ],
        '30' => [
            'value' => 'liquidos_biologicos',
            'name' => 'Líquidos biológicos',
        ],
        '31' => [
            'value' => 'aspectos_generales',
            'name' => 'Aspectos generales',
        ],
        '32' => [
            'value' => 'radiologia',
            'name' => 'Radiología',
        ],
        '33' => [
            'value' => 'soporte_vital',
            'name' => 'Soporte vitál',
        ],
        '34' => [
            'value' => 'uci',
            'name' => 'UCI',
        ],
        '36' => [
            'value' => 'medicina_del_trabajo',
            'name' => 'Medicina del Trabajo',
        ],
        '37' => [
            'value' => 'infecciosas',
            'name' => 'Infecciosas',
        ],
        '38' => [
            'value' => 'neurofisiologia',
            'name' => 'Neurofisiología',
        ],
        '39' => [
            'value' => 'extrahospitalaria',
            'name' => 'Extrahospitalaria',
        ],
        '40' => [
            'value' => 'cirugiacardiaca',
            'name' => 'Cirugía Cardiaca',
        ],
        '42' => [
            'value' => 'anestesiologia',
            'name' => 'Anestesiología y reanimación',
        ],
        '43' => [
            'value' => 'geriatria',
            'name' => 'Geriatría',
        ],
        '44' => [
            'value' => 'calculadoras',
            'name' => 'Calculadoras',
        ]
    ];
    /**
     * Display a listing of the Specialties.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $module = LAModule::get('Specialties');

        if(LAModule::hasAccess($module->id)) {
            if($request->ajax() && !isset($request->_pjax)) {
                // TODO: Implement good Query Builder
                return Specialty::all();
            } else {
                return View('la.specialties.index', [
                    'show_actions' => $this->show_action,
                    'listing_cols' => $this->listing_cols,
                    'module' => $module
                ]);
            }
        } else {
            if($request->ajax() && !isset($request->_pjax)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized Access'
                ], 403);
            } else {
                return redirect(config('laraadmin.adminRoute') . "/");
            }
        }
    }

    /**
     * Show the form for creating a new specialty.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created specialty in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        if(LAModule::hasAccess("Specialties", "create")) {
            if($request->ajax() && !isset($request->quick_add)) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Specialties", $request);

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()) {
                if($request->ajax() || isset($request->quick_add)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Validation error',
                        'errors' => $validator->errors()
                    , 400]);
                } else {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
            }

            $insert_id = LAModule::insert("Specialties", $request);

            $specialty = Specialty::find($insert_id);

            // Add LALog
            LALog::make("Specialties.SPECIALTY_CREATED", [
                'title' => "Specialty Created",
                'module_id' => 'Specialties',
                'context_id' => $specialty->id,
                'content' => $specialty,
                'user_id' => Auth::user()->id,
                'notify_to' => "[]"
            ]);

            if($request->ajax() || isset($request->quick_add)) {
                return response()->json([
                    'status' => 'success',
                    'object' => $specialty,
                    'message' => 'Specialty updated successfully!',
                    'redirect' => url(config('laraadmin.adminRoute') . '/specialties')
                ], 201);
            } else {
                return redirect()->route(config('laraadmin.adminRoute') . '.specialties.index');
            }
        } else {
            if($request->ajax() || isset($request->quick_add)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized Access'
                ], 403);
            } else {
                return redirect(config('laraadmin.adminRoute') . "/");
            }
        }
    }

    /**
     * Display the specified specialty.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id specialty ID
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        if(LAModule::hasAccess("Specialties", "view")) {

            $specialty = Specialty::find($id);
            if(isset($specialty->id)) {
                if($request->ajax() && !isset($request->_pjax)) {
                    return $specialty;
                } else {
                    $module = LAModule::get('Specialties');
                    $module->row = $specialty;

                    return view('la.specialties.show', [
                        'module' => $module,
                        'view_col' => $module->view_col,
                        'no_header' => true,
                        'no_padding' => "no-padding",
                        'chapters' => Chapter::where(['specialty_id' => $specialty->id])->orderBy('orden', 'asc')->get()
                    ])->with('specialty', $specialty);
                }
            } else {
                if($request->ajax() && !isset($request->_pjax)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Record not found'
                    ], 404);
                } else {
                    return view('errors.404', [
                        'record_id' => $id,
                        'record_name' => ucfirst("specialty"),
                    ]);
                }
            }
        } else {
            if($request->ajax() && !isset($request->_pjax)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized Access'
                ], 403);
            } else {
                return redirect(config('laraadmin.adminRoute') . "/");
            }
        }
    }

    /**
     * Show the form for editing the specified specialty.
     *
     * @param int $id specialty ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(LAModule::hasAccess("Specialties", "edit")) {
            $specialty = Specialty::find($id);
            if(isset($specialty->id)) {
                $module = LAModule::get('Specialties');

                $module->row = $specialty;

                return view('la.specialties.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('specialty', $specialty);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("specialty"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * Update the specified specialty in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id specialty ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(LAModule::hasAccess("Specialties", "edit")) {
            if($request->ajax()) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Specialties", $request, true);

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()) {
                if($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Validation error',
                        'errors' => $validator->errors()
                    ], 400);
                } else {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
            }

            $specialty_old = Specialty::find($id);

            if(isset($specialty_old->id)) {

                // Update Data
                LAModule::updateRow("Specialties", $request, $id);

                $specialty_new = Specialty::find($id);

                // Add LALog
                LALog::make("Specialties.SPECIALTY_UPDATED", [
                    'title' => "Specialty Updated",
                    'module_id' => 'Specialties',
                    'context_id' => $specialty_new->id,
                    'content' => [
                        'old' => $specialty_old,
                        'new' => $specialty_new
                    ],
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'object' => $specialty_new,
                        'message' => 'Specialty updated successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/specialties')
                    ], 200);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.specialties.index');
                }
            } else {
                if($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Record not found'
                    ], 404);
                } else {
                    return view('errors.404', [
                        'record_id' => $id,
                        'record_name' => ucfirst("specialty"),
                    ]);
                }
            }

        } else {
            if($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized Access'
                ], 403);
            } else {
                return redirect(config('laraadmin.adminRoute') . "/");
            }
        }
    }

    /**
     * Remove the specified specialty from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id specialty ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        if(LAModule::hasAccess("Specialties", "delete")) {

            $specialty = Specialty::find($id);
            if(isset($specialty->id)) {
                $specialty->delete();

                // Add LALog
                LALog::make("Specialties.SPECIALTY_DELETED", [
                    'title' => "Specialty Deleted",
                    'module_id' => 'Specialties',
                    'context_id' => $specialty->id,
                    'content' => $specialty,
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Record Deleted successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/specialties')
                    ], 204);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.specialties.index');
                }
            } else {
                if($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Record not found'
                    ], 404);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.specialties.index');
                }
            }
        } else {
            if($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized Access'
                ], 403);
            } else {
                return redirect(config('laraadmin.adminRoute') . "/");
            }
        }
    }

    /**
     * Server side Datatable fetch via Ajax
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function dtajax(Request $request)
    {
        $module = LAModule::get('Specialties');
        $listing_cols = $this->listing_cols;

        $values = DB::table('specialties')->select($this->listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();

        $fields_popup = $this->listing_cols;

        for($i = 0; $i < count($data->data); $i++) {

            $specialty = Specialty::find($data->data[$i]->id);

            for($j = 0; $j < count($this->listing_cols); $j++) {
                $col = $this->listing_cols[$j];
                if(isset($fields_popup[$col]) && str_starts_with($fields_popup[$col]->popup_vals, "@")) {
                    if($col == $module->view_col) {
                        $data->data[$i]->$col = LAModuleField::getFieldValue($fields_popup[$col], $data->data[$i]->$col);
                    } else {
                        $data->data[$i]->$col = LAModuleField::getFieldLink($fields_popup[$col], $data->data[$i]->$col);
                    }
                }
                if($col == $module->view_col) {
                    $data->data[$i]->$col = '<a '.config('laraadmin.ajaxload').' href="' . url(config('laraadmin.adminRoute') . '/specialties/' . $data->data[$i]->id) . '">' . $data->data[$i]->$col . '</a>';
                }

                if($col == 'image' && !empty($data->data[$i]->image)) {
					$img = Upload::find($data->data[$i]->image);
                    
                        $data->data[$i]->$col = '<img src="'.$img->url().'" width="70" height="70">';
                    
					
				}		
                // else if($col == "author") {
                //    $data->data[$i]->$col;
                // }
            }

            if($this->show_action) {
                $output = '';
                if(LAModule::hasAccess("Specialties", "edit")) {
                    $output .= '<a '.config('laraadmin.ajaxload').' href="' . url(config('laraadmin.adminRoute') . '/specialties/' . $data->data[$i]->id . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>';
                }

                if(LAModule::hasAccess("Specialties", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.specialties.destroy', $data->data[$i]->id], 'method' => 'delete', 'style' => 'display:inline']);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit" data-toggle="tooltip" title="Delete"><i class="fa fa-times"></i></button>';
                    $output .= Form::close();
                }
                if(!empty($data->data[$i]->pdf) && $data->data[$i]->pdf == 1){
					$output .= '<a class="btn btn-danger btn-xs disabled '. $data->data[$i]->pdf.'" style="display:inline;padding:2px 5px 3px 5px; margin-left: 3px;"><i class="fa fa-spinner"></i></a>';
				}elseif(!empty($data->data[$i]->pdf) && $data->data[$i]->pdf == 2){
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/specialties/'.$data->data[$i]->id.'/download-pdf').'" class="btn btn-primary btn-xs" style="display:inline;padding:2px 5px 3px 5px; margin-left: 3px;" target="_blank"><i class="fa fa-file"></i></a>';
				}
                $data->data[$i]->dt_action = (string)$output;
            }
        }
        $out->setData($data);
        return $out;
    }
    public function generatePdf($id){
		$specialty = Specialty::find($id);
		//$specialty->pdf = 1;
		$specialty->save();

		$folder_specialty = str_pad($specialty->id, 2, '0', STR_PAD_LEFT).'-'.$this->folders[$specialty->id]['value'].'/';

        $folderOut = storage_path('pdfs/'.$folder_specialty.'especialidad.pdf');

        $specialty = $this->folders[$specialty->id]['name'];

        $locale = 'es_ES.utf-8';
        setlocale(LC_ALL, $locale);
        putenv('LC_ALL='.$locale);
        
        exec('/var/www/vhosts/medicodeurgencia.com/panel.medicodeurgencia.com/scripts/generate-pdf.sh "'.$specialty.'" '.$id.' "'.$folderOut.'" > /dev/null &');
        //exec('/usr/bin/wkhtmltopdf -B 18 -L 0 -R 0 -T 20 --encoding utf-8 --header-spacing 4 --header-html http://panel.medicodeurgencia.com/admin/chapters/render-chapter-header --footer-html http://panel.medicodeurgencia.com/admin/chapters/render-chapter-footer --page-offset 0 --footer-spacing 2 --image-dpi 300 --no-outline --replace "color" "0" --replace "specialty" "'.$specialty.'" http://panel.medicodeurgencia.com/admin/specialties/render-specialty/'.$id.' '.$folderOut. ' > /dev/null &');
	}

	public function renderSpecialty($id){
        $chapters = Chapter::where('specialty_id', $id)->orderBy('orden', 'asc')->get();

        foreach ($chapters as $key => $chapter) {
        	$subc = Subchapter::where(['chapter_id' => $chapter->id])->orderBy('orden', 'asc')->get();	
        	$chapters[$key]['subchapters'] = false;
        	if(count($subc) > 0){
        		$chapters[$key]['subchapters'] = $subc;
        	}
        }

        return View('la.specialties.pdf', ['chapters' => $chapters]);
    }

    public function generatePdfEnd($id){
		$specialty = Specialty::find($id);
		$specialty->pdf = 2;
		$specialty->save();
	}

	public function downloadPdf($id){
        $specialty = Specialty::find($id);
        
        $folder_specialty = str_pad($specialty->id, 2, '0', STR_PAD_LEFT).'-'.$this->folders[$specialty->id]['value'].'/';
        
        $path = storage_path('pdfs/'.$folder_specialty.'especialidad.pdf');
		return response()->file($path, ['Cache-Control' => 'no-store,no-cache, must-revalidate, post-check=0, pre-check=0', 'Pragma' => 'no-cache', 'Content-Disposition' => 'inline; filename="'. $specialty->name .'.pdf"']);
    }
}
