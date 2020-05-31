<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LibroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prueba=fopen("public/libros.txt","r") or die ("Error al abrir el archivo");

        while(($datos = fgetcsv($prueba,5000,"|")) !== FALSE)
        {
            DB::table('libros')->insert([
                'nombre' => $datos[0],
                'autor' => $datos[1],
                'editorial' => $datos[2],
                'genero' => $datos[3],
                'idioma' => $datos[4],
                'isbn' => $datos[5],
                'precio' => $datos[6],
                'descripcion' => $datos[7],
                'imagen'=> $datos[8]
            ]);
        }
        fclose($prueba);
    }
}
