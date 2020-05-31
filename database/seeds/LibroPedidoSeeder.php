<?php


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LibroPedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $file=fopen("public/libro_pedido.txt","r") or die ("Error al abrir el archivo");
      rewind($file);

      while(($datos = fgetcsv($file,1000,"|")) !== FALSE)
      {
        DB::table('libro_pedido')->insert([
          'libro_id' => $datos[0],
          'pedido_id' => $datos[1],
          'cantidad' => $datos[2],
        ]);
      }
      fclose($file);
    }
}
