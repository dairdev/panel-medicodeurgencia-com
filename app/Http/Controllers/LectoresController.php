<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers;

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

use App\Models\Lectore;
use App\Models\Codigo;
use Mail;

class LectoresController extends Controller
{
	public $show_action = true;
	public $view_col = 'namo';
	public $listing_cols = ['id', 'colegiado', 'namo', 'surname', 'email', 'phone', 'notas'];
	
	public function __construct() {
	}

        public function updateLector(Request $request, $userId){
            $validator = Validator::make($request->all(), [
			'email' => 'email|unique:lectores,email,'.$userId,
			'phone' => 'numeric',
		]);

		if ($validator->fails()){
			return \Response::json(array(
		        'success' => false,
		        'errors' => $validator->errors()->first()

		    ), 200);
		}

		$user = Lectore::find($userId);
		
		if($request->get('namo')){
			$user->namo = $request->get('namo');
		}

		if($request->get('surname')){
			$user->surname = $request->get('surname');
		}

		if($request->get('email')){
			$user->email = $request->get('email');
		}

		if($request->get('phone')){
			$user->phone = $request->get('phone');
		}

		$user->save();
		return \Response::json(array(
	        'success' => true,
	        'user' => $user,
	        'errors' => []

	    ), 200);
	}

	public function saveAndActivateLector(Request $request, $code){

            //Activate code
            $s = Codigo::where('codigo', $code)->where(function ($q) {
                $q->where('date_validez', '>=', date('Y-m-d'))->orWhereNull('date_validez');
            })->first();

            if($s){
                if(empty($s->date_validez)){
                    $s->date_validez = date("Y-m-d", strtotime('+ '.$s->type.' days'));
                }
                $s->uses = 1;
				$s->date_validez = date("Y-m-d", strtotime('+ '.$s->type.' days'));
				$s->date_compra = date("Y-m-d");
                $s->save();
            }

            $userId = $s->lectore_id;

            $validator = Validator::make($request->all(), [
                'email' => 'email|unique:lectores,email,'.$userId,
                'phone' => 'numeric',
            ]);

            if ($validator->fails()){
                return \Response::json(array(
                    'success' => false,
                    'errors' => $validator->errors()->first()

                ), 200);
            }

            $user = Lectore::find($userId);

            if($request->get('namo')){
                $user->namo = $request->get('namo');
            }

            if($request->get('surname')){
                $user->surname = $request->get('surname');
            }

            if($request->get('email')){
                $user->email = $request->get('email');
            }

            if($request->get('password')){
                $user->password = $request->get('password');
            }

            if($request->get('phone')){
                $user->phone = $request->get('phone');
            }

            $user->save();

            return \Response::json(array(
                'success' => true,
                'user' => $user,
                'date_validez' => $s->date_validez,
				'expiracion' => $s->date_validez,
                'errors' => []
            ), 200);
	}

}
