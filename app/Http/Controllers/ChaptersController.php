<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use DB;
use Validator;
use Datatables;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;
use Storage;
use File;

use App\Models\Chapter;
use App\Models\Subchapter;
use App\Models\Upload;

class ChaptersController extends Controller
{
	public $show_action = true;
	public $view_col = 'name';
	public $listing_cols = ['id', 'name', 'image', 'orden', 'specialty_id'];

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
	
	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Chapters', $this->listing_cols);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('Chapters', $this->listing_cols);
		}
	}
	
	/**
	 * Display a listing of the Chapters.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Chapters');
		
		if(Module::hasAccess($module->id)) {
			return View('la.chapters.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for creating a new chapter.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created chapter in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Chapters", "create")) {
		
			$rules = Module::validateRules("Chapters", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			$request->request->add(['slug' => str_replace(' ','-', $request->get('name')).'.docx']);
			$insert_id = Module::insert("Chapters", $request);
			$this->generatePdf($insert_id);
			return redirect()->route(config('laraadmin.adminRoute') . '.chapters.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified chapter.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Chapters", "view")) {
			
			$chapter = Chapter::find($id);
			if(isset($chapter->id)) {
				$module = Module::get('Chapters');
				$module->row = $chapter;
				
				return view('la.chapters.show', [
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding",
					'subchapters' => Subchapter::where(['chapter_id' => $chapter->id])->orderBy('orden', 'desc')->get()
				])->with('chapter', $chapter);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("chapter"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Show the form for editing the specified chapter.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Chapters", "edit")) {			
			$chapter = Chapter::find($id);
			if(isset($chapter->id)) {	
				$module = Module::get('Chapters');
				
				$module->row = $chapter;
				
				return view('la.chapters.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
				])->with('chapter', $chapter);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("chapter"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified chapter in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Chapters", "edit")) {
			
			$rules = Module::validateRules("Chapters", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			
			$insert_id = Module::updateRow("Chapters", $request, $id);
			$this->generatePdf($insert_id);
			return redirect()->route(config('laraadmin.adminRoute') . '.chapters.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified chapter from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Chapters", "delete")) {
			Chapter::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.chapters.index');
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}
	
	/**
	 * Datatable Ajax fetch
	 *
	 * @return
	 */
	public function dtajax()
	{
		$values = DB::table('chapters')->select($this->listing_cols)->whereNull('deleted_at');
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Chapters');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/chapters/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}

				if($col == 'image' && !empty($data->data[$i][$j])) {
					$img = Upload::find($data->data[$i][$j]);
					$data->data[$i][$j] = '<img src="'.$img->url().'?s=70">';
				}	
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$output = '';
				if(Module::hasAccess("Chapters", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/chapters/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Chapters", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.chapters.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
					$output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
					$output .= Form::close();
				}

				$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/chapters/'.$data->data[$i][0].'/download-pdf').'" class="btn btn-primary btn-xs" style="display:inline;padding:2px 5px 3px 5px; margin-left: 3px;"><i class="fa fa-file"></i></a>';

				$data->data[$i][] = (string)$output;
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

		return response()->file($path, ['Content-Disposition' => 'inline; filename="'. $chapter->slug .'.pdf"']);
	}

    public function renderChapter($id, $tipo = 'html'){
        $chapter = Chapter::find($id);
        
        if($tipo == 'pdf'){
        	return View('la.chapters.pdf', ['content' => $chapter->body, 'title' => $chapter->name]);	
        }

        return View('la.chapters.html', ['content' => $chapter->body, 'title' => $chapter->name]);
    }

    public function generatePdf($id){
        $chapter = Chapter::find($id);

        $folder_specialty = str_pad($chapter->specialty_id, 2, '0', STR_PAD_LEFT).'-'.$this->folders[$chapter->specialty_id]['value'].'/';

        $folderOut = storage_path('pdfs/'.$folder_specialty.str_replace('-', '_', $chapter->slug).'.pdf');

        $specialty = $this->folders[$chapter->specialty_id]['name'];

        $locale = 'es_ES.utf-8';
        setlocale(LC_ALL, $locale);
        putenv('LC_ALL='.$locale);

  	//echo 'sudo /usr/bin/wkhtmltopdf -B 18 -L 0 -R 0 -T 20 --encoding utf-8 --header-spacing 4 --header-html http://panel.medicodeurgencia.com/admin/chapters/render-chapter-header --footer-html http://panel.medicodeurgencia.com/admin/chapters/render-chapter-footer --page-offset 0 --footer-spacing 2 --image-dpi 300 --no-outline --replace "color" "0" --replace "specialty" "'.$specialty.'" http://panel.medicodeurgencia.com/admin/chapters/render-chapter/'.$id.'/pdf '.$folderOut;die;
        exec('wkhtmltopdf -B 18 -L 0 -R 0 -T 20 --encoding utf-8 --header-spacing 4 --header-html https://panel.medicodeurgencia.com/admin/chapters/render-chapter-header --footer-html https://panel.medicodeurgencia.com/admin/chapters/render-chapter-footer --page-offset 0 --footer-spacing 2 --image-dpi 300 --no-outline --replace "color" "0" --replace "specialty" "'.$specialty.'" https://panel.medicodeurgencia.com/admin/chapters/render-chapter/'.$id.'/pdf "'.$folderOut. '" 2>&1');
    }
}
