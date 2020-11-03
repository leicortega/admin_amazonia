<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Models\Blog\Post;
use App\Models\Blog\Media_post;


class BlogController extends Controller
{
    public $date;

    public function __construct() {
        $this->date = Carbon::now('America/Bogota');
    }

    public function index() {
        $posts = Post::with('users')->paginate(10);

        return view('blog.index', ['posts' => $posts]);
    }

    public function crear() {
        return view('blog.crear');
    }

    public function crear_post(Request $request) {
        if ($request->file('imagen')) {
            $extension_file_documento = pathinfo($request->file('imagen')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_documento = 'blog/post/imagenes/';
            $nombre_file_documento = 'imagen_'.$this->date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
            Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('imagen')));

            $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;
        }

        $post = Post::create([
            'fecha' => $this->date->format('Y-m-d'),
            'titulo' => $request['titulo'],
            'slug' => Str::slug($request['titulo']),
            'imagen' => $nombre_completo_file_documento,
            'contenido' => $request['contenido'],
            'users_id' => auth()->user()->id
        ]);

        if ($request->file('input_galeria')) {
            foreach ($request->file('input_galeria') as $num => $imagen) {
                $extension_file_documento = pathinfo($imagen->getClientOriginalName(), PATHINFO_EXTENSION);
                $ruta_file_documento = 'blog/post/galeria/';
                $nombre_file_documento = 'imagen_'.$this->date->isoFormat('YMMDDHmmss').'_'.$num.'.'.$extension_file_documento;
                Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($imagen));

                $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;

                Media_post::create([
                    'imagen' => $nombre_completo_file_documento,
                    'posts_id' => $post->id
                ]);
            }
        }

        return redirect()->route('blog')->with(['create' => 1, 'mensaje' => 'Post creado correctamente']);
    }

    public function ver(Request $request) {
        $post = Post::find($request['id']);

        return view('blog.ver', ['post' => $post]);
    }
}
