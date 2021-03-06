@extends('layouts_editor.editorMenu')
@section('content')

<html>
<body>
<link href="{{ asset('css/font-awesome/all.css') }}" rel="stylesheet">
    <!-- Todos los evento -->
    <div class="breadcrumb-holder">
        <div class="container-fluid">
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="/editor">Inicio</a></li>
            <li class="breadcrumb-item active">Actualizar </li>
          </ul>
        </div>
      </div>


      <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <h4>Cervecerias</h4>
                  <nav class="float-right">
                    <form class="form-inline" action="{{route('cerveceria.scopeDelete')}}" method="POST">
                    @csrf
                      <input name="buscar" class="form-control mr-sm-2" type="search" placeholder="Nombre Cerveceria" aria-label="Search">
                      <button type="submit" class="btn btn-outline-success btn-sm">Buscar</button>
                      </form>
                  </nav>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th> ID </th>
                          <th> NOMBRE </th>
                          <th> CIUDAD </th>
                          <th> SITIO WEB </th>
                          <th> CONTACTO </th>
                      {{--<th> IMAGEN </th>--}}
                          <th>  </th>
                      </tr>
                      </thead>
                      <tbody>
                      @foreach($cerveceria as $micerveceria)
                      <tr>
                          <td> {{$micerveceria->id}}</td>
                          <td> {{$micerveceria->nombre}} </td>
                          <td> {{$micerveceria->ciudad}} </td>
                          <td> {{$micerveceria->sitio_web}} </td>
                          <td> {{$micerveceria->contacto}} </td> 
                    {{--  <td> {{$micerveceria->imagen}}</td> --}}
                          <td>
                              <a
                                 href="{{route('cerveceria.show',$micerveceria->id)}}" >
                               <i class="fas fa-info-circle"></i>
                               </a>
                              
                          </td>
                      </tr>
                          @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

  </body>
</html>

@endsection



