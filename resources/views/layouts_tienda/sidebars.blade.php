<div class="col-lg-3">
  <div class="card sidebar-menu mb-4">
    <div class="card-header">
      <h3 class="h4 card-title">Genero</h3>
    </div>
    <div class="card-body force-scroll-estilos">
      <ul class="list-unstyled">
        <!-- foreach para los estilos-->
        @foreach($generos as $genero)
          <li><a href="{{route('tienda.porEstilo', $genero->estilo)}}" class="nav-link">{{$genero->estilo}}</a></li>
        @endforeach
      </ul>
    </div>
  </div>
</div>