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

use App\Models\Pagina;

class PaginasController extends Controller
{
    public $show_action = true;

    /**
     * Display a listing of the Paginas.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $module = LAModule::get('Paginas');

        if(LAModule::hasAccess($module->id)) {
            if($request->ajax() && !isset($request->_pjax)) {
                // TODO: Implement good Query Builder
                return Pagina::all();
            } else {
                return View('la.paginas.index', [
                    'show_actions' => $this->show_action,
                    'listing_cols' => LAModule::getListingColumns('Paginas'),
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
     * Show the form for creating a new pagina.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created pagina in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        if(LAModule::hasAccess("Paginas", "create")) {
            if($request->ajax() && !isset($request->quick_add)) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Paginas", $request);

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

            $insert_id = LAModule::insert("Paginas", $request);

            $pagina = Pagina::find($insert_id);

            // Add LALog
            LALog::make("Paginas.PAGINA_CREATED", [
                'title' => "Pagina Created",
                'module_id' => 'Paginas',
                'context_id' => $pagina->id,
                'content' => $pagina,
                'user_id' => Auth::user()->id,
                'notify_to' => "[]"
            ]);

            if($request->ajax() || isset($request->quick_add)) {
                return response()->json([
                    'status' => 'success',
                    'object' => $pagina,
                    'message' => 'Pagina updated successfully!',
                    'redirect' => url(config('laraadmin.adminRoute') . '/paginas')
                ], 201);
            } else {
                return redirect()->route(config('laraadmin.adminRoute') . '.paginas.index');
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
     * Display the specified pagina.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id pagina ID
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        if(LAModule::hasAccess("Paginas", "view")) {

            $pagina = Pagina::find($id);
            if(isset($pagina->id)) {
                if($request->ajax() && !isset($request->_pjax)) {
                    return $pagina;
                } else {
                    $module = LAModule::get('Paginas');
                    $module->row = $pagina;

                    return view('la.paginas.show', [
                        'module' => $module,
                        'view_col' => $module->view_col,
                        'no_header' => true,
                        'no_padding' => "no-padding"
                    ])->with('pagina', $pagina);
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
                        'record_name' => ucfirst("pagina"),
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
     * Show the form for editing the specified pagina.
     *
     * @param int $id pagina ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(LAModule::hasAccess("Paginas", "edit")) {
            $pagina = Pagina::find($id);
            if(isset($pagina->id)) {
                $module = LAModule::get('Paginas');

                $module->row = $pagina;

                return view('la.paginas.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('pagina', $pagina);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("pagina"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * Update the specified pagina in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id pagina ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(LAModule::hasAccess("Paginas", "edit")) {
            if($request->ajax()) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Paginas", $request, true);

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

            $pagina_old = Pagina::find($id);

            if(isset($pagina_old->id)) {

                // Update Data
                LAModule::updateRow("Paginas", $request, $id);

                $pagina_new = Pagina::find($id);

                // Add LALog
                LALog::make("Paginas.PAGINA_UPDATED", [
                    'title' => "Pagina Updated",
                    'module_id' => 'Paginas',
                    'context_id' => $pagina_new->id,
                    'content' => [
                        'old' => $pagina_old,
                        'new' => $pagina_new
                    ],
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'object' => $pagina_new,
                        'message' => 'Pagina updated successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/paginas')
                    ], 200);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.paginas.index');
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
                        'record_name' => ucfirst("pagina"),
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
     * Remove the specified pagina from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id pagina ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        if(LAModule::hasAccess("Paginas", "delete")) {

            $pagina = Pagina::find($id);
            if(isset($pagina->id)) {
                $pagina->delete();

                // Add LALog
                LALog::make("Paginas.PAGINA_DELETED", [
                    'title' => "Pagina Deleted",
                    'module_id' => 'Paginas',
                    'context_id' => $pagina->id,
                    'content' => $pagina,
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Record Deleted successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/paginas')
                    ], 204);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.paginas.index');
                }
            } else {
                if($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Record not found'
                    ], 404);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.paginas.index');
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
        $module = LAModule::get('Paginas');
        $listing_cols = LAModule::getListingColumns('Paginas');

        $values = DB::table('paginas')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();

        $fields_popup = LAModuleField::getModuleFields('Paginas');

        for($i = 0; $i < count($data->data); $i++) {

            $pagina = Pagina::find($data->data[$i]->id);

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
                    $data->data[$i]->$col = '<a '.config('laraadmin.ajaxload').' href="' . url(config('laraadmin.adminRoute') . '/paginas/' . $data->data[$i]->id) . '">' . $data->data[$i]->$col . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i]->$col;
                // }
            }

            if($this->show_action) {
                $output = '';
                if(LAModule::hasAccess("Paginas", "edit")) {
                    $output .= '<a '.config('laraadmin.ajaxload').' href="' . url(config('laraadmin.adminRoute') . '/paginas/' . $data->data[$i]->id . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>';
                }

                if(LAModule::hasAccess("Paginas", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.paginas.destroy', $data->data[$i]->id], 'method' => 'delete', 'style' => 'display:inline']);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit" data-toggle="tooltip" title="Delete"><i class="fa fa-times"></i></button>';
                    $output .= Form::close();
                }
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
		Storage::disk('public_paginas')->put($cover->getFilename().'.'.$extension,  File::get($cover));
		if(!Storage::disk('public_uploads')->put('/'.$cover->getFilename().'.'.$extension, File::get($cover))) {
		    return false;
		}

		return '/images/paginas/'.$cover->getFilename().'.'.$extension;
	}

	public function renderPagina($id){
        $pagina = Pagina::find($id);
        
        return View('la.paginas.html', ['content' => $pagina->contenido, 'title' => $pagina->titulo]);
    }
}
