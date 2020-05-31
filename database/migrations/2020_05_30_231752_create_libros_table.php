<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLibrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('libros', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('nombre');
          $table->string('autor');
          $table->string('editorial');
          $table->string('genero');
          $table->string('idioma');
          $table->string('isbn');
          $table->decimal('precio', 11, 2);
          $table->text('descripcion');
          $table->softDeletes();
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('libros');
    }
}
