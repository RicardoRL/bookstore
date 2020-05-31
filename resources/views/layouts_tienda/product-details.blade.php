<div class="col-lg-9 order-1 order-lg-2">
    <div id="productMain" class="row">
        <div class="col-md-6">
            <div class="item">
                <img src="/img/imagenes_libros/{{$libro->imagen}}.jpg" alt="" class="img-fluid">
            </div>
        </div>
        <div class="col-md-10">
            <div class="box">
                <div class="card">
                    <div class="card-header">
                        <h4>{{$libro->nombre}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <tbody>
                                    <tr>
                                        <th scope="row">Autor</th>
                                        <td>{{$libro->autor}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Género</th>
                                        @if($libro->genero != 'NULL')
                                          <td>{{$libro->genero}}</td>
                                        @else
                                          <td>-</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th scope="row">Editorial</th>
                                        @if($libro->editorial != 'NULL')
                                          <td>{{$libro->editorial}}</td>
                                        @else
                                          <td>-</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th scope="row">Idioma</th>
                                        @if($libro->idioma != 'NULL')
                                          <td>{{$libro->idioma}}</td>
                                        @else
                                          <td>-</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th scope="row">ISBN</th>
                                        @if($libro->isbn != 'NULL')
                                          <td>{{$libro->isbn}}</td>
                                        @else
                                          <td>-</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th scope="row">Descripción</th>
                                        @if($libro->descripcion != 'NULL')
                                          <td>{{$libro->descripcion}}</td>
                                        @else
                                          <td>-</td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <p class="price">${{$libro->precio}}</p>
                @auth
                    <form action="{{route('cart.store')}}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{$libro->id}}">
                        <input type="hidden" name="name" value="{{$libro->nombre}}">
                        <input type="hidden" name="price" value="{{$libro->precio}}">
                        <p class="text-center buttons">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-shopping-cart"></i> Agregar al carro
                            </button>
                        </p>
                    </form>
                @endauth
                <hr>
                <div class="social">
                  <h4>Compártelo con tus amigos</h4>
                  <p>
                      <a href="#" class="external facebook">
                          <i class="fa fa-facebook"></i>
                        </a>
                        <a href="#" class="external gplus">
                            <i class="fa fa-google-plus"></i>
                        </a>
                        <a href="#" class="external twitter">
                            <i class="fa fa-twitter"></i>
                        </a>
                        <a href="#" class="email">
                            <i class="fa fa-envelope"></i>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>