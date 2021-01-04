<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Carbon\Carbon;
use ZipArchive;

use App\Models\Documentacion;
use App\Models\Documentos_documentacion;

class DocumentacionController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $documentacion = Documentacion::all();

        return view('documentacion', ['documentacion' => $documentacion]);
    }

    public function create_modulo(Request $request) {
        $modulo = Documentacion::create(['nombre' => $request['nombre']]);

        return redirect()->back()->with(['error' => 0, 'mensaje' => 'Modulo creado correctamente']);
    }

    public function delete_modulo(Request $request) {
        return Documentacion::find($request['id'])->delete();
    }

    public function agregar_documento(Request $request) {
        $date = Carbon::now('America/Bogota');

        $nombre = ($request['nombre'] == 'Otros') ? $request['nombre_otros'] : $request['nombre'];

        if ($request->file('file')) {
            $extension_file_documento = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_documento = 'docs/documentacion/documentos/';
            $nombre_file_documento = 'documento_'.$date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
            Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('file')));

            $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;
        }

        $documento = Documentos_documentacion::create([
            'nombre' => $nombre,
            'file' => $nombre_completo_file_documento,
            'documentacion_id' => $request['documentacion_id'],
        ]);

        if ( $documento->save() ) {
            return $request['documentacion_id'];
        }
    }

    public function cargar_documentos(Request $request) {
        $documentos = Documentos_documentacion::where('documentacion_id', $request['id'])->get();

        return $documentos;
    }

    public function delete_documento(Request $request) {
        return Documentos_documentacion::find($request['id'])->delete();
    }

    public function cargar_documentos_all() {
        return Documentos_documentacion::all();
    }

    public function exportar_documentos(Request $request) {
        $zip = new ZipArchive();

        if(!$zip->open(public_path('storage/docs/documentacion/documentacion.zip'), ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE){
            return 'error';
        }

        foreach ($request['documentos'] as $row) {
            $documento = Documentos_documentacion::find($row)->file;
            $documento_nombre = Documentos_documentacion::find($row)->nombre;
            $documento_extencion = pathinfo($documento, PATHINFO_EXTENSION);
            $zip->addFile('storage/'.$documento, $documento_nombre.'.'.$documento_extencion);
        }

        $zip->close();

        return true;
    }
}
