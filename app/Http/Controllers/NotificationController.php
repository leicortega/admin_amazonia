<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    
    public function index()
    {
        //
    }

    public function ver(Request $request) {
        $notificacion = Notification::find($request['id']);

        $notificacion->visto = '1';

        if ($notificacion->save()) {
            return $notificacion;
        }
        
    }

}
