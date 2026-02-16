@extends('layouts.app')

@section('title', 'Panel de administración • Jediga')

@section('content')
@php
  /* ===== Datos mock (solo para maqueta) ===== */
  $productos = [
    ['id'=>101,'nombre'=>'Neon Rift','categoria'=>'Shooter','plataforma'=>'PC','tipo'=>'Juego','precio'=>19.99,'stock'=>42,'estado'=>'Publicado','actualizado'=>'2026-02-10'],
    ['id'=>102,'nombre'=>'Astra Runner','categoria'=>'Acción','plataforma'=>'PS','tipo'=>'Juego','precio'=>49.99,'stock'=>8,'estado'=>'Publicado','actualizado'=>'2026-02-08'],
    ['id'=>103,'nombre'=>'Echoes DLC','categoria'=>'Contenido','plataforma'=>'PC','tipo'=>'DLC','precio'=>9.99,'stock'=>999,'estado'=>'Borrador','actualizado'=>'2026-02-06'],
    ['id'=>104,'nombre'=>'Zero Byte','categoria'=>'Indie','plataforma'=>'Xbox','tipo'=>'Juego','precio'=>0.00,'stock'=>999,'estado'=>'Publicado','actualizado'=>'2026-02-03'],
    ['id'=>105,'nombre'=>'Skyforge Lite','categoria'=>'Aventura','plataforma'=>'Switch','tipo'=>'Juego','precio'=>29.99,'stock'=>0,'estado'=>'Oculto','actualizado'=>'2026-01-30'],
  ];

  $categorias = [
    ['id'=>1,'nombre'=>'Acción','slug'=>'accion','productos'=>12,'actualizado'=>'2026-02-01'],
    ['id'=>2,'nombre'=>'Shooter','slug'=>'shooter','productos'=>7,'actualizado'=>'2026-01-26'],
    ['id'=>3,'nombre'=>'Aventura','slug'=>'aventura','productos'=>9,'actualizado'=>'2026-01-15'],
    ['id'=>4,'nombre'=>'Indie','slug'=>'indie','productos'=>18,'actualizado'=>'2026-02-09'],
  ];

  $usuarios = [
    ['id'=>1,'nombre'=>'Admin','email'=>'admin@demo.com','rol'=>'Admin','estado'=>'Activo','alta'=>'2026-01-12'],
    ['id'=>2,'nombre'=>'Cliente demo','email'=>'cliente@demo.com','rol'=>'Cliente','estado'=>'Activo','alta'=>'2026-02-01'],
    ['id'=>3,'nombre'=>'Gestor demo','email'=>'gestor@demo.com','rol'=>'Gestor','estado'=>'Suspendido','alta'=>'2026-01-20'],
  ];

  $pedidos = [
    ['id'=>5001,'usuario'=>'cliente@demo.com','total'=>59.98,'estado'=>'Pagado','fecha'=>'2026-02-10'],
    ['id'=>5002,'usuario'=>'cliente@demo.com','total'=>0.00,'estado'=>'Pendiente','fecha'=>'2026-02-09'],
    ['id'=>5003,'usuario'=>'cliente@demo.com','total'=>19.99,'estado'=>'Reembolsado','fecha'=>'2026-02-05'],
  ];

  $totalProductos = count($productos);
  $bajoStock = collect($productos)->filter(fn($p) => $p['stock'] > 0 && $p['stock'] <= 10)->count();
  $sinStock = collect($productos)->filter(fn($p) => $p['stock'] === 0)->count();
  $borradores = collect($productos)->filter(fn($p) => $p['estado'] === 'Borrador')->count();

  $fmt = fn($n) => number_format($n, 2, ',', '.');
@endphp

<div class="jg-admin jg-admin-wrap">
  <div class="container">

    {{-- HEADER --}}
    <div class="jg-admin-header p-4 mb-4">
      <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>
          <div class="jg-pill mb-2">
            <span class="jg-dot"></span>
            <span>Panel de administración</span>
          </div>

          <h1 class="jg-section-title h3 mb-2" style="display:block;">Administración</h1>
          <div class="jg-muted">Gestión de productos, categorías y más (vista provisional).</div>

          <div class="mt-3 d-flex flex-wrap gap-2">
            <span class="jg-chip"><i class="bi bi-shield-lock"></i> Solo admin</span>
            <span class="jg-chip"><i class="bi bi-sliders"></i> Filtros y ordenación</span>
            <span class="jg-chip"><i class="bi bi-table"></i> CRUD (maqueta)</span>
          </div>
        </div>

        <div class="d-flex gap-2 align-items-center">
          <button type="button" class="btn jg-btn jg-btn-primary" disabled title="Aún sin backend">
            <i class="bi bi-plus-circle me-1"></i> Nuevo producto
          </button>
        </div>
        
        <div class="jg-admin-note mt-3 small">
          Nota: esto es <strong>solo UI</strong>. Los botones están como “placeholder” para cuando conectéis BD + controladores.
        </div>
      </div>
    </div>

    {{-- KPIs --}}
    <div class="row g-3 mb-4">
      <div class="col-12 col-md-6 col-lg-3">
        <div class="jg-kpi p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="small jg-muted">Productos</div>
              <div class="h3 mb-0">{{ $totalProductos }}</div>
            </div>
            <span class="badge badge-soft"><i class="bi bi-box-seam me-1"></i> Total</span>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-3">
        <div class="jg-kpi p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="small jg-muted">Bajo stock</div>
              <div class="h3 mb-0">{{ $bajoStock }}</div>
            </div>
            <span class="badge badge-sun"><i class="bi bi-exclamation-triangle me-1"></i> Atención</span>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-3">
        <div class="jg-kpi p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="small jg-muted">Sin stock</div>
              <div class="h3 mb-0">{{ $sinStock }}</div>
            </div>
            <span class="badge badge-soft"><i class="bi bi-x-circle me-1"></i> Agotado</span>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-3">
        <div class="jg-kpi p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="small jg-muted">Borradores</div>
              <div class="h3 mb-0">{{ $borradores }}</div>
            </div>
            <span class="badge badge-mint"><i class="bi bi-pencil-square me-1"></i> Draft</span>
          </div>
        </div>
      </div>
    </div>

    {{-- TABS --}}
    <ul class="nav jg-admin-tabs gap-2 mb-3" id="adminTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tab-productos" data-bs-toggle="tab" data-bs-target="#productos" type="button" role="tab">
          <i class="bi bi-box-seam me-1"></i> Productos
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-categorias" data-bs-toggle="tab" data-bs-target="#categorias" type="button" role="tab">
          <i class="bi bi-tags me-1"></i> Categorías
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-usuarios" data-bs-toggle="tab" data-bs-target="#usuarios" type="button" role="tab">
          <i class="bi bi-people me-1"></i> Usuarios
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-pedidos" data-bs-toggle="tab" data-bs-target="#pedidos" type="button" role="tab">
          <i class="bi bi-receipt me-1"></i> Pedidos
        </button>
      </li>
    </ul>

    <div class="tab-content">

      {{-- ===================== PRODUCTOS ===================== --}}
      <div class="tab-pane fade show active" id="productos" role="tabpanel" aria-labelledby="tab-productos">

        {{-- FILTROS (solo UI) --}}
        <div class="jg-filterbar mb-3">
          <div class="row g-2 align-items-end">
            <div class="col-12 col-lg-4">
              <label class="form-label mb-1">Buscar</label>
              <div class="input-group">
                <span class="input-group-text" style="background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.12); color:#fff;">
                  <i class="bi bi-search"></i>
                </span>
                <input id="adminSearchProductos" class="form-control" type="text" placeholder="Nombre, categoría, plataforma…">
              </div>
            </div>

            <div class="col-6 col-lg-2">
              <label class="form-label mb-1">Estado</label>
              <select id="adminEstadoProductos" class="form-select">
                <option value="">Todos</option>
                <option>Publicado</option>
                <option>Borrador</option>
                <option>Oculto</option>
              </select>
            </div>

            <div class="col-6 col-lg-2">
              <label class="form-label mb-1">Ordenar</label>
              <select id="adminOrdenProductos" class="form-select">
                <option value="nombre_asc">Nombre (A-Z)</option>
                <option value="nombre_desc">Nombre (Z-A)</option>
                <option value="precio_asc">Precio (menor)</option>
                <option value="precio_desc">Precio (mayor)</option>
                <option value="stock_asc">Stock (menor)</option>
                <option value="stock_desc">Stock (mayor)</option>
                <option value="fecha_desc">Actualizado (reciente)</option>
                <option value="fecha_asc">Actualizado (antiguo)</option>
              </select>
            </div>

            <div class="col-12 col-lg-4 d-flex gap-2 justify-content-lg-end">
              <button class="btn jg-btn jg-btn-outline" type="button" disabled title="Placeholder">
                <i class="bi bi-download me-1"></i> Exportar
              </button>
              <button class="btn jg-btn jg-btn-outline" type="button" disabled title="Placeholder">
                <i class="bi bi-funnel me-1"></i> Filtro avanzado
              </button>
              <button class="btn jg-btn jg-btn-primary" type="button" disabled title="Placeholder">
                <i class="bi bi-plus-circle me-1"></i> Añadir
              </button>
            </div>
          </div>
        </div>

        {{-- TABLA --}}
        <div class="jg-table-wrap mb-3">
          <div class="table-responsive">
            <table class="table jg-table align-middle" id="tablaProductos">
              <thead>
                <tr>
                  <th style="width:48px;">
                    <input class="form-check-input" type="checkbox" disabled>
                  </th>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Categoría</th>
                  <th>Plataforma</th>
                  <th>Tipo</th>
                  <th class="text-end">Precio</th>
                  <th class="text-end">Stock</th>
                  <th>Estado</th>
                  <th>Actualizado</th>
                  <th class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($productos as $p)
                  @php
                    $badgeEstado = $p['estado']==='Publicado' ? 'badge-mint' : ($p['estado']==='Borrador' ? 'badge-soft' : 'badge-sun');
                    $stockWarn = $p['stock']===0 ? 'badge-sun' : ($p['stock']<=10 ? 'badge-sun' : 'badge-soft');
                  @endphp
                  <tr data-nombre="{{ strtolower($p['nombre']) }}"
                      data-categoria="{{ strtolower($p['categoria']) }}"
                      data-plataforma="{{ strtolower($p['plataforma']) }}"
                      data-estado="{{ strtolower($p['estado']) }}"
                      data-precio="{{ $p['precio'] }}"
                      data-stock="{{ $p['stock'] }}"
                      data-fecha="{{ $p['actualizado'] }}">
                    <td><input class="form-check-input" type="checkbox" disabled></td>
                    <td class="text-nowrap">#{{ $p['id'] }}</td>
                    <td class="fw-bold">{{ $p['nombre'] }}</td>
                    <td>{{ $p['categoria'] }}</td>
                    <td><span class="badge badge-soft">{{ $p['plataforma'] }}</span></td>
                    <td>{{ $p['tipo'] }}</td>
                    <td class="text-end">{{ $fmt($p['precio']) }} €</td>
                    <td class="text-end">
                      <span class="badge {{ $stockWarn }}">
                        {{ $p['stock'] }}
                      </span>
                    </td>
                    <td><span class="badge {{ $badgeEstado }}">{{ $p['estado'] }}</span></td>
                    <td class="text-nowrap">{{ $p['actualizado'] }}</td>
                    <td class="text-end jg-actions">
                      <button class="btn btn-sm jg-btn jg-btn-outline" disabled><i class="bi bi-eye"></i></button>
                      <button class="btn btn-sm jg-btn jg-btn-outline" disabled><i class="bi bi-pencil"></i></button>
                      <button class="btn btn-sm jg-btn jg-btn-outline" disabled><i class="bi bi-trash"></i></button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        {{-- PAGINACIÓN (placeholder) --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
          <div class="jg-muted small">Mostrando 1–{{ count($productos) }} de {{ count($productos) }} (mock)</div>
          <nav aria-label="Paginación" class="ms-auto">
            <ul class="pagination mb-0">
              <li class="page-item disabled"><a class="page-link" href="#">«</a></li>
              <li class="page-item active"><a class="page-link" href="#">1</a></li>
              <li class="page-item disabled"><a class="page-link" href="#">»</a></li>
            </ul>
          </nav>
        </div>
      </div>

      {{-- ===================== CATEGORÍAS ===================== --}}
      <div class="tab-pane fade" id="categorias" role="tabpanel" aria-labelledby="tab-categorias">
        <div class="jg-card p-3 mb-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="h4 mb-1">Categorías</div>
              <div class="jg-muted">Estructura del catálogo (maqueta).</div>
            </div>
            <button class="btn jg-btn jg-btn-primary" disabled><i class="bi bi-plus-circle me-1"></i> Nueva categoría</button>
          </div>
        </div>

        <div class="jg-table-wrap">
          <div class="table-responsive">
            <table class="table jg-table align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Slug</th>
                  <th class="text-end">Productos</th>
                  <th>Actualizado</th>
                  <th class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($categorias as $c)
                  <tr>
                    <td>#{{ $c['id'] }}</td>
                    <td class="fw-bold">{{ $c['nombre'] }}</td>
                    <td><span class="badge badge-soft">{{ $c['slug'] }}</span></td>
                    <td class="text-end">{{ $c['productos'] }}</td>
                    <td class="text-nowrap">{{ $c['actualizado'] }}</td>
                    <td class="text-end">
                      <button class="btn btn-sm jg-btn jg-btn-outline" disabled><i class="bi bi-pencil"></i></button>
                      <button class="btn btn-sm jg-btn jg-btn-outline" disabled><i class="bi bi-trash"></i></button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {{-- ===================== USUARIOS ===================== --}}
      <div class="tab-pane fade" id="usuarios" role="tabpanel" aria-labelledby="tab-usuarios">
        <div class="jg-card p-3 mb-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="h4 mb-1">Usuarios</div>
              <div class="jg-muted">Gestión de roles y estado (maqueta).</div>
            </div>
            <button class="btn jg-btn jg-btn-outline" disabled><i class="bi bi-person-plus me-1"></i> Invitar</button>
          </div>
        </div>

        <div class="jg-table-wrap">
          <div class="table-responsive">
            <table class="table jg-table align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Email</th>
                  <th>Rol</th>
                  <th>Estado</th>
                  <th>Alta</th>
                  <th class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($usuarios as $u)
                  @php
                    $bRol = $u['rol']==='Admin' ? 'badge-sun' : ($u['rol']==='Gestor' ? 'badge-soft' : 'badge-mint');
                    $bEst = $u['estado']==='Activo' ? 'badge-mint' : 'badge-sun';
                  @endphp
                  <tr>
                    <td>#{{ $u['id'] }}</td>
                    <td class="fw-bold">{{ $u['nombre'] }}</td>
                    <td>{{ $u['email'] }}</td>
                    <td><span class="badge {{ $bRol }}">{{ $u['rol'] }}</span></td>
                    <td><span class="badge {{ $bEst }}">{{ $u['estado'] }}</span></td>
                    <td class="text-nowrap">{{ $u['alta'] }}</td>
                    <td class="text-end">
                      <button class="btn btn-sm jg-btn jg-btn-outline" disabled><i class="bi bi-pencil"></i></button>
                      <button class="btn btn-sm jg-btn jg-btn-outline" disabled><i class="bi bi-slash-circle"></i></button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {{-- ===================== PEDIDOS ===================== --}}
      <div class="tab-pane fade" id="pedidos" role="tabpanel" aria-labelledby="tab-pedidos">
        <div class="jg-card p-3 mb-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="h4 mb-1">Pedidos</div>
              <div class="jg-muted">Histórico y estados (maqueta).</div>
            </div>
            <button class="btn jg-btn jg-btn-outline" disabled><i class="bi bi-download me-1"></i> Exportar</button>
          </div>
        </div>

        <div class="jg-table-wrap">
          <div class="table-responsive">
            <table class="table jg-table align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Usuario</th>
                  <th class="text-end">Total</th>
                  <th>Estado</th>
                  <th>Fecha</th>
                  <th class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($pedidos as $p)
                  @php
                    $b = $p['estado']==='Pagado' ? 'badge-mint' : ($p['estado']==='Pendiente' ? 'badge-soft' : 'badge-sun');
                  @endphp
                  <tr>
                    <td>#{{ $p['id'] }}</td>
                    <td>{{ $p['usuario'] }}</td>
                    <td class="text-end">{{ $fmt($p['total']) }} €</td>
                    <td><span class="badge {{ $b }}">{{ $p['estado'] }}</span></td>
                    <td class="text-nowrap">{{ $p['fecha'] }}</td>
                    <td class="text-end">
                      <button class="btn btn-sm jg-btn jg-btn-outline" disabled><i class="bi bi-eye"></i></button>
                      <button class="btn btn-sm jg-btn jg-btn-outline" disabled><i class="bi bi-arrow-repeat"></i></button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

@push('scripts')
<script>
  // Filtro básico front
  document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('adminSearchProductos');
    const estado = document.getElementById('adminEstadoProductos');
    const orden = document.getElementById('adminOrdenProductos');
    const tabla = document.getElementById('tablaProductos');
    
    if (!input || !estado || !orden || !tabla){
      return;
    }

    const tbody = tabla.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    function aplicar(){
      const q = (input.value || '').trim().toLowerCase();
      const est = (estado.value || '').trim().toLowerCase();

      // filtrar
      let visibles = rows.filter(r => {
        const nombre = r.dataset.nombre || '';
        const cat = r.dataset.categoria || '';
        const plat = r.dataset.plataforma || '';
        const estRow = r.dataset.estado || '';
        const matchQ = !q || (nombre.includes(q) || cat.includes(q) || plat.includes(q));
        const matchE = !est || estRow === est.toLowerCase();
        return matchQ && matchE;
      });

      // ordenar
      const k = orden.value;
      visibles.sort((a,b) => {
        const A = a.dataset, B = b.dataset;

        const cmpText = (x,y)=> x.localeCompare(y, 'es', { sensitivity:'base' });
        const cmpNum = (x,y)=> (parseFloat(x) || 0) - (parseFloat(y) || 0);

        if (k === 'nombre_asc') return cmpText(A.nombre, B.nombre);
        if (k === 'nombre_desc') return cmpText(B.nombre, A.nombre);
        if (k === 'precio_asc') return cmpNum(A.precio, B.precio);
        if (k === 'precio_desc') return cmpNum(B.precio, A.precio);
        if (k === 'stock_asc') return cmpNum(A.stock, B.stock);
        if (k === 'stock_desc') return cmpNum(B.stock, A.stock);
        if (k === 'fecha_asc') return cmpText(A.fecha, B.fecha);
        if (k === 'fecha_desc') return cmpText(B.fecha, A.fecha);
        return 0;
      });

      // pintar
      tbody.innerHTML = '';
      visibles.forEach(r => tbody.appendChild(r));
    }

    input.addEventListener('input', aplicar);
    estado.addEventListener('change', aplicar);
    orden.addEventListener('change', aplicar);
  });
</script>
@endpush

@endsection
