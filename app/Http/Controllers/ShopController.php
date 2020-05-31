<?php

namespace App\Http\Controllers;

use App\Pedido;
use App\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Mostrar los estilos de Libros en el sidebar menu izquierdo
        $generos = getGeneros();
        
        //Crear paginador personalizado
        $productos = Libro::all();
        $set = paginator($request, $productos);

        //Obtener los productos como array
        $productos = $set['paginator']->all();

        return view('layouts_tienda.tienda', compact('generos', 'productos', 'set'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      //Mostrar los estilos de Libros en el sidebar menu izquierdo
      $generos = getGeneros();

      $libro = Libro::where('id', $id)->firstOrFail();

      return view('layouts_tienda.producto', compact('libro', 'generos'));
    }

    public function porCerveceria(Request $request)
    {
      $id = (int)$request->input('id');

      $productos = Libro::whereHas('cerveceria', function($query) use ($id){
        $query->where('id', $id);
      })->get();

      $generos = getGeneros();

      $set = paginator($request, $productos);

      return view('layouts_tienda.tienda', compact('productos', 'generos', 'set'));
    }

    public function porEstilo(Request $request, $genero)
    {
      $generos = getGeneros();

      $productos = Libro::where('genero', $genero)->get()->all();

      $set = paginator($request, $productos);
        
      return view('layouts_tienda.tienda', compact('productos', 'generos', 'set'));
    }

    public function buscar(Request $request)
    {
      $generos = getGeneros();

      $nombre = $request->buscar;
      $productos = Libro::where('nombre', 'LIKE', "%$nombre%")->get()->all();

      $set = paginator($request, $productos);

      return view('layouts_tienda.tienda', compact('productos', 'generos', 'set'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
