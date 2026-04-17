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

use App\Models\Subchapter;
use App\Models\Chapter;
use App\Models\Upload;
use App\Models\Note;

class SubchaptersController extends Controller
{
    public $show_action = true;
	public $view_col = 'name';
	public $listing_cols = ['id', 'image', 'name', 'orden', 'chapter_id', 'slug', 'created_at', 'updated_at'];
	
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
        ],
    ];
    /**
     * Display a listing of the Subchapters.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $module = LAModule::get('Subchapters');

        if(LAModule::hasAccess($module->id)) {
            if($request->ajax() && !isset($request->_pjax)) {
                // TODO: Implement good Query Builder
                return Subchapter::all();
            } else {
                return View('la.subchapters.index', [
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
     * Show the form for creating a new subchapter.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created subchapter in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        if(LAModule::hasAccess("Subchapters", "create")) {
            if($request->ajax() && !isset($request->quick_add)) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Subchapters", $request);

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
            $request->request->add(['slug' => strtolower(str_replace(' ','-', $request->get('name')))]);
			$request->request->add(['name' => str_replace(' ','-', $request->get('name'))]);
            $insert_id = LAModule::insert("Subchapters", $request);
            $this->generatePdf($insert_id);
            $subchapter = Subchapter::find($insert_id);

            // Add LALog
            LALog::make("Subchapters.SUBCHAPTER_CREATED", [
                'title' => "Subchapter Created",
                'module_id' => 'Subchapters',
                'context_id' => $subchapter->id,
                'content' => $subchapter,
                'user_id' => Auth::user()->id,
                'notify_to' => "[]"
            ]);

            if($request->ajax() || isset($request->quick_add)) {
                return response()->json([
                    'status' => 'success',
                    'object' => $subchapter,
                    'message' => 'Subchapter updated successfully!',
                    'redirect' => url(config('laraadmin.adminRoute') . '/subchapters')
                ], 201);
            } else {
                return redirect()->route(config('laraadmin.adminRoute') . '.subchapters.index');
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
     * Display the specified subchapter.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id subchapter ID
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        if(LAModule::hasAccess("Subchapters", "view")) {

            $subchapter = Subchapter::find($id);
            if(isset($subchapter->id)) {
                if($request->ajax() && !isset($request->_pjax)) {
                    return $subchapter;
                } else {
                    $module = LAModule::get('Subchapters');
                    $module->row = $subchapter;

                    return view('la.subchapters.show', [
                        'module' => $module,
                        'view_col' => $module->view_col,
                        'no_header' => true,
                        'no_padding' => "no-padding"
                    ])->with('subchapter', $subchapter);
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
                        'record_name' => ucfirst("subchapter"),
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
     * Show the form for editing the specified subchapter.
     *
     * @param int $id subchapter ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(LAModule::hasAccess("Subchapters", "edit")) {
            $subchapter = Subchapter::find($id);
            if(isset($subchapter->id)) {
                $module = LAModule::get('Subchapters');

                $module->row = $subchapter;

                return view('la.subchapters.edit', [
                    'module' => $module,
                    'view_col' => $this->view_col,
                ])->with('subchapter', $subchapter);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("subchapter"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * Update the specified subchapter in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id subchapter ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(LAModule::hasAccess("Subchapters", "edit")) {
            if($request->ajax()) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Subchapters", $request, true);

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

            $subchapter_old = Subchapter::find($id);

            if(isset($subchapter_old->id)) {

                // Update Data
                LAModule::updateRow("Subchapters", $request, $id);

                $subchapter_new = Subchapter::find($id);

                // Add LALog
                LALog::make("Subchapters.SUBCHAPTER_UPDATED", [
                    'title' => "Subchapter Updated",
                    'module_id' => 'Subchapters',
                    'context_id' => $subchapter_new->id,
                    'content' => [
                        'old' => $subchapter_old,
                        'new' => $subchapter_new
                    ],
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'object' => $subchapter_new,
                        'message' => 'Subchapter updated successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/subchapters')
                    ], 200);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.subchapters.index');
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
                        'record_name' => ucfirst("subchapter"),
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
     * Remove the specified subchapter from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id subchapter ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        if(LAModule::hasAccess("Subchapters", "delete")) {

            $subchapter = Subchapter::find($id);
            if(isset($subchapter->id)) {
                $subchapter->delete();

                // Add LALog
                LALog::make("Subchapters.SUBCHAPTER_DELETED", [
                    'title' => "Subchapter Deleted",
                    'module_id' => 'Subchapters',
                    'context_id' => $subchapter->id,
                    'content' => $subchapter,
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Record Deleted successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/subchapters')
                    ], 204);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.subchapters.index');
                }
            } else {
                if($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Record not found'
                    ], 404);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.subchapters.index');
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
        $module = LAModule::get('Subchapters');
        $listing_cols = LAModule::getListingColumns('Subchapters');

        $values = DB::table('subchapters')->select($this->listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();

        $fields_popup = LAModuleField::getModuleFields('Subchapters');

        for($i = 0; $i < count($data->data); $i++) {

            $subchapter = Subchapter::find($data->data[$i]->id);

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
                    $data->data[$i]->$col = '<a '.config('laraadmin.ajaxload').' href="' . url(config('laraadmin.adminRoute') . '/subchapters/' . $data->data[$i]->id) . '">' . $data->data[$i]->$col . '</a>';
                }
                if($col == 'image' && !empty($data->data[$i]->image)) {
					$img = Upload::find($data->data[$i]->image);
					$data->data[$i]->$col = '<img src="'.$img->url().'" width="70" height="70">';
				}	
                if(($col == 'created_at' || $col == 'updated_at') && !empty($data->data[$i]->$col)) {
					$data->data[$i]->$col = date('d/m/Y H:i', strtotime($data->data[$i]->$col));
				}	
                // else if($col == "author") {
                //    $data->data[$i]->$col;
                // }
            }

            if($this->show_action) {
                $output = '';
                if(LAModule::hasAccess("Subchapters", "edit")) {
                    $output .= '<a '.config('laraadmin.ajaxload').' href="' . url(config('laraadmin.adminRoute') . '/subchapters/' . $data->data[$i]->id . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>';
                }

                if(LAModule::hasAccess("Subchapters", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.subchapters.destroy', $data->data[$i]->id], 'method' => 'delete', 'style' => 'display:inline']);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit" data-toggle="tooltip" title="Delete"><i class="fa fa-times"></i></button>';
                    $output .= Form::close();
                }

                $output .= '<a href="'.url(config('laraadmin.adminRoute') . '/subchapters/'.$data->data[$i]->id.'/download-pdf').'" class="btn btn-primary btn-xs" style="display:inline;padding:2px 5px 3px 5px; margin-left: 3px;"><i class="fa fa-file"></i></a>';

                $data->data[$i]->dt_action = (string)$output;
            }
        }
        $out->setData($data);
        return $out;
    }
    public function uploadimage(Request $request)
	{
		$cover = $request->file('image');
		$extension = $cover->getClientOriginalExtension();
		Storage::disk('public_uploads')->put($cover->getFilename().'.'.$extension,  File::get($cover));
		$path = '/'.str_pad($request->get('subchapter_id'), 2, "0", STR_PAD_LEFT).'-'.$this->folders[$request->get('subchapter_id')]['value'].'/media/'.$cover->getFilename().'.'.$extension;
		if(!Storage::disk('public_uploads')->put($path, File::get($cover))) {
		    return false;
		}

		return '/images/book'.$path;
	}

	public function downloadPdf($id){
        $subchapter = Subchapter::find($id);
        $chapter = Chapter::find($subchapter->chapter_id);
        if(isset($subchapter)){
            if(isset($chapter)){
                $folder_specialty = str_pad($chapter->specialty_id, 2, '0', STR_PAD_LEFT).'-'.$this->folders[$chapter->specialty_id]['value'].'/';
				
        
                $path = storage_path('pdfs/'.$folder_specialty.str_replace('-', '_', $subchapter->slug).'.pdf');
				
				//dd($path);
				
                if(file_exists($path)){
                    return response()->file($path, ['Content-Disposition' => 'inline; filename="'. $subchapter->slug .'.pdf"']);
                }else{
                    return redirect()->back()->withErrors(['No se encontro el archivo '. $subchapter->slug])->withInput();
                    //echo '<script>alert("No se encontro el archivo '. $subchapter->slug .'")</script>';

                     $module = LAModule::get('Subchapters');

                    if(LAModule::hasAccess($module->id)) {
                                        
                  return View('la.subchapters.index', [
                                 'show_actions' => $this->show_action,
                                                'listing_cols' => $this->listing_cols,
                                                'module' => $module
                                            ]);
                                       
                                            }
        
    }
}
        }
    }

    public function renderChapter($id, $tipo = 'html'){
        $chapter = Subchapter::find($id);
        
        if($tipo == 'pdf'){
        	return View('la.chapters.pdf', ['content' => $chapter->body, 'title' => $chapter->name, 'video' => $chapter->video]);	
        }

        return View('la.chapters.html', ['content' => $chapter->body, 'title' => $chapter->name, 'video' => $chapter->video]);
    }

    public function renderDetail($type, $id, $code = ''){
    	$user_id = '';
    	$note = '';
    	if(!empty($code)){
			$s = Codigo::select('lectore_id')->where('codigo', $code)->first();
    		$user_id = $s->lectore_id;

    		//Buscamos Notas
    		$note = Note::where('type', $type)->where('lectore_id', $user_id)->where('chapter_id', $id)->first();
    	}

    	if($type == 'subchapters'){
			$chapter = Subchapter::find($id);
		}else{
			$chapter = Chapter::find($id);
		}
        
        return View('la.chapters.html', [
        	'content' => $chapter->body, 
        	'title' => $chapter->name, 
        	'video' => $chapter->video, 
        	'user_id' => $user_id, 
        	'type' => $type,
        	'c_id' => $id,
        	'note' => $note,
        	'code' => $code
        ]);
    }

    public function generatePdf($id){
        $subchapter = Subchapter::find($id);

        $chapter = Chapter::find($subchapter->chapter_id);

        $folder_specialty = str_pad($chapter->specialty_id, 2, '0', STR_PAD_LEFT).'-'.$this->folders[$chapter->specialty_id]['value'].'/';

        $folderOut = storage_path('pdfs/'.$folder_specialty.str_replace('-', '_', trim($subchapter->slug)).'.pdf');

        $specialty = $this->folders[$chapter->specialty_id]['name'];

        $locale = 'es_ES.utf-8';
        setlocale(LC_ALL, $locale);
        putenv('LC_ALL='.$locale);

        shell_exec('/usr/bin/wkhtmltopdf -B 18 -L 0 -R 0 -T 20 --encoding utf-8 --header-spacing 4 --header-html https://panel.medicodeurgencia.com/admin/chapters/render-chapter-header --footer-html https://panel.medicodeurgencia.com/admin/chapters/render-chapter-footer --page-offset 0 --footer-spacing 2 --image-dpi 300 --no-outline --replace "color" "0" --replace "specialty" "'.$specialty.'" https://panel.medicodeurgencia.com/admin/subchapters/render-subchapter/'.$id.'/pdf '.$folderOut);
    }
}
