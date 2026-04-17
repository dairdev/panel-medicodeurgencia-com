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

use App\Models\Especialidade;

class EspecialidadesController extends Controller
{
    public $show_action = true;

    /**
     * Display a listing of the Especialidades.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $module = LAModule::get('Especialidades');

        if(LAModule::hasAccess($module->id)) {
            if($request->ajax() && !isset($request->_pjax)) {
                // TODO: Implement good Query Builder
                return Especialidade::all();
            } else {
                return View('la.especialidades.index', [
                    'show_actions' => $this->show_action,
                    'listing_cols' => LAModule::getListingColumns('Especialidades'),
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
     * Show the form for creating a new especialidade.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created especialidade in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        if(LAModule::hasAccess("Especialidades", "create")) {
            if($request->ajax() && !isset($request->quick_add)) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Especialidades", $request);

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

            $insert_id = LAModule::insert("Especialidades", $request);

            $especialidade = Especialidade::find($insert_id);

            // Add LALog
            LALog::make("Especialidades.ESPECIALIDADE_CREATED", [
                'title' => "Especialidade Created",
                'module_id' => 'Especialidades',
                'context_id' => $especialidade->id,
                'content' => $especialidade,
                'user_id' => Auth::user()->id,
                'notify_to' => "[]"
            ]);

            if($request->ajax() || isset($request->quick_add)) {
                return response()->json([
                    'status' => 'success',
                    'object' => $especialidade,
                    'message' => 'Especialidade updated successfully!',
                    'redirect' => url(config('laraadmin.adminRoute') . '/especialidades')
                ], 201);
            } else {
                return redirect()->route(config('laraadmin.adminRoute') . '.especialidades.index');
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
     * Display the specified especialidade.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id especialidade ID
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        if(LAModule::hasAccess("Especialidades", "view")) {

            $especialidade = Especialidade::find($id);
            if(isset($especialidade->id)) {
                if($request->ajax() && !isset($request->_pjax)) {
                    return $especialidade;
                } else {
                    $module = LAModule::get('Especialidades');
                    $module->row = $especialidade;

                    return view('la.especialidades.show', [
                        'module' => $module,
                        'view_col' => $module->view_col,
                        'no_header' => true,
                        'no_padding' => "no-padding"
                    ])->with('especialidade', $especialidade);
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
                        'record_name' => ucfirst("especialidade"),
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
     * Show the form for editing the specified especialidade.
     *
     * @param int $id especialidade ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(LAModule::hasAccess("Especialidades", "edit")) {
            $especialidade = Especialidade::find($id);
            if(isset($especialidade->id)) {
                $module = LAModule::get('Especialidades');

                $module->row = $especialidade;

                return view('la.especialidades.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('especialidade', $especialidade);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("especialidade"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * Update the specified especialidade in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id especialidade ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(LAModule::hasAccess("Especialidades", "edit")) {
            if($request->ajax()) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Especialidades", $request, true);

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

            $especialidade_old = Especialidade::find($id);

            if(isset($especialidade_old->id)) {

                // Update Data
                LAModule::updateRow("Especialidades", $request, $id);

                $especialidade_new = Especialidade::find($id);

                // Add LALog
                LALog::make("Especialidades.ESPECIALIDADE_UPDATED", [
                    'title' => "Especialidade Updated",
                    'module_id' => 'Especialidades',
                    'context_id' => $especialidade_new->id,
                    'content' => [
                        'old' => $especialidade_old,
                        'new' => $especialidade_new
                    ],
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'object' => $especialidade_new,
                        'message' => 'Especialidade updated successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/especialidades')
                    ], 200);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.especialidades.index');
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
                        'record_name' => ucfirst("especialidade"),
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
     * Remove the specified especialidade from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id especialidade ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        if(LAModule::hasAccess("Especialidades", "delete")) {

            $especialidade = Especialidade::find($id);
            if(isset($especialidade->id)) {
                $especialidade->delete();

                // Add LALog
                LALog::make("Especialidades.ESPECIALIDADE_DELETED", [
                    'title' => "Especialidade Deleted",
                    'module_id' => 'Especialidades',
                    'context_id' => $especialidade->id,
                    'content' => $especialidade,
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Record Deleted successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/especialidades')
                    ], 204);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.especialidades.index');
                }
            } else {
                if($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Record not found'
                    ], 404);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.especialidades.index');
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
        $module = LAModule::get('Especialidades');
        $listing_cols = LAModule::getListingColumns('Especialidades');

        $values = DB::table('especialidades')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();

        $fields_popup = LAModuleField::getModuleFields('Especialidades');

        for($i = 0; $i < count($data->data); $i++) {

            $especialidade = Especialidade::find($data->data[$i]->id);

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
                    $data->data[$i]->$col = '<a '.config('laraadmin.ajaxload').' href="' . url(config('laraadmin.adminRoute') . '/especialidades/' . $data->data[$i]->id) . '">' . $data->data[$i]->$col . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i]->$col;
                // }
            }

            if($this->show_action) {
                $output = '';
                if(LAModule::hasAccess("Especialidades", "edit")) {
                    $output .= '<a '.config('laraadmin.ajaxload').' href="' . url(config('laraadmin.adminRoute') . '/especialidades/' . $data->data[$i]->id . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>';
                }

                if(LAModule::hasAccess("Especialidades", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.especialidades.destroy', $data->data[$i]->id], 'method' => 'delete', 'style' => 'display:inline']);
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
