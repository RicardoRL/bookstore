<li class="nav-item dropdown menu-large"><a href="#" data-toggle="dropdown" data-hover="dropdown" data-delay="200" class="dropdown-toggle nav-link">Estilos<b class="caret"></b></a>
  <ul class="dropdown-menu megamenu force-scroll">
    <li>
      <div class="row">
        <div class="col-md-6 col-lg-3">
          <ul class="list-unstyled mb-3">
          @for($i = 0; $i < getGeneros(); $i++)
            <li class="nav-item">
              <a href="{{route('tienda.porEstilo', getGeneros()[$i])}}" class="nav-link">
                {{getGeneros()[$i]}}
              </a>
            </li>
          @endfor
          </ul>
        </div>
      </div>
    </li>
  </ul>
</li>