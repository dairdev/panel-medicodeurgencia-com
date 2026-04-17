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

use App\Models\Lectore;
use App\Models\Codigo;
use Mail;

class LectoresController extends Controller
{
    public $show_action = true;
    public $view_col = 'namo';
	public $listing_cols = ['id', 'colegiado', 'namo', 'surname', 'email', 'phone', 'notas'];

    /**
     * Display a listing of the Lectores.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $module = LAModule::get('Lectores');

        if(LAModule::hasAccess($module->id)) {
            if($request->ajax() && !isset($request->_pjax)) {
                // TODO: Implement good Query Builder
                return Lectore::all();
            } else {
                return View('la.lectores.index', [
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
     * Show the form for creating a new lectore.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created lectore in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        if(LAModule::hasAccess("Lectores", "create")) {
            if($request->ajax() && !isset($request->quick_add)) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Lectores", $request);

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

            $empresa = DB::select(DB::raw("SELECT * FROM employees WHERE id = :iduser"), array('iduser' => Auth::user()->context_id));
			$codigos = DB::select(DB::raw("SELECT count(*) as total FROM codigos WHERE codigos.user_id = :iduser"), array('iduser' => Auth::user()->id));

            if($empresa[0]->limitcode > 0){
				if($codigos[0]->total >= $empresa[0]->limitcode){
					return redirect()->back()->withErrors(['Excedido limite de codigos. No puedes crear nuevos lectores. Contacta con administración'])->withInput();
				}
			}
			
			if(empty($request->get('colegiado'))){
				$request->request->add(['colegiado' => strtoupper(uniqid())]);
			}

            $insert_id = LAModule::insert("Lectores", $request);

            $lectore = Lectore::find($insert_id);

            $lector = Lectore::find($insert_id);
			$lector->user_id = Auth::user()->id;
			if($lector->save()){
				$codigo = new Codigo;
				$codigo->codigo = strtoupper(uniqid());
				$codigo->date_compra = date('Y-m-d');

				if(Auth::user()->roles[0]->id == 3){
					$codigo->type = $empresa[0]->type;
					$codigo->renove = $empresa[0]->renove;
				}else{
					$codigo->type = $request->type;
					$codigo->renove = 0;
				}

				$codigo->lectore_id = $lector->id;
				$codigo->user_id = Auth::user()->id;

				if($empresa[0]->renove){
					$date_validez = new \DateTime(date('Y-m-d') . ' + ' . $request->type . ' days');
					$codigo->date_validez = $date_validez->format('Y-m-d');
				}

				$codigo->save(); 
			}

			//Email empresa
			$to_name = $lector->namo.' '.$lector->surname;
			$to_email = $lector->email;
			$data = array('codigo' => $codigo, 'lector' => $lector);

			Mail::send('emails.lector', $data, function($message) use ($to_name, $to_email) {
				$message->to($to_email, $to_name)->subject('Nueva cuenta creada en MedicoDeUrgencias');
				$message->from(env('MAIL_USERNAME'),'Médico de Urgencias');
			});

            // Add LALog
            LALog::make("Lectores.LECTORE_CREATED", [
                'title' => "Lectore Created",
                'module_id' => 'Lectores',
                'context_id' => $lectore->id,
                'content' => $lectore,
                'user_id' => Auth::user()->id,
                'notify_to' => "[]"
            ]);

            if($request->ajax() || isset($request->quick_add)) {
                return response()->json([
                    'status' => 'success',
                    'object' => $lectore,
                    'message' => 'Lectore updated successfully!',
                    'redirect' => url(config('laraadmin.adminRoute') . '/lectores')
                ], 201);
            } else {
                return redirect()->route(config('laraadmin.adminRoute') . '.lectores.index');
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
     * Display the specified lectore.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id lectore ID
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        if(LAModule::hasAccess("Lectores", "view")) {

            $lectore = Lectore::find($id);
            if(isset($lectore->id)) {
                if($request->ajax() && !isset($request->_pjax)) {
                    return $lectore;
                } else {
                    $module = LAModule::get('Lectores');
                    $module->row = $lectore;

                    return view('la.lectores.show', [
                        'module' => $module,
                        'view_col' => $module->view_col,
                        'no_header' => true,
                        'no_padding' => "no-padding"
                    ])->with('lectore', $lectore);
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
                        'record_name' => ucfirst("lectore"),
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
     * Show the form for editing the specified lectore.
     *
     * @param int $id lectore ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(LAModule::hasAccess("Lectores", "edit")) {
            if(Auth::user()->roles[0]->id == 3){
				$lectore = Lectore::where('id', $id)->where('user_id', Auth::user()->id)->first();
			}else{
				$lectore = Lectore::find($id);
			}		
            if(isset($lectore->id)) {
                $module = LAModule::get('Lectores');

                $module->row = $lectore;

                return view('la.lectores.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('lectore', $lectore);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("lectore"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * Update the specified lectore in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id lectore ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(LAModule::hasAccess("Lectores", "edit")) {
            if($request->ajax()) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Lectores", $request, true);

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

            $lectore_old = Lectore::find($id);

            if(isset($lectore_old->id)) {

                // Update Data
                LAModule::updateRow("Lectores", $request, $id);

                $lectore_new = Lectore::find($id);

                // Add LALog
                LALog::make("Lectores.LECTORE_UPDATED", [
                    'title' => "Lectore Updated",
                    'module_id' => 'Lectores',
                    'context_id' => $lectore_new->id,
                    'content' => [
                        'old' => $lectore_old,
                        'new' => $lectore_new
                    ],
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'object' => $lectore_new,
                        'message' => 'Lectore updated successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/lectores')
                    ], 200);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.lectores.index');
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
                        'record_name' => ucfirst("lectore"),
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
     * Remove the specified lectore from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id lectore ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        if(LAModule::hasAccess("Lectores", "delete")) {

            $lectore = Lectore::find($id);
            if(isset($lectore->id)) {
                $lectore->delete();

                // Add LALog
                LALog::make("Lectores.LECTORE_DELETED", [
                    'title' => "Lectore Deleted",
                    'module_id' => 'Lectores',
                    'context_id' => $lectore->id,
                    'content' => $lectore,
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Record Deleted successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/lectores')
                    ], 204);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.lectores.index');
                }
            } else {
                if($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Record not found'
                    ], 404);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.lectores.index');
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
        $module = LAModule::get('Lectores');
        $listing_cols = LAModule::getListingColumns('Lectores');

        if(Auth::user()->roles[0]->id == 3){
			$values = DB::table('lectores')->select($this->listing_cols)->where('user_id', Auth::user()->id)->whereNull('deleted_at');
		}else{
			$values = DB::table('lectores')->select($this->listing_cols)->whereNull('deleted_at');	
		}
        $out = Datatables::of($values)->make();
        $data = $out->getData();

        $fields_popup = LAModuleField::getModuleFields('Lectores');

        for($i = 0; $i < count($data->data); $i++) {

            $lectore = Lectore::find($data->data[$i]->id);

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
                    $data->data[$i]->$col = '<a '.config('laraadmin.ajaxload').' href="' . url(config('laraadmin.adminRoute') . '/lectores/' . $data->data[$i]->id) . '">' . $data->data[$i]->$col . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i]->$col;
                // }
            }

            if($this->show_action) {
                $output = '';
                if(LAModule::hasAccess("Lectores", "edit")) {
                    $output .= '<a '.config('laraadmin.ajaxload').' href="' . url(config('laraadmin.adminRoute') . '/lectores/' . $data->data[$i]->id . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>';
                }

                if(LAModule::hasAccess("Lectores", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.lectores.destroy', $data->data[$i]->id], 'method' => 'delete', 'style' => 'display:inline']);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit" data-toggle="tooltip" title="Delete"><i class="fa fa-times"></i></button>';
                    $output .= Form::close();
                }
                $data->data[$i]->dt_action = (string)$output;
            }
        }
        $out->setData($data);
        return $out;
    }
}
