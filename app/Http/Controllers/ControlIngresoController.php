<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\FormCreateControlIngresoRequest;
use App\Models\Control_ingreso;
use App\Models\Ingreso;
use App\User;
use PDF;

class ControlIngresoController extends Controller
{
    public $fecha; 

    public function __construct() {
        $this->middleware('auth');

        // $this->fecha;
    }

    public function funcionarios () { 
        if (Auth::user()->hasRole('admin')) {
            $funcionarios = Control_ingreso::with(array('ingresos' => function($query){
                                $query->orderBy('fecha','desc'); 
                            }))
                            ->where('tipo', 'Funcionario')
                            ->paginate(10);
        } else {
            $funcionarios = Control_ingreso::with(array('ingresos' => function($query){
                                $query->orderBy('fecha','desc'); 
                            }))
                            ->where('tipo', 'Funcionario')
                            ->where('identificacion', Auth::user()->identificacion)
                            ->paginate(10);
        }

        return view('control_ingreso.index', ['funcionarios' => $funcionarios]);
    }

    public function clientes () {
        if (Auth::user()->hasRole('admin')) {
            $funcionarios = Control_ingreso::with(array('ingresos' => function($query){
                                $query->orderBy('fecha','desc'); 
                            }))
                            ->where('tipo', 'Cliente')
                            ->paginate(10);
        } else {
            $funcionarios = Control_ingreso::with(array('ingresos' => function($query){
                                $query->orderBy('fecha','desc'); 
                            }))
                            ->where('tipo', 'Cliente')
                            ->where('identificacion', Auth::user()->identificacion)
                            ->paginate(10);
        }

        return view('control_ingreso.index', ['funcionarios' => $funcionarios]);
    }

    public function search(Request $request) {
        $funcionarios = Control_ingreso::with(array('ingresos' => function($query){
                    $query->orderBy('fecha','desc'); 
                    $query->where('sede', Auth::user()->sede); 
                }))
                ->where('identificacion', $request['identificacion_search'])
                ->paginate(10);

        return view('control_ingreso.index', ['funcionarios' => $funcionarios]);
    }

    public function create(FormCreateControlIngresoRequest $request) {
        $new = Control_ingreso::create([
            'name' => $request['name'],
            'identificacion' => $request['identificacion'],
            'telefono' => $request['telefono'],
            'edad' => $request['edad'],
            'email' => $request['email'],
            'tipo' => $request['tipo'],
        ]);

        if($new->save()){
            if ($request['tipo'] == 'Funcionario') {
                return redirect()->route('funcionarios', ['create' => 1, 'id' => $new->id, 'name' => $new->name]);
            } else {
                return redirect()->route('clientes', ['create' => 1, 'id' => $new->id, 'name' => $new->name]);
            }
        } else {
            if ($request['tipo'] == 'Funcionario') {
                return redirect()->route('funcionarios')->with('create', 0);
            } else {
                return redirect()->route('clientes')->with('create', 0);
            }
        }
    }

    public function registrar(Request $request) {
        $ingresado_hoy = Ingreso::where([['control_ingreso_id', $request['control_ingreso_id']], ['fecha', $request['fecha']], ['sede', Auth::user()->sede]])->exists();

        $funcionarios = Control_ingreso::with(array('ingresos' => function($query){
                $query->orderBy('fecha','desc'); }))
                ->paginate(10);

        if ($ingresado_hoy) {
            if ($request['tipo'] == 'Funcionario') {
                return redirect()->route('funcionarios')->with('ingreso', 2);
            } else {
                return redirect()->route('clientes')->with('ingreso', 2);
            }
        } else {
            $new_ingreso = Ingreso::create([
                'fecha' => $request['fecha'],
                'temperatura' => $request['temperatura'],

                'pregunta_one' => $request['pregunta_one'],
                'pregunta_two' => $request['pregunta_two'],
                'pregunta_three' => $request['pregunta_three'],
                'fiebre' => $request['fiebre'],
                'tos' => $request['tos'],
                'gripa' => $request['gripa'],
                'malestar' => $request['malestar'],
                'dolor_cabeza' => $request['dolor_cabeza'],
                'fatiga' => $request['fatiga'],
                'secrecion_nasal' => $request['secrecion_nasal'],
                'dificultad_respirar' => $request['dificultad_respirar'],
                'dolor_garganta' => $request['dolor_garganta'],
                'olfato_gusto' => $request['olfato_gusto'],
                'diabetes' => $request['diabetes'],
                'hipertension' => $request['hipertension'],
                'mayor_edad' => $request['mayor_edad'],
                'cancer' => $request['cancer'],
                'inmunodeficiencia' => $request['inmunodeficiencia'],

                'control_ingreso_id' => $request['control_ingreso_id'],
                'sede' => 'Amazonia C&L Central, Neiva'
            ]);
    
            if($new_ingreso->save()){
                if ($request['tipo'] == 'Funcionario') {
                    return redirect()->route('funcionarios')->with('ingreso', 1);
                } else {
                    return redirect()->route('clientes')->with('ingreso', 1);
                }
            } else {
                if ($request['tipo'] == 'Funcionario') {
                    return redirect()->route('funcionarios')->with('ingreso', 0);
                } else {
                    return redirect()->route('clientes')->with('ingreso', 0);
                }
            }
        }
    }

    public function createSearch(Request $request) {
        $user = Control_ingreso::where('identificacion', $request['id'])->get();

        if ($user[0]->exists()) {
            return ['id' => $user[0]->id, 'name' => $user[0]->name];
        }
    }

    public function historialIngresos(Request $request) {
        $historial = Ingreso::where('control_ingreso_id', $request['id'])->orderBy('fecha', 'desc')->get();

        return ['historial' => $historial];
    }

    public function printIngreso(Request $request) {

        $this->fecha = $request['fecha'];

        $ingreso = Control_ingreso::with(array('ingresos' => function($query){
                                        $query->where('fecha', $this->fecha); 
                                    }))
                                    ->where('id', $request['id'])
                                    ->get();
        // dd($ingreso[0]['ingresos'][0]['temperatura']);
        
        $pdf = PDF::loadView('control_ingreso.pdf', compact('ingreso'))->setPaper('A4');
        
        return $pdf->stream();
    }
}
