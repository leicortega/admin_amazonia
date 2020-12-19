<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HseqController extends Controller
{
    public function list() {
        $dir = '/16OSoQwhwXii2Fhtd65_OL3RFLs9cXS2m';
        $recursive = false;
        $contents = collect(\Storage::cloud()->listContents($dir, $recursive));

        // dd($contents);
        return view('HSEQ.index', ['files' => $contents]);
    }

    public function list_folder(Request $request) {
        $dir = $request['folder'];
        $recursive = false;
        $contents = collect(\Storage::cloud()->listContents($dir, $recursive));

        return view('HSEQ.index', ['files' => $contents]);
    }

    public function list_folder_return(Request $request) {
        $dir = $request['path'];
        $recursive = false;
        $contents = collect(\Storage::cloud()->listContents($dir, $recursive));

        // dd($contents);
        return view('HSEQ.index', ['files' => $contents]);
    }

    public function create_dir(Request $request) {
        $str = explode ("/", $request['path']);
        $path = ($request['path'] == '/') ? '/16OSoQwhwXii2Fhtd65_OL3RFLs9cXS2m' : $str[2].'/'.$str[3];

        \Storage::cloud()->makeDirectory($path.'/'.$request['nombre_carpeta']);
        return redirect()->back()->with(['create' => 1, 'mensaje' => 'La carpeta se creo correctamente']);
    }

    public function subir_archivo(Request $request) {
        $str = explode ("/", $request['path']);
        $path = ($request['path'] == '/') ? '/16OSoQwhwXii2Fhtd65_OL3RFLs9cXS2m' : $str[2].'/'.$str[3];

        \Storage::cloud()->put($path.'/'.$request->file('file')->getClientOriginalName(), \File::get($request->file('file')));
        return redirect()->back()->with(['create' => 1, 'mensaje' => 'El documento se subio correctamente']);
    }

    public function descargar(Request $request) {
        $rawData = \Storage::cloud()->get($request['path']);
        $filename = $request['file'];

        return response($rawData, 200)
            ->header('ContentType', $request['mimetype'])
            ->header('Content-Disposition', "attachment; filename=$filename");
    }

    public function eliminar_archivo(Request $request) {
        \Storage::cloud()->delete($request['path']);

        return redirect()->back()->with(['create' => 1, 'mensaje' => 'El documento se elimino correctamente']);
    }

    public function eliminar_carpeta(Request $request) {
        \Storage::cloud()->deleteDirectory($request['path']);

        return redirect()->back()->with(['create' => 1, 'mensaje' => 'La carpeta se elimino correctamente']);
    }
}
