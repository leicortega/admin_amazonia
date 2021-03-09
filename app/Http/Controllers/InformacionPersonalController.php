<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personal;
use Illuminate\Support\Facades\Crypt;

class InformacionPersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        $qr='';
        $identification=$request->identificacion?$request->identificacion:0;
        if ($identification!=0) {
            $qr=Personal::select('id')->where('identificacion',$request->identificacion)->first();
            if ($qr) {
                $qrcrypt=Crypt::encryptString($qr->id);
                return view('personal.carnet_virtual.informacion_personal',compact(['qrcrypt','qr','identification']));
            }else{
                $msg='Usuario no encontrado';
                return view('personal.carnet_virtual.informacion_personal',compact(['msg','qr','identification']));
            }
        }
        return view('personal.carnet_virtual.informacion_personal',compact('qr','identification'));
        
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id=Crypt::decryptString($id);
        $persona=Personal::with('cargos_personal.cargos')->findorfail($id);

        // return $persona;
        return view('personal.carnet_virtual.carnet',compact('persona'));
    }
}
