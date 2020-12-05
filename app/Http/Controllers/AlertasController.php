<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Documentos_legales_vehiculo;
use App\Models\Sistema\Alerta;
use Illuminate\Support\Facades\Mail;
use App\Mail\AlertasMail;

use Carbon\Carbon;

class AlertasController extends Controller
{
    public function alerta_documentos() {
        $ultima_alerta = Alerta::orderBy('id', 'desc')->first();

        if (!$ultima_alerta || $ultima_alerta->fecha < Carbon::now('America/Bogota')->format('Y-m-d')) {
            $alerta_documentos = Documentos_legales_vehiculo::whereNotNull('fecha_fin_vigencia')->where('ultimo', 1)->orderBy('fecha_fin_vigencia', 'desc')->with('vehiculo')->get();

            $documetos_por_vencer = [];
            $documetos_vencidos = [];

            foreach ($alerta_documentos as $alerta) {
                if (Carbon::parse($alerta['fecha_fin_vigencia'])->diffInDays(Carbon::now('America/Bogota')) < 30 && Carbon::now('America/Bogota')->format('Y-m-d') < $alerta['fecha_fin_vigencia']) {
                    array_push($documetos_por_vencer, [
                        'documento'=> $alerta['tipo'],
                        'vigencia'=> $alerta['fecha_fin_vigencia'],
                        'vehiculo'=> $alerta['vehiculo']['placa'],
                        'vehiculo_id'=> $alerta['vehiculo']['id'],
                    ]);
                }
            }

            foreach ($alerta_documentos as $alerta) {
                if (Carbon::now('America/Bogota')->format('Y-m-d') > $alerta['fecha_fin_vigencia']) {
                    array_push($documetos_vencidos, [
                        'documento'=> $alerta['tipo'],
                        'vigencia'=> $alerta['fecha_fin_vigencia'],
                        'vehiculo'=> $alerta['vehiculo']['placa'],
                        'vehiculo_id'=> $alerta['vehiculo']['id'],
                    ]);
                }
            }

            $data = [
                'titulo' => 'ALERTA DOCUMENTOS VENCIDOS Y POR VENCER',
                'documetos_por_vencer' => $documetos_por_vencer,
                'documetos_vencidos' => $documetos_vencidos,
            ];

            Mail::to('calidad@amazoniacl.com')->send(new AlertasMail($data));

            Alerta::create([
                'fecha' => Carbon::now('America/Bogota')->format('Y-m-d'),
                'notificado' => 'Si',
                'alertas' => 'Si'
            ]);

            return 1;

        } else {
            return 0;
        }

    }
}
