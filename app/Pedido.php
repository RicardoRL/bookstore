<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
  use softDeletes;
  public function cliente()
  {
    return $this->belongsTo('App\Cliente');
  }

  public function libros()
  {
    return $this->belongsToMany('App\Libro')->withTimestamps()->withPivot(['cantidad', 'deleted_at']);
  }
}
