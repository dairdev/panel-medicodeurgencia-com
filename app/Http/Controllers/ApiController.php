<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Codigo;
use App\Models\Lectore;
use App\User;
use Illuminate\Support\Facades\Validator;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{

    public $token;
    public $employee;
    public $user;

    public $output = [
        'data' => [
            'error' => []
        ],
        'meta' => [
            'code' => ''
        ]
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->token = $request->header('X-Token-Auth');
        $this->employee = Employee::where('token', $this->token)->whereNull('deleted_at')->first();
        if($this->employee){
            $this->user = User::where('context_id', $this->employee->id)->whereNull('deleted_at')->first();    
        }
        
    }

    public function storeColegiado(Request $request)
    {
        $validator = Validator::make($request->all(), 
            [
                'num_colegiado' => 'required|size:9',
                'name' => 'required|max:30',
                'surname' => 'required|max:60',
                'email' => 'required|max:150',
                'phone' => 'max:15'
            ],
            [
                'required' => 'El campo :attribute es obligaorio',
                'size' => 'El campo :attribute excede el máximo de caracteres que son :size'
            ]
        );

        if($validator->fails()){
            $this->output['data']['error'] = $validator->messages();
            $this->output['meta']['code'] = 400;
            return response()->json($this->output, 400);
        }

        $codigos = Codigo::where('user_id', $this->user->id)->count();
        if($this->employee->limitcode > 0 && $this->employee->limitcode <= $codigos){
            $this->output['data']['error']['limite'] = 'Excedido limite de codigos. No puedes crear nuevos lectores. Contacta con administración';
            $this->output['meta']['code'] = 400;
            return response()->json($this->output, 400);
        }

        if(Lectore::where('colegiado', $request->num_colegiado)->count() > 0){
            $this->output['data']['error']['save'] = 'El colegiado ya existe en el sistema. No puede crearse otro igual';
            $this->output['meta']['code'] = 400;
            return response()->json($this->output, 400);
        }

        $lector = new Lectore();
        $lector->namo = $request->name;
        $lector->surname = $request->surname;
        $lector->email = $request->email;
        $lector->phone = $request->phone;
        $lector->password = bcrypt($request->num_colegiado);
        $lector->user_id = $this->user->id;
        $lector->notas = !empty($request->notes) ? $request->notes : null;
        $lector->colegiado = $request->num_colegiado;

        if(!$lector->save()){
            $this->output['data']['error']['save'] = 'No se ha podido crear el colegiado';
            $this->output['meta']['code'] = 400;
            return response()->json($this->output, 400);
        }

        $codigo = new Codigo;
        $codigo->codigo = strtoupper(uniqid());
        $codigo->date_compra = date('Y-m-d');
        $codigo->type = $this->employee->type;
        $codigo->renove = $this->employee->renove;
        $codigo->lectore_id = $lector->id;
        $codigo->user_id = $this->user->id;

        $date_validez = new \DateTime(date('Y-m-d') . ' + ' . $codigo->type . ' days');
        $codigo->date_validez = $date_validez->format('Y-m-d');

        if(!$codigo->save()){
            $this->output['data']['error']['save'] = 'Se ha generado el colegiado pero el código no se ha podido crear.';
            $this->output['meta']['code'] = 400;
            return response()->json($this->output, 400);
        }

        $this->output['data']['colegiado'] = [
            'name' => $lector->namo,
            'surname' => $lector->surname,
            'email' => $lector->email,
            'phone' => $lector->phone,
            'num_colegiado' => $lector->colegiado
        ];
        $this->output['data']['code'] = [
            'code' => $codigo->codigo,
            'fecha_compra' => $codigo->date_compra->format('Y-m-d'),
            'fecha_validez' => $codigo->date_validez->format('Y-m-d'),
            'duracion' => $codigo->type
        ];
        $this->output['meta']['code'] = 200;
        return response()->json($this->output, 200);
    }

    public function updateColegiado(Request $request, $num_colegiado)
    {
        $lector = Lectore::where('colegiado', $num_colegiado)
                    ->where('user_id', $this->user->id)
                    ->whereNull('deleted_at')
                    ->first();

        if(!$lector){
            $this->output['data']['error']['exists'] = 'El colegiado no existe. No se ha podido recuperar';
            $this->output['meta']['code'] = 404;
            return response()->json($this->output, 404);
        }

        $validator = Validator::make($request->all(), 
            [
                'name' => 'required|max:30',
                'surname' => 'required|max:60',
                'email' => 'required|max:150',
                'phone' => 'max:15'
            ],
            [
                'required' => 'El campo :attribute es obligaorio',
                'size' => 'El campo :attribute excede el máximo de caracteres que son :size'
            ]
        );

        if($validator->fails()){
            $this->output['data']['error'] = $validator->messages();
            $this->output['meta']['code'] = 400;
            return response()->json($this->output, 400);
        }

        $lector->namo = $request->name;
        $lector->surname = $request->surname;
        $lector->email = $request->email;
        $lector->phone = $request->phone;
        if(!empty($request->notes)){
            $lector->notas = $request->notes;
        }

        if(!$lector->save()){
            $this->output['data']['error']['save'] = 'No se ha podido crear el colegiado';
            $this->output['meta']['code'] = 400;
            return response()->json($this->output, 400);
        }

        $this->output['data']['colegiado'] = [
            'name' => $lector->namo,
            'surname' => $lector->surname,
            'email' => $lector->email,
            'phone' => $lector->phone,
            'num_colegiado' => $lector->colegiado
        ];
        
        $this->output['meta']['code'] = 200;
        return response()->json($this->output, 200);
    }

    public function showColegiado($num_colegiado)
    {
        $lector = Lectore::where('colegiado', $num_colegiado)
                    ->where('user_id', $this->user->id)
                    ->whereNull('deleted_at')
                    ->first();

        if(!$lector){
            $this->output['data']['error']['exists'] = 'El colegiado no existe. No se ha podido recuperar';
            $this->output['meta']['code'] = 404;
            return response()->json($this->output, 404);
        }

        $codigos = Codigo::where('lectore_id', $lector->id)
                    ->where('user_id', $this->user->id)
                    ->whereNull('deleted_at')
                    ->get();

        $this->output['data']['colegiado'] = [
            'name' => $lector->namo,
            'surname' => $lector->surname,
            'email' => $lector->email,
            'phone' => $lector->phone,
            'num_colegiado' => $lector->colegiado
        ];
        
        $this->output['data']['colegiado']['codes'] = [];

        foreach($codigos as $codigo){
            if(empty($codigo->date_validez)){
                $status = 'SIN USO';
            }elseif($codigo->date_validez->gt(\Carbon\Carbon::now())){
                $status = 'EN USO';
            }else{
                $status = 'CADUCADO';
            }
            $this->output['data']['colegiado']['codes'][] = [
                'code' => $codigo->codigo,
                'date_compra' => $codigo->date_compra->format('Y-m-d'),
                'date_validez' => !empty($codigo->date_validez) ? $codigo->date_validez->format('Y-m-d') : null,
                'duracion' => $codigo->type,
                'status' => $status
            ];
        }

        $this->output['meta']['code'] = 200;
        return response()->json($this->output, 200);
    }

    public function indexColegiado()
    {
        $lectores = Lectore::where('user_id', $this->user->id)
                    ->whereNull('deleted_at')
                    ->get();

        if($lectores->count() == 0){
            $this->output['data']['colegiados'] = [];
            $this->output['meta']['code'] = 200;
            return response()->json($this->output, 200);
        }

        foreach($lectores as $lector){
            $codigos = Codigo::where('lectore_id', $lector->id)
                    ->where('user_id', $this->user->id)
                    ->whereNull('deleted_at')
                    ->get();
            
            $codes = [];

            foreach($codigos as $codigo){
                if(empty($codigo->date_validez)){
                    $status = 'SIN USO';
                }elseif($codigo->date_validez->gt(\Carbon\Carbon::now())){
                    $status = 'EN USO';
                }else{
                    $status = 'CADUCADO';
                }
                $codes[] = [
                    'code' => $codigo->codigo,
                    'date_compra' => $codigo->date_compra->format('Y-m-d'),
                    'date_validez' => !empty($codigo->date_validez) ? $codigo->date_validez->format('Y-m-d') : null,
                    'duracion' => $codigo->type,
                    'status' => $status
                ];
            }

            $this->output['data']['colegiados'][] = [
                'name' => $lector->namo,
                'surname' => $lector->surname,
                'email' => $lector->email,
                'phone' => $lector->phone,
                'num_colegiado' => $lector->colegiado,
                'codes' => $codes
            ];
        }

        $this->output['meta']['code'] = 200;
        return response()->json($this->output, 200);
    }

    public function showCode($code)
    {
        $codigo = Codigo::where('codigo', $code)
                    ->where('user_id', $this->user->id)
                    ->whereNull('deleted_at')
                    ->first();

        if(!$codigo){
            $this->output['data']['error']['exists'] = 'El código no existe. No se ha podido recuperar';
            $this->output['meta']['code'] = 404;
            return response()->json($this->output, 404);
        }

        if(empty($codigo->date_validez)){
            $status = 'SIN USO';
        }elseif($codigo->date_validez->gt(\Carbon\Carbon::now())){
            $status = 'EN USO';
        }else{
            $status = 'CADUCADO';
        }

        $this->output['data']['code'] = [
            'code' => $codigo->codigo,
            'date_compra' => $codigo->date_compra->format('Y-m-d'),
            'date_validez' => !empty($codigo->date_validez) ? $codigo->date_validez->format('Y-m-d') : null,
            'duracion' => $codigo->type,
            'status' => $status
        ];
        
        $this->output['meta']['code'] = 200;
        return response()->json($this->output, 200);
    }

    public function indexCodes($start, $end)
    {

        $data = [
            'start' => $start,
            'end' => $end
        ];

        Validator::extend('after_equal', function($attribute, $value, $parameters, $validator) {
            return strtotime($validator->getData()[$parameters[0]]) <= strtotime($value);
        });
        
        $validator = Validator::make($data, 
            [
                'start' => 'required|date',
                'end' => 'required|date|after_equal:start'
            ],
            [
                'required' => 'El campo es obligaorio',
                'date' => 'La fecha :attribute no es correcta',
                'after_equal' => 'La fecha :attribute tiene que ser igual o superior a la start'
            ]
        );
        
        if($validator->fails()){
            $this->output['data']['error'] = $validator->messages();
            $this->output['meta']['code'] = 400;
            return response()->json($this->output, 400);
        }

        $codigos = Codigo::where('user_id', $this->user->id)
                    ->whereDate('date_compra', '>=', $start)
                    ->whereDate('date_compra', '<=', $end)
                    ->whereNull('deleted_at')
                    ->get();

        if($codigos->count() == 0){
            $this->output['data']['codes'] = [];
            $this->output['meta']['code'] = 200;
            return response()->json($this->output, 200);
        }

        foreach($codigos as $codigo){
            $this->output['data']['codes'][] = [
                'code' => $codigo->codigo,
                'date_compra' => $codigo->date_compra->format('Y-m-d'),
                'date_validez' => !empty($codigo->date_validez) ? $codigo->date_validez->format('Y-m-d') : null,
                'duracion' => $codigo->type
            ];
        }

        $this->output['meta']['code'] = 200;
        return response()->json($this->output, 200);
    }

}
