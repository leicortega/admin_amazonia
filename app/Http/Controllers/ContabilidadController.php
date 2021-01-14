<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contabilidad;
use App\Models\Personal;
use App\Models\Vehiculo;
use App\Models\Cargos_personal;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\NotificationMail;
use Carbon\Carbon;

class ContabilidadController extends Controller
{
    public $date;

    public function __construct() {
        $this->date = Carbon::now('America/Bogota');
        $this->middleware('auth');
    }

    public function index() {
        $persona = Personal::where('identificacion', auth()->user()->identificacion)->first();

        // if (auth()->user()->hasRole('general')) {
        //     $registros = DB::table('contabilidad')
        //                 ->join('vehiculos', 'vehiculos.id', '=', 'contabilidad.vehiculos_id')
        //                 ->select('contabilidad.*', 'vehiculos.*')
        //                 ->where('vehiculos.personal_id', $persona->id)
        //                 ->paginate(10);
        // } else {
        //     $registros = DB::table('contabilidad')
        //                 ->join('vehiculos', 'vehiculos.id', '=', 'contabilidad.vehiculos_id')
        //                 ->select('contabilidad.*', 'vehiculos.*')
        //                 ->paginate(10);
        // }

        $placas = Vehiculo::all();

        $propietarios = Cargos_personal::join('cargos', 'cargos.id', '=', 'cargos_personal.cargos_id')
                ->join('personal', 'personal.id', '=', 'cargos_personal.personal_id')
                ->select('personal.id', 'personal.nombres', 'personal.primer_apellido', 'personal.segundo_apellido')
                ->where('cargos.nombre', 'Propietario')
                ->get();

        $vehiculos = Vehiculo::paginate(10);

        return view('contabilidad.index', ['vehiculos' => $vehiculos, 'propietarios' => $propietarios, 'placas' => $placas]);
    }

    public function filtrar(){

        $propietarios = Cargos_personal::join('cargos', 'cargos.id', '=', 'cargos_personal.cargos_id')
                ->join('personal', 'personal.id', '=', 'cargos_personal.personal_id')
                ->select('personal.id', 'personal.nombres', 'personal.primer_apellido', 'personal.segundo_apellido')
                ->where('cargos.nombre', 'Propietario')
                ->get();

        $vehiculos = Vehiculo::select('vehiculos.*');
        $placas = Vehiculo::all();

        if(isset($_GET['propietario']) && $_GET['propietario'] != null) {
            $vehiculos = $vehiculos->join('personal', 'personal.id', '=', 'vehiculos.personal_id');
            $vehiculos = $vehiculos->where('personal.id',$_GET['propietario']);
        }
        if(isset($_GET['placa']) && $_GET['placa'] != null) {
            $vehiculos = $vehiculos->where('id',$_GET['placa']);
        }

        if(isset($_GET['search']) && $_GET['search'] != null) {
            $vehiculos = $vehiculos->join('personal', 'personal.id', '=', 'vehiculos.personal_id');
            $vehiculos = $vehiculos->where('personal.nombres', 'like', "%" . $_GET['search'] . "%");
            $vehiculos = $vehiculos->orwhere('placa', 'like', "%" . $_GET['search'] . "%");
        }

        $vehiculos = $vehiculos->paginate(10);

        return view('contabilidad.index', ['vehiculos' => $vehiculos, 'propietarios' => $propietarios, 'placas' => $placas]);
    }

    public function create(Request $request) {
        if ($request->file('anexo')) {
            $extension_file_documento = pathinfo($request->file('anexo')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_documento = 'docs/contabilidad/anexos/';
            $nombre_file_documento = 'anexo_'.$this->date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
            Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('anexo')));

            $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;
        }

        $contabilidad = Contabilidad::create([
            'persona_creo' => auth()->user()->name,
            'fecha' => $this->date->format('Y-m-d'),
            'concepto' => $request['concepto'],
            'valor' => $request['valor'],
            'tipo' => $request['tipo'],
            'estado' => 0,
            'anexo' => $nombre_completo_file_documento ?? null,
            'vehiculos_id' => $request['vehiculos_id'],
        ]);

        // $data = [
        //     'titulo' => 'NUEVO REGISTRO CONTABLE AGREGADO',
        //     'link' => 'https://admin.amazoniacl.com/tareas/ver/'.$tarea->id
        // ];

        // Mail::to(User::find($request['asignado'])->email)->send(new NotificationMail($data));

        if ($contabilidad->save()) {
            return redirect()->back()->with(['create' => 1, 'mensaje' => 'Registro agregado correctamente']);
        }

        return redirect()->back()->with(['create' => 0, 'mensaje' => 'Ocurrio un error, intente de nuevo']);
    }

    public function ver(Request $request) {
        $registros = Contabilidad::where('vehiculos_id', $request['id'])->paginate(20);

        $por_pagar = $registros->where('tipo', 'Por pagar')->where('estado', 0);
        $por_cobrar = $registros->where('tipo', 'Por cobrar')->where('estado', 0);

        $total_por_pagar = 0;
        $total_por_cobrar = 0;

        foreach ($por_pagar as $row_pagar) {
            $total_por_pagar = $total_por_pagar + $row_pagar['valor'];
        }

        foreach ($por_cobrar as $row_cobrar) {
            $total_por_cobrar = $total_por_cobrar + $row_cobrar['valor'];
        }

        $estado_cuenta = $total_por_cobrar - $total_por_pagar;
        $estado = $total_por_pagar > $total_por_cobrar ? 'Por pagar' : 'Por cobrar';

        return view('contabilidad.ver', [
            'registros' => $registros,
            'total_por_pagar' => $total_por_pagar,
            'total_por_cobrar' => $total_por_cobrar,
            'estado_cuenta' => $estado_cuenta,
            'estado' => $estado,
            'id' => $request['id']
        ]);
    }
}
