<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      $this->truncatetables ([
        'clientes',
        'domicilios',
        'tarjetas',
        'libros',
        'pedidos',
        'editors',
        'libro_pedido'
      ]);
        
      $this->call(ClientesSeeder::class);
      $this->call(DomicilioSeeder::class);
      $this->call(TarjetaSeeder::class);
      $this->call(LibroSeeder::class);
      $this->call(PedidoSeeder::class);
      $this->call(EditorSeeder::class);
      $this->call(LibroPedidoSeeder::class);
    }

    protected function truncatetables(array $tables) // Tablas de areglo que queremos vaciar
    {
        foreach ($tables as $table) {
           DB::table($table)->truncate();
        }
    }
    
}
