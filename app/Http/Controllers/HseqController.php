<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HseqController extends Controller
{
    public function list() {
        $dir = '/16OSoQwhwXii2Fhtd65_OL3RFLs9cXS2m';
        $recursive = false; // Get subdirectories also?
        $contents = collect(\Storage::cloud()->listContents($dir, $recursive));

        //return $contents->where('type', '=', 'dir'); // directories
        //return $contents->where('type', '=', 'file'); // files
        return view('HSEQ.index', ['files' => $contents]); // files
    }

    public function list_folder(Request $request) {
        $dir = $request['folder'];
        $recursive = false; // Get subdirectories also?
        $contents = collect(\Storage::cloud()->listContents($dir, $recursive));

        //return $contents->where('type', '=', 'dir'); // directories
        //return $contents->where('type', '=', 'file'); // files
        return view('HSEQ.index', ['files' => $contents]); // files
    }
}
