<?php

use App\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

function getGeneros()
{
  $generos = (DB::table('libros')->select('genero')
              ->groupBy('genero')->orderBy('genero', 'ASC')->get())->all();
  
  return $generos;
}

function paginator(Request $request, $productos)
{
  $array = (is_array($productos)) ? $productos : $productos->toArray();
  $total = count($productos);
  $per_page = 24;
  $current_page = $request->input("page") ?? 1;
  $starting_point = ($current_page * $per_page) - $per_page;
  $array = array_slice($array, $starting_point, $per_page, true);
  $array = new Paginator($array, $total, $per_page, $current_page, [
      'path' => $request->url(),
      'query' => $request->query(),
  ]);

  //Variables adicionales para personalizar paginación
  $block = $request->input("block") ?? 1;
  $pages_per_block = 5;
  $limit_blocks = (int)ceil($array->lastPage()/$pages_per_block);
  $max_page_of_block = ($block < $limit_blocks) ? ($pages_per_block * $block) : (($array->lastPage() - $current_page) + 1);
  $endFor = ($block < $limit_blocks) ? $max_page_of_block : $array->lastPage();
  if($block == 1){
      $initFor = 1;
  }
  elseif($block < $limit_blocks){
      $initFor = ($max_page_of_block - $pages_per_block) + 1;
  }
  else{
      $initFor = ($array->lastPage() - $max_page_of_block) + 1;
  }

  //Arreglo con todas las variables que necesitamos para mostrar los productos en el blade
  $set = array(
      "paginator" => $array, 
      "limit_blocks" => $limit_blocks,
      "block" => $block,
      "max_page_of_block" => $max_page_of_block, 
      "start" => $initFor,
      "end" => $endFor
  );
  //dd($set);

  return $set;
}

?>