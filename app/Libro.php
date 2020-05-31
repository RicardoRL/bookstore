<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
  use softDeletes;

  protected $fillable = ['nombre', 'autor', 'editorial', 'genero', 'idioma', 'isbn', 'precio', 'descripcion'];

  public function pedidos()
  {
    return $this->belongsToMany('App\Pedido')->withTimestamps()->withPivot(['cantidad', 'deleted_at']);
  }
}
