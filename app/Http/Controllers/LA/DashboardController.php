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
use App\Http\Requests;
use App\Models\Chapter;
use App\Models\Subchapter;
use App\Models\Specialty;
use App\Models\Pagina;
use App\Models\Authore;
use App\Models\Lectore;
use App\Models\Codigo;
use App\Models\Employee;
use Illuminate\Http\Request;
use Auth;
/**
 * Dashboard Controller.
 */
class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $capitulos = Chapter::count();
        $subcapitulos = Subchapter::count();
        $especialidades = Specialty::count();
        $paginas = Pagina::count();
        $autores = Authore::count();
        $empresas = Employee::count();
        if(Auth::user()->roles[0]->id == 3){
            $lectores = Lectore::where('user_id', Auth::user()->id)->count();
            $codigos = Codigo::where('user_id', Auth::user()->id)->count();
        }else{
            $lectores = Lectore::count();
            $codigos = Codigo::count();
        }
        return view('la.dashboard', compact(
            'capitulos', 
            'especialidades', 
            'paginas', 
            'autores', 
            'lectores', 
            'codigos',
            'empresas',
            'subcapitulos'
        ));
    }
}
