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

use App\Models\Chapter;
use App\Models\Subchapter;
use App\Models\Upload;

class ChaptersController extends Controller
{
    public $show_action = true;
    public $view_col = 'name';
	public $listing_cols = ['id', 'name', 'image', 'orden', 'specialty_id', 'created_at', 'updated_at'];

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
        '46' => [
            'value' => 'medicina-legal-y-forense',
            'name' => 'Medicina-Legal-y-Forense',
        ],
        '47' => [
            'value' => 'medicina-nuclear',
            'name' => 'Medicina Nuclear',
        ],
        '48' => [
            'value' => 'medicina-familiar-y-comunitaria',
            'name' => 'medicina-familiar-y-comunitaria',
        ],
        '49' => [
            'value' => 'alergologia',
            'name' => 'alergologia',
        ],
        '50' => [
            'value' => 'anatomia-patologica',
            'name' => 'anatomia-patologica',
        ],
        '51' => [
            'value' => 'analisis-clinico',
            'name' => 'analisis-clinico',
        ],
        '52' => [
            'value' => 'bioquimica-clinica',
            'name' => 'bioquimica-clinica',
        ],
        '53' => [
            'value' => 'psiquiatria-infantil-y-de-la-adolescencia',
            'name' => 'psiquiatria-infantil-y-de-la-adolescencia',
        ],
        '54' => [
            'value' => 'cirugia-toracica',
            'name' => 'cirugia-toracica',
        ],
        '55' => [
            'value' => 'farmacologia-clinica',
            'name' => 'farmacologia-clinica',
        ],
        '56' => [
            'value' => 'medicina-preventiva-y-salud-publica',
            'name' => 'medicina-preventiva-y-salud-publica',
        ],
        '57' => [
            'value' => 'psicologia-clinica',
            'name' => 'psicologia-clinica',
        ]
    ];

    /**
     * Display a listing of the Chapters.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $module = LAModule::get('Chapters');

        if(LAModule::hasAccess($module->id)) {
            if($request->ajax() && !isset($request->_pjax)) {
                // TODO: Implement good Query Builder
                return Chapter::all();
            } else {
                return View('la.chapters.index', [
                    'show_actions' => $this->show_action,
                    'listing_cols' => LAModule::getListingColumns('Chapters'),
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
     * Show the form for creating a new chapter.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created chapter in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        if(LAModule::hasAccess("Chapters", "create")) {
            if($request->ajax() && !isset($request->quick_add)) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Chapters", $request);

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
            $request->request->add(['slug' => str_replace(' ','-', $request->get('name')).'']);
            $insert_id = LAModule::insert("Chapters", $request);
            $this->generatePdf($insert_id);
            $chapter = Chapter::find($insert_id);

            // Add LALog
            LALog::make("Chapters.CHAPTER_CREATED", [
                'title' => "Chapter Created",
                'module_id' => 'Chapters',
                'context_id' => $chapter->id,
                'content' => $chapter,
                'user_id' => Auth::user()->id,
                'notify_to' => "[]"
            ]);

            if($request->ajax() || isset($request->quick_add)) {
                return response()->json([
                    'status' => 'success',
                    'object' => $chapter,
                    'message' => 'Chapter updated successfully!',
                    'redirect' => url(config('laraadmin.adminRoute') . '/chapters')
                ], 201);
            } else {
                return redirect()->route(config('laraadmin.adminRoute') . '.chapters.index');
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
     * Display the specified chapter.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id chapter ID
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        if(LAModule::hasAccess("Chapters", "view")) {

            $chapter = Chapter::find($id);
            if(isset($chapter->id)) {
                if($request->ajax() && !isset($request->_pjax)) {
                    return $chapter;
                } else {
                    $module = LAModule::get('Chapters');
                    $module->row = $chapter;

                    return view('la.chapters.show', [
                        'module' => $module,
                        'view_col' => $module->view_col,
                        'no_header' => true,
                        'no_padding' => "no-padding"
                    ])->with('chapter', $chapter);
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
                        'record_name' => ucfirst("chapter"),
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
     * Show the form for editing the specified chapter.
     *
     * @param int $id chapter ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(LAModule::hasAccess("Chapters", "edit")) {
            $chapter = Chapter::find($id);
            if(isset($chapter->id)) {
                $module = LAModule::get('Chapters');

                $module->row = $chapter;

                return view('la.chapters.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('chapter', $chapter);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("chapter"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * Update the specified chapter in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id chapter ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(LAModule::hasAccess("Chapters", "edit")) {
            if($request->ajax()) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Chapters", $request, true);

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

            $chapter_old = Chapter::find($id);

            if(isset($chapter_old->id)) {

                // Update Data
                LAModule::updateRow("Chapters", $request, $id);

                $chapter_new = Chapter::find($id);

                // Add LALog
                LALog::make("Chapters.CHAPTER_UPDATED", [
                    'title' => "Chapter Updated",
                    'module_id' => 'Chapters',
                    'context_id' => $chapter_new->id,
                    'content' => [
                        'old' => $chapter_old,
                        'new' => $chapter_new
                    ],
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'object' => $chapter_new,
                        'message' => 'Chapter updated successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/chapters')
                    ], 200);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.chapters.index');
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
                        'record_name' => ucfirst("chapter"),
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
     * Remove the specified chapter from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id chapter ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        if(LAModule::hasAccess("Chapters", "delete")) {

            $chapter = Chapter::find($id);
            if(isset($chapter->id)) {
                $chapter->delete();

                // Add LALog
                LALog::make("Chapters.CHAPTER_DELETED", [
                    'title' => "Chapter Deleted",
                    'module_id' => 'Chapters',
                    'context_id' => $chapter->id,
                    'content' => $chapter,
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Record Deleted successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/chapters')
                    ], 204);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.chapters.index');
                }
            } else {
                if($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Record not found'
                    ], 404);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.chapters.index');
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
        $module = LAModule::get('Chapters');
        $listing_cols = LAModule::getListingColumns('Chapters');

        $values = DB::table('chapters')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();

        $fields_popup = LAModuleField::getModuleFields('Chapters');

        for($i = 0; $i < count($data->data); $i++) {

            $chapter = Chapter::find($data->data[$i]->id);

            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if(isset($fields_popup[$col]) && str_starts_with($fields_popup[$col]->popup_vals, "@")) {
                    if($col == $module->view_col) {
                        $data->data[$i]->$col = LAModuleField::getFieldValue($fields_popup[$col], $data->data[$i]->$col);
                    } else {
                        $data->data[$i]->$col = LAModuleField::getFieldLink($fields_popup[$col], $data->data[$i]->$col);
                    }
                }
                if($col == $module->view_col) {
                    $data->data[$i]->$col = '<a '.config('laraadmin.ajaxload').' href="' . url(config('laraadmin.adminRoute') . '/chapters/' . $data->data[$i]->id) . '">' . $data->data[$i]->$col . '</a>';
                }
                if($col == 'image' && !empty($data->data[$i]->$col)) {
					$img = Upload::find($data->data[$i]->$col);
					$data->data[$i]->$col = '<img src="'.$img->url().'" width="70" height="70">';
				}	

				
                // else if($col == "author") {
                //    $data->data[$i]->$col;
                // }
            }

            if($this->show_action) {
                $output = '';
                if(LAModule::hasAccess("Chapters", "edit")) {
                    $output .= '<a '.config('laraadmin.ajaxload').' href="' . url(config('laraadmin.adminRoute') . '/chapters/' . $data->data[$i]->id . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>';
                }

                if(LAModule::hasAccess("Chapters", "delete")) {
                    $array = explode(">", (string)$data->data[$i]->name);
                    $array2 = explode("<", $array[1]);
                    $tit = $array2[0];
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.chapters.destroy', $data->data[$i]->id], 'method' => 'delete', 'style' => 'display:inline']);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit" onclick="return confirm(\'¿Quieres eliminar el capítulo '.$tit.'?\')" data-toggle="tooltip" title="Delete"><i class="fa fa-times"></i></button>';
                    $output .= Form::close();
                }

                $output .= '<a href="'.url(config('laraadmin.adminRoute') . '/chapters/'.$data->data[$i]->id.'/download-pdf').'" class="btn btn-primary btn-xs" style="display:inline;padding:2px 5px 3px 5px; margin-left: 3px;"><i class="fa fa-file"></i></a>';
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
		$path = '/'.str_pad($request->get('chapter_id'), 2, "0", STR_PAD_LEFT).'-'.$this->folders[$request->get('chapter_id')]['value'].'/media/'.$cover->getFilename().'.'.$extension;
		if(!Storage::disk('public_uploads')->put($path, File::get($cover))) {
		    return false;
		}

		return '/images/book'.$path;
	}

    public function downloadPdf($id){
		$chapter = Chapter::find($id);

		$folder_specialty = str_pad($chapter->specialty_id, 2, '0', STR_PAD_LEFT).'-'.$this->folders[$chapter->specialty_id]['value'].'/';

		$path = storage_path('pdfs/'.$folder_specialty.str_replace('-', '_', $chapter->slug).'.pdf');

		if(file_exists($path) == false){
			$this->generatePdf($id);
		}
		return response()->file($path, ['Cache-Control' => 'no-store,no-cache, must-revalidate, post-check=0, pre-check=0', 'Pragma' => 'no-cache', 'Content-Disposition' => 'inline; filename="'. $chapter->slug .'.pdf"']);
	}

    public function renderChapter($id, $tipo = 'html'){
        $chapter = Chapter::find($id);
        
        if($tipo == 'pdf'){
        	return View('la.chapters.pdf', ['content' => $chapter->body, 'title' => $chapter->name, 'video' => $chapter->video]);	
        }

        return View('la.chapters.html', ['content' => $chapter->body, 'title' => $chapter->name, 'video' => $chapter->video]);
    }

    public function generatePdf($id){
        $chapter = Chapter::find($id);

        $folder_specialty = str_pad($chapter->specialty_id, 2, '0', STR_PAD_LEFT).'-'.$this->folders[$chapter->specialty_id]['value'].'/';
        
        $folderOut = storage_path('pdfs/'.$folder_specialty.str_replace('-', '_', trim($chapter->slug)).'.pdf');

        $specialty = $this->folders[$chapter->specialty_id]['name'];

        $locale = 'es_ES.utf-8';
        setlocale(LC_ALL, $locale);
        putenv('LC_ALL='.$locale);

  		//echo 'sudo /usr/bin/wkhtmltopdf -B 18 -L 0 -R 0 -T 20 --encoding utf-8 --header-spacing 4 --header-html http://panel.medicodeurgencia.com/admin/chapters/render-chapter-header --footer-html http://panel.medicodeurgencia.com/admin/chapters/render-chapter-footer --page-offset 0 --footer-spacing 2 --image-dpi 300 --no-outline --replace "color" "0" --replace "specialty" "'.$specialty.'" http://panel.medicodeurgencia.com/admin/chapters/render-chapter/'.$id.'/pdf '.$folderOut;die;
        exec('wkhtmltopdf -B 18 -L 0 -R 0 -T 20 --encoding utf-8 --header-spacing 4 --header-html https://panel.medicodeurgencia.com/admin/chapters/render-chapter-header --footer-html https://panel.medicodeurgencia.com/admin/chapters/render-chapter-footer --page-offset 0 --footer-spacing 2 --image-dpi 300 --no-outline --replace "color" "0" --replace "specialty" "'.$specialty.'" https://panel.medicodeurgencia.com/admin/chapters/render-chapter/'.$id.'/pdf "'.$folderOut. '" 2>&1');
     //   exec('wkhtmltopdf -B 18 -L 0 -R 0 -T 20 --encoding utf-8 --header-spacing 4 --header-html http://localhost:81/laraadmin-master/public/admin/chapters/render-chapter-header --footer-html http://localhost:81/laraadmin-master/public/admin/chapters/render-chapter-footer --page-offset 0 --footer-spacing 2 --image-dpi 300 --no-outline --replace "color" "0" --replace "specialty" "'.$specialty.'" http://localhost:81/laraadmin-master/public/admin/chapters/render-chapter/'.$id.'/pdf "'.$folderOut. '" 2>&1');
    }
}
