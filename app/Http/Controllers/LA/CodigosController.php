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
use Mail;

use App\Models\Codigo;
use App\Models\Lectore;

class CodigosController extends Controller
{
    public $show_action = true;
    public $view_col = 'codigo';
	public $listing_cols = ['id', 'codigo', 'date_compra', 'type', 'date_validez', 'lectore_id'];

    /**
     * Display a listing of the Codigos.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $module = LAModule::get('Codigos');

        if(LAModule::hasAccess($module->id)) {
            if($request->ajax() && !isset($request->_pjax)) {
                // TODO: Implement good Query Builder
                return Codigo::all();
            } else {
                return View('la.codigos.index', [
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
     * Show the form for creating a new codigo.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created codigo in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        if(LAModule::hasAccess("Codigos", "create")) {

            $rules = LAModule::validateRules("Codigos", $request);

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

            //Miramos el limite de codigos y vemos si le quedan libres
			$empresa = DB::select(DB::raw("SELECT * FROM employees WHERE id = :iduser"), array('iduser' => Auth::user()->context_id));
			$codigos = DB::select(DB::raw("SELECT count(*) as total FROM codigos WHERE codigos.user_id = :iduser"), array('iduser' => Auth::user()->id));

			if($empresa[0]->limitcode > 0){
				if($codigos[0]->total >= $empresa[0]->limitcode){
					return redirect()->back()->withErrors(['Excedido limite de codigos. No puedes crear nuevos. Contacta con administración'])->withInput();
				}
			}

            $request->request->add([
				'codigo' => strtoupper(uniqid()),
				'user_id' => Auth::user()->id,
			]);

            if(auth()->user()->roles[0]->id == 3){
				$request->request->add([
					'date_compra' => date('d/m/Y'),
					'type' => $empresa[0]->type,
					'renove' => $empresa[0]->renove
				]);
			}else{
				$request->request->add(['renove' => 0]);
			}

            if($empresa[0]->renove){
				$date_validez = new \DateTime(date('Y-m-d') . ' + ' . $request->type . ' days');
				$request->request->add([
					'date_validez' => $date_validez->format('d/m/Y')
				]);
			}

            $insert_id = LAModule::insert("Codigos", $request);

            $codigo = Codigo::find($insert_id);

            // Add LALog
            LALog::make("Codigos.CODIGO_CREATED", [
                'title' => "Codigo Created",
                'module_id' => 'Codigos',
                'context_id' => $codigo->id,
                'content' => $codigo,
                'user_id' => Auth::user()->id,
                'notify_to' => "[]"
            ]);

            if($request->ajax() || isset($request->quick_add)) {
                return response()->json([
                    'status' => 'success',
                    'object' => $codigo,
                    'message' => 'Codigo updated successfully!',
                    'redirect' => url(config('laraadmin.adminRoute') . '/codigos')
                ], 201);
            } else {
                return redirect()->route(config('laraadmin.adminRoute') . '.codigos.index');
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
     * Display the specified codigo.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id codigo ID
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        if(LAModule::hasAccess("Codigos", "view")) {

            if(Auth::user()->roles[0]->id == 3){
				$codigo = Codigo::where('id', $id)->where('user_id', Auth::user()->id)->first();
			}else{
				$codigo = Codigo::find($id);
			}
            if(isset($codigo->id)) {
                if($request->ajax() && !isset($request->_pjax)) {
                    return $codigo;
                } else {
                    $module = LAModule::get('Codigos');
                    $module->row = $codigo;

                    return view('la.codigos.show', [
                        'module' => $module,
                        'view_col' => $this->view_col,
                        'no_header' => true,
                        'no_padding' => "no-padding"
                    ])->with('codigo', $codigo);
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
                        'record_name' => ucfirst("codigo"),
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
     * Show the form for editing the specified codigo.
     *
     * @param int $id codigo ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(LAModule::hasAccess("Codigos", "edit")) {
            if(Auth::user()->roles[0]->id == 3){
				$codigo = Codigo::where('id', $id)->where('user_id', Auth::user()->id)->first();
			}else{
				$codigo = Codigo::find($id);
			}	
            if(isset($codigo->id)) {
                $module = LAModule::get('Codigos');

                $module->row = $codigo;

                return view('la.codigos.edit', [
                    'module' => $module,
                    'view_col' => $this->view_col,
                ])->with('codigo', $codigo);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("codigo"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * Update the specified codigo in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id codigo ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(LAModule::hasAccess("Codigos", "edit")) {

            if(Auth::user()->roles[0]->id == 3){
				$codigo = Codigo::where('id', $id)->where('user_id', Auth::user()->id)->first();
			}else{
				$codigo = Codigo::find($id);
			}	

            if(!isset($codigo->id)) {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("codigo"),
				]);
			}

            if($request->ajax()) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Codigos", $request, true);

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

            $insert_id = LAModule::updateRow("Codigos", $request, $id);
			$codigo = Codigo::find($insert_id);
			if(!empty($codigo->date_validez)){
				$days = $request->get('type');
				$codigo->date_validez = date("Y-m-d", strtotime($codigo->date_compra.' + '.$days.' days'));
				$codigo->save();
			}
            LALog::make("Codigos.CODIGO_UPDATED", [
                'title' => "Codigo Updated",
                'module_id' => 'Codigos',
                'context_id' => $codigo->id,
                'content' => [
                    'old' => $codigo,
                    'new' => $codigo
                ],
                'user_id' => Auth::user()->id,
                'notify_to' => "[]"
            ]);

            if($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'object' => $codigo,
                    'message' => 'Codigo updated successfully!',
                    'redirect' => url(config('laraadmin.adminRoute') . '/codigos')
                ], 200);
            } else {
                return redirect()->route(config('laraadmin.adminRoute') . '.codigos.index');
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
     * Remove the specified codigo from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id codigo ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        if(LAModule::hasAccess("Codigos", "delete")) {

            $codigo = Codigo::find($id);
            if(isset($codigo->id)) {
                $codigo->delete();

                // Add LALog
                LALog::make("Codigos.CODIGO_DELETED", [
                    'title' => "Codigo Deleted",
                    'module_id' => 'Codigos',
                    'context_id' => $codigo->id,
                    'content' => $codigo,
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Record Deleted successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/codigos')
                    ], 204);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.codigos.index');
                }
            } else {
                if($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Record not found'
                    ], 404);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.codigos.index');
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
    // Query con JOIN para poder buscar por Código, Nombre y Email del lector
    $values = DB::table('codigos')
        ->leftJoin('lectores', 'lectores.id', '=', 'codigos.lectore_id')
        ->whereNull('codigos.deleted_at')
        ->select([
            DB::raw('codigos.id as id'), // Mantener "id" como el de codigos (LaraAdmin/DataTables)
            'codigos.codigo',
            'codigos.date_compra',
            'codigos.type',
            'codigos.date_validez',
            'codigos.lectore_id',
            DB::raw("CONCAT(COALESCE(lectores.namo,''),' ',COALESCE(lectores.surname,'')) as NombreLector"),
            DB::raw("lectores.email as LectorEmail"),
            DB::raw("lectores.id as LectorId"),
        ]);

    // Restricción por rol (tu lógica actual)
    if (Auth::user()->roles[0]->id == 3) {
        $values->where('codigos.user_id', Auth::user()->id);
    }

    return Datatables::of($values)

        // Evita ORDER BY id ambiguo (porque existe id en codigos y lectores)
        ->orderColumn('id', function ($query, $order) {
            $query->orderBy('codigos.id', $order);
        })

        // Search global: Código + Nombre Lector + Email
        ->filter(function ($query) use ($request) {
            $search = $request->input('search.value');
            if (!$search) return;

            $query->where(function ($q) use ($search) {
                $q->orWhere('codigos.codigo', 'like', "%{$search}%")
                  ->orWhere('codigos.id', 'like', "%{$search}%")
                  ->orWhere('lectores.email', 'like', "%{$search}%")
                  ->orWhereRaw(
                      "CONCAT(COALESCE(lectores.namo,''),' ',COALESCE(lectores.surname,'')) LIKE ?",
                      ["%{$search}%"]
                  );
            });
        })

        // Link en la columna principal (view_col = 'codigo')
        ->editColumn('codigo', function ($row) {
            return '<a '.config('laraadmin.ajaxload').' href="' .
                url(config('laraadmin.adminRoute') . '/codigos/' . $row->id) .
                '">' . e($row->codigo) . '</a>';
        })

        // Columna Estado (HTML)
        ->addColumn('Estado', function ($row) {
            if (empty($row->date_validez)) return '<span class="gris"></span>';
            if ($row->date_validez < date('Y-m-d')) return '<span class="rojo"></span>';
            return '<span class="verde"></span>';
        })

        // Columna Nombre Lector (ya viene del SELECT, pero la normalizamos)
        ->editColumn('NombreLector', function ($row) {
            return $row->NombreLector ?? '';
        })

        // Acciones (edit/delete/email) sin N+1 queries
        ->addColumn('dt_action', function ($row) {
            $output = '';

            if (LAModule::hasAccess("Codigos", "edit")) {
                $output .= '<a '.config('laraadmin.ajaxload').' href="' .
                    url(config('laraadmin.adminRoute') . '/codigos/' . $row->id . '/edit') .
                    '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="tooltip" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>';
            }

            if (LAModule::hasAccess("Codigos", "delete")) {
                $output .= Form::open([
                    'route' => [config('laraadmin.adminRoute') . '.codigos.destroy', $row->id],
                    'method' => 'delete',
                    'style' => 'display:inline'
                ]);
                $output .= ' <button class="btn btn-danger btn-xs" type="submit" data-toggle="tooltip" title="Delete">
                                <i class="fa fa-times"></i>
                            </button>';
                $output .= Form::close();
            }

            // Botón email (modal)
            $output .= ' <a href="#" data-code="'.$row->id.'"
                            data-lectore="'.$row->LectorEmail.'"
                            data-email="'.$row->LectorEmail.'"
                            data-toggle="modal" data-target="#SendModal"
                            class="btn btn-info btn-xs" style="display:inline;padding:2px 5px 3px 5px;"
                            title="Email">
                            <i class="fa fa-envelope"></i>
                        </a>';

            return (string) $output;
        })

        // Permite HTML
        ->rawColumns(['codigo', 'Estado', 'dt_action'])

        ->make(true);
}
	
	
	
    public function email(Request $request)
	{
		$code = Codigo::where(['id' => $request->get('code_id')])->first();
		$lector = Lectore::where(['id' => $request->get('lectore_id2')])->first();

		$to_name = $lector->namo.' '.$lector->surname;
		$to_email = $request->get('email');
		$texto = $request->get('texto_code');
		$data = array('code' => $code, 'lector' => $lector, 'texto_code' => $texto);

		Mail::send('emails.code', $data, function($message) use ($to_name, $to_email) {
			$message->to($to_email, $to_name)->subject('Reenvio de código de Médico de Urgencias');
			$message->from('envios@medicodeurgencia.com','Médico de Urgencias');
		});

		echo json_encode(['result' => true]);
	}
}
