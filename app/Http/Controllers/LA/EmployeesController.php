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

use App\Models\Employee;
use App\Models\Lectore;
use App\Models\Codigo;
use App\Models\User;
use App\Models\Role;
use Mail;

class EmployeesController extends Controller
{
    public $show_action = true;
    public $view_col = 'name';
	public $listing_cols = ['id', 'name', 'about', 'designation', 'mobile', 'email', 'dept', 'limitcode'];
    /**
     * Display a listing of the Employees.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $module = LAModule::get('Employees');

        if(LAModule::hasAccess($module->id)) {
            if($request->ajax() && !isset($request->_pjax)) {
                // TODO: Implement good Query Builder
                return Employee::all();
            } else {
                return View('la.employees.index', [
                    'show_actions' => $this->show_action,
                    'listing_cols' => $this->listing_cols,
                    'module' => $module
                ]);
                echo $module;
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
     * Show the form for creating a new employee.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created employee in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        if(LAModule::hasAccess("Employees", "create")) {
            if($request->ajax() && !isset($request->quick_add)) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Employees", $request);
            $rules['password'] = 'required|min:6|max:15';
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
            $password = $request->password;
			$request->token = bin2hex(openssl_random_pseudo_bytes(64));
            
            $insert_id = LAModule::insert("Employees", $request);

            $employee = Employee::find($insert_id);
            // Create User
			$user = User::create([
				'name' => $request->name,
				'email' => $request->email,
				'password' => bcrypt($password),
				'context_id' => $insert_id,
			]);
	
			// update user role
			$user->detachRoles();
			$role = Role::find($request->role);
			$user->attachRole($role);
			
			//Email admin
			$to_name = env('MAIL_USERNAME');
			$to_email = env('MAIL_USERNAME');
			$data = array('password' => $password, 'user' => $user);

			Mail::send('emails.empresa', $data, function($message) use ($to_name, $to_email) {
				$message->to($to_email, $to_name)->subject('Datos de nueva empresa creada');
				$message->from(env('MAIL_USERNAME'),'Médico de Urgencias');
			});
			
			//Email empresa
			$to_name = $user->name;
			$to_email = $user->email;
			$data = array('password' => $password, 'user' => $user);

			Mail::send('emails.empresa2', $data, function($message) use ($to_name, $to_email) {
				$message->to($to_email, $to_name)->subject('Datos de acceso a MedicoDeUrgencias');
				$message->from(env('MAIL_USERNAME'),'Médico de Urgencias');
			});
            // Add LALog
            LALog::make("Employees.EMPLOYEE_CREATED", [
                'title' => "Employee Created",
                'module_id' => 'Employees',
                'context_id' => $employee->id,
                'content' => $employee,
                'user_id' => Auth::user()->id,
                'notify_to' => "[]"
            ]);

            if($request->ajax() || isset($request->quick_add)) {
                return response()->json([
                    'status' => 'success',
                    'object' => $employee,
                    'message' => 'Employee updated successfully!',
                    'redirect' => url(config('laraadmin.adminRoute') . '/employees')
                ], 201);
            } else {
                return redirect()->route(config('laraadmin.adminRoute') . '.employees.index');
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
     * Display the specified employee.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id employee ID
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        if(LAModule::hasAccess("Employees", "view")) {

            $employee = Employee::find($id);
            if(isset($employee->id)) {
                if($request->ajax() && !isset($request->_pjax)) {
                    return $employee;
                } else {
                    $module = LAModule::get('Employees');
                    $module->row = $employee;

                    // Get User Table Information
				$user = User::where('context_id', '=', $id)->firstOrFail();
				//$lectores = Lectore::where('user_id', $user->id)->get();
                $lectores = DB::table('lectores')
            ->join('codigos', 'lectores.id', '=', 'codigos.lectore_id')
            ->select(
                'lectores.id',
                'lectores.namo',
                'lectores.surname',
                'lectores.email',
                'codigos.codigo',
				'codigos.date_validez'
            )
            ->where('lectores.deleted_at', '=', null)
			->where('codigos.deleted_at', '=', null)
            ->where('lectores.user_id', '=', $user->id)
            ->get();

                    return view('la.employees.show', [
                        'user' => $user,
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding",
					'lectores' => $lectores
                    ])->with('employee', $employee);
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
                        'record_name' => ucfirst("employee"),
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
     * Show the form for editing the specified employee.
     *
     * @param int $id employee ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(LAModule::hasAccess("Employees", "edit")) {
            $employee = Employee::find($id);
            if(isset($employee->id)) {
                $module = LAModule::get('Employees');

                $module->row = $employee;

                $user = User::where('context_id', '=', $id)->firstOrFail();

                return view('la.employees.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'user' => $user,
                ])->with('employee', $employee);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("employee"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * Update the specified employee in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id employee ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(LAModule::hasAccess("Employees", "edit")) {
            if($request->ajax()) {
                $request->merge((array)json_decode($request->getContent()));
            }
            $rules = LAModule::validateRules("Employees", $request, true);

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

            $employee_old = Employee::find($id);

            if(isset($employee_old->id)) {

                // Update Data
                $employee_id = LAModule::updateRow("Employees", $request, $id);

                $employee_new = Employee::find($id);
                // Update User
                $user = User::where('context_id', $employee_id)->first();
                $user->name = $request->name;
                $user->save();
                // Add LALog
                LALog::make("Employees.EMPLOYEE_UPDATED", [
                    'title' => "Employee Updated",
                    'module_id' => 'Employees',
                    'context_id' => $employee_new->id,
                    'content' => [
                        'old' => $employee_old,
                        'new' => $employee_new
                    ],
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'object' => $employee_new,
                        'message' => 'Employee updated successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/employees')
                    ], 200);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.employees.index');
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
                        'record_name' => ucfirst("employee"),
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
     * Remove the specified employee from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id employee ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        if(LAModule::hasAccess("Employees", "delete")) {

            $employee = Employee::find($id);
            if(isset($employee->id)) {
                $employee->delete();

                // Add LALog
                LALog::make("Employees.EMPLOYEE_DELETED", [
                    'title' => "Employee Deleted",
                    'module_id' => 'Employees',
                    'context_id' => $employee->id,
                    'content' => $employee,
                    'user_id' => Auth::user()->id,
                    'notify_to' => "[]"
                ]);

                if($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Record Deleted successfully!',
                        'redirect' => url(config('laraadmin.adminRoute') . '/employees')
                    ], 204);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.employees.index');
                }
            } else {
                if($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Record not found'
                    ], 404);
                } else {
                    return redirect()->route(config('laraadmin.adminRoute') . '.employees.index');
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
        $module = LAModule::get('Employees');
        $listing_cols = LAModule::getListingColumns('Employees');

        $values = DB::table('employees')->select($this->listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();

        $fields_popup = LAModuleField::getModuleFields('Employees');

        for($i = 0; $i < count($data->data); $i++) {

            $employee = Employee::find($data->data[$i]->id);

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
                    $data->data[$i]->$col = '<a '.config('laraadmin.ajaxload').' href="' . url(config('laraadmin.adminRoute') . '/employees/' . $data->data[$i]->id) . '">' . $data->data[$i]->$col . '</a>';
                }

                if($col == 'limitcode'){
					$codigos = DB::select(DB::raw("SELECT count(*) as total FROM codigos INNER JOIN lectores ON lectores.id = codigos.lectore_id INNER JOIN users ON lectores.user_id = users.id WHERE users.id = :iduser"), array('iduser' => $data->data[$i]->id));

					$limite = $data->data[$i]->$col;
					$usados = $codigos[0]->total;
					$libres = $limite - $usados;

					if($data->data[$i]->$col == 0){
						$data->data[$i]->$col = '<strong>ILIMITADOS</strong><br>';
						$data->data[$i]->$col .= '<strong>USADOS: </strong>'. $usados.'<br>';
					}else{
						$data->data[$i]->$col = '<strong>LIMITE: '.$limite.'</strong><br>';
						$data->data[$i]->$col .= '<strong>USADOS: </strong>'. $usados.'<br>';
						$data->data[$i]->$col .= '<strong>LIBRES: </strong>'. $libres.'<br>';
					}
				}
                // else if($col == "author") {
                //    $data->data[$i]->$col;
                // }
            }

            if($this->show_action) {
                $output = '';
                if(LAModule::hasAccess("Employees", "edit")) {
                    $output .= '<a '.config('laraadmin.ajaxload').' href="' . url(config('laraadmin.adminRoute') . '/employees/' . $data->data[$i]->id . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>';
                }

                if(LAModule::hasAccess("Employees", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.employees.destroy', $data->data[$i]->id], 'method' => 'delete', 'style' => 'display:inline']);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit" data-toggle="tooltip" title="Delete"><i class="fa fa-times"></i></button>';
                    $output .= Form::close();
                }

                $output .= ' <button type="button" class="open-AddModalCode btn btn-success btn-xs" data-toggle="modal" data-target="#AddModalCode" data-id="'.$data->data[$i]->id.'"><i class="fa fa-user-plus"></i></button>';
                $output .= ' <a href="../ListaEmpresa.php?id_empresa='.$data->data[$i]->id.'">
                <button class="btn btn-info btn-xs" style="margin-left: 100;" type="submit" data-toggle="tooltip" title="Lista Empresas"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
              </a> ';
                
                $data->data[$i]->dt_action = (string)$output;
            }
        }
        $out->setData($data);
        return $out;
    }
    public function massiveCodes(Request $request){

		$random = date('YmdHis');
		$firstId = 0;
		$lastId = 0;
		if($request->get('id')){
			$user = User::where('context_id', $request->get('id'))->first();
			if($request->get('codes') > 0){
				for ($i = 1; $i <= $request->get('codes'); $i++){
					$lector = new Lectore();
					$lector->namo = $random.'usuario_'.str_pad($i, 4, "0", STR_PAD_LEFT);
					$lector->surname = $random.'apellido_'.str_pad($i, 4, "0", STR_PAD_LEFT);
					$lector->email = $random.'email_'.str_pad($i, 4, "0", STR_PAD_LEFT).'@'.$random.'_generic.com';
					$lector->password = '$2y$10$hE.DjqUfNTYSN.DNbtN2juSahTdQmINm.tSJZO/VkdBCnL5m9XYVO';
					$lector->user_id = $user->id;
					$lector->colegiado = strtoupper(uniqid());
					if($lector->save()){
						if($i == 1){
							$firstId = $lector->id;
						}
	
						if($i == $request->get('codes')){
							$lastId = $lector->id;
						}

						$codigo = new Codigo();
						$codigo->codigo = strtoupper(uniqid());
						$codigo->date_compra = date('Y-m-d');
						$codigo->date_validez = date('Y-m-d', strtotime(date('Y-m-d'). ' + '.$request->get('days').' days'));
						$codigo->lectore_id = $lector->id;
						$codigo->type = $request->get('days');
						$codigo->user_id = $user->id;
						$codigo->renove = 0;
						$codigo->save();
					}else{
						return response()->json(['result' => false]);
					}
				}
                return redirect(config('laraadmin.adminRoute') . '/employees/csv/'.$firstId.'/'.$lastId);
				/*return response()->json([
					'result' => true,
					'first' => $firstId,
					'last' => $lastId
				]);*/
			}
			return response()->json(['result' => false]);
		}
		
		return response()->json(['result' => false]);
	}

	public function downloadCSV($first, $last){
		$fileName = 'codigos.csv';
		$codigos = DB::table('codigos')
            ->join('lectores', 'lectores.id', '=', 'codigos.lectore_id')
            ->select(
                'lectores.namo',
                'lectores.surname',
                'lectores.email',
                'codigos.codigo',
				'codigos.date_validez'
            )
            ->where('lectores.id', '>=', $first)
			->where('lectores.id', '<=', $last)
            ->get();
		
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Usuario', 'Codigo', 'Validez');

        $callback = function() use($codigos, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($codigos as $codigo) {
                $row['Usuario']  = $codigo->namo;
                $row['Codigo']    = $codigo->codigo;
                $row['Validez']  = $codigo->date_validez;

                fputcsv($file, array(
					$row['Usuario'], 
					$row['Codigo'], 
					$row['Validez']
				));
            }

            fclose($file);
        };
		
        return response()->stream($callback, 200, $headers);
	}
    /**
     * Change Employee Password
     *
     * @return
     */
	public function change_password($id, Request $request) {
		
		$validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
			'password_confirmation' => 'required|min:6|same:password'
        ]);
		
		if ($validator->fails()) {
			return \Redirect::to(config('laraadmin.adminRoute') . '/employees/'.$id)->withErrors($validator);
		}
		
		$employee = Employee::find($id);
		$user = User::where("context_id", $employee->id)->first();
		$user->password = bcrypt($request->password);
		$user->save();
		
		\Session::flash('success_message', 'La contraseña se cambió correctamente');
		
		
		return redirect(config('laraadmin.adminRoute') . '/employees/'.$id.'#tab-account-settings');
	}

}
