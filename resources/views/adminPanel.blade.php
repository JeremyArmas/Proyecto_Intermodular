@extends('layouts.app')

@section('title', 'Panel de administración • Jediga')

@section('content')
@php
  // Esta vista empezó como maqueta con datos fijos. el controlador
  // inyecta colecciones reales ($productos, $categorias, $usuarios,
  // $pedidos) y ya no hace falta sobrescribirlas aquí. retirar este
  // bloque y adaptar el resto de la plantilla si lo vas a mantener.

  // Si mantienes el `AdminController@index` activo, puedes usar los
  // KPI que te pasa desde allá; en cualquier caso se calcularán mejor
  // en el controlador o usando métodos de colección.

  // $fmt sirve para formatear precios, se puede seguir usando.
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

        <div class="ms-auto">
          <a href="{{ route('admin.games.create') }}" class="btn jg-btn jg-btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Añadir Juego
          </a>
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
                  <th>Título</th>
                  <th>Categorías</th>
                  <th>Plataforma</th>
                  <th class="text-end">Precio Base</th>
                  <th class="text-end">Stock</th>
                  <th>Estado</th>
                  <th>Actualizado</th>
                  <th class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($productos as $p)
                  @php
                    // Lógica para etiquetas visuales según los datos de la bbdd
                    $estadoStr = $p->is_active ? 'Publicado' : 'Borrador';
                    $badgeEstado = $p->is_active ? 'badge-mint' : 'badge-soft';
                    $stockWarn = $p->stock === 0 ? 'badge-sun' : ($p->stock <= 10 ? 'badge-sun' : 'badge-soft');
                    $categoriasStr = $p->categories->pluck('name')->join(', ');
                  @endphp
                  {{-- Atributos data-* usados por nuestro JS local para ordenar y buscar --}}
                  <tr data-nombre="{{ strtolower($p->title) }}"
                      data-categoria="{{ strtolower($categoriasStr) }}"
                      data-plataforma="{{ strtolower($p->platform->name ?? '') }}"
                      data-estado="{{ strtolower($estadoStr) }}"
                      data-precio="{{ $p->price }}"
                      data-stock="{{ $p->stock }}"
                      data-fecha="{{ $p->updated_at }}">
                    
                    <td>
                        @if($p->cover_image)
                            <img src="{{ asset('storage/' . $p->cover_image) }}" alt="Img" style="width: 32px; height: 32px; object-fit: cover; border-radius: 4px;">
                        @else
                            <div style="width: 32px; height: 32px; border-radius: 4px; background: rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:center;">
                                <i class="bi bi-joystick text-white-50"></i>
                            </div>
                        @endif
                    </td>
                    <td class="text-nowrap">#{{ $p->id }}</td>
                    <td class="fw-bold">{{ $p->title }}</td>
                    <td>{{ $categoriasStr ?: 'Sin categorizar' }}</td>
                    <td><span class="badge badge-soft">{{ $p->platform->name ?? 'N/A' }}</span></td>
                    <td class="text-end fw-bold" style="color: var(--jg-mint);">{{ $fmt($p->price) }} €</td>
                    <td class="text-end">
                      <span class="badge {{ $stockWarn }}">
                        {{ $p->stock }}
                      </span>
                    </td>
                    <td><span class="badge {{ $badgeEstado }}">{{ $estadoStr }}</span></td>
                    <td class="text-nowrap">{{ $p->updated_at->format('Y-m-d') }}</td>
                    <td class="text-end jg-actions">
                      <a href="{{ route('admin.games.edit', $p) }}" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-pencil"></i></a>
                      <form action="{{ route('admin.games.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-trash"></i></button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        {{-- PAGINACIÓN (placeholder) --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
          <div class="jg-muted small">Mostrando {{ $productos->count() }} de {{ $totalProductos ?? $productos->count() }}</div>
          {{-- si se implementa paginación, sustituir por links reales --}}
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
              <div class="jg-muted">Estructura del catálogo.</div>
            </div>
            <div class="justify-content-end d-flex gap-2">
              <a href="{{ route('admin.categories.index') }}" class="btn jg-btn jg-btn-sun">
                <i class="bi bi-eye me-1"></i> Ver todas las categorías
              </a>
              <a href="{{ route('admin.categories.create') }}" class="btn jg-btn jg-btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Añadir categoría
              </a>
            </div>
            
              
          </div>
        </div>

        <div class="jg-table-wrap">
          <div class="table-responsive">
            <table class="table jg-table align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre Genérico</th>
                  <th>Identificador (Slug)</th>
                  <th class="text-end">Juegos Asignados</th>
                  <th>Fecha de Creación</th>
                  <th class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @forelse($categorias as $c)
                  <tr>
                    <td>#{{ $c->id }}</td>
                    <td class="fw-bold">{{ $c->name }}</td>
                    <td><span class="badge badge-soft">{{ $c->slug }}</span></td>
                    <td class="text-end">{{ $c->games_count }}</td>
                    <td class="text-nowrap">{{ $c->created_at->format('Y-m-d') }}</td>
                    <td class="text-end">
                      <a href="{{ route('admin.categories.edit', $c) }}" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-pencil"></i></a>
                      <form action="{{ route('admin.categories.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-trash"></i></button>
                    </td>
                  </tr>
                @empty
                 <tr><td colspan="6" class="text-center py-4 jg-muted">No hay categorías.</td></tr>
                @endforelse
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
              <div class="jg-muted">Gestión de roles y estado.</div>
            </div>
            <div class="justify-content-end d-flex gap-2">
              <a href="{{ route('admin.users.index') }}" class="btn jg-btn jg-btn-sun">
                <i class="bi bi-eye me-1"></i> Ver todos los usuarios
              </a>
              <a href="{{ route('admin.categories.create') }}" class="btn jg-btn jg-btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Invitar
              </a>
            </div>
          </div>
        </div>

        <div class="jg-table-wrap">
          <div class="table-responsive">
            <table class="table jg-table align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre y Apellidos</th>
                  <th>Correo Electrónico</th>
                  <th>Rol Asignado</th>
                  <th>Fecha de Registro</th>
                  <th class="text-end">Acción Rápida</th>
                </tr>
              </thead>
              <tbody>
                @forelse($usuarios as $u)
                  @php
                    $bRol = $u->role === 'admin' ? 'badge-sun' : ($u->role === 'company' ? 'badge-soft' : 'badge-mint');
                  @endphp
                  <tr>
                    <td>#{{ $u->id }}</td>
                    <td class="fw-bold">{{ $u->name }}</td>
                    <td><a href="mailto:{{ $u->email }}" class="text-white text-decoration-none">{{ $u->email }}</a></td>
                    <td><span class="badge {{ $bRol }}">{{ ucfirst($u->role) }}</span></td>
                    <td class="text-nowrap">{{ $u->created_at->format('Y-m-d H:i') }}</td>
                    <td class="text-end">
                      <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-pencil"></i></a>
                      <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="6" class="text-center py-4 jg-muted">No hay usuarios registrados.</td></tr>
                @endforelse
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
              <div class="jg-muted">Histórico y estados.</div>
            </div>
            <div class="justify-content-end d-flex gap-2">
              <a href="{{ route('admin.orders.index') }}" class="btn jg-btn jg-btn-sun">
                <i class="bi bi-eye me-1"></i> Ver todos los pedidos
              </a>
              <a href="{{ route('admin.panel') }}" class="btn jg-btn jg-btn-sun disabled">
                <i class="bi bi-download me-1"></i> Exportar
              </a>
            </div>
            
          </div>
        </div>

        <div class="jg-table-wrap">
          <div class="table-responsive">
            <table class="table jg-table align-middle">
              <thead>
                <tr>
                  <th>Referencia</th>
                  <th>Datos del Comprador</th>
                  <th>Tipo (B2B/B2C)</th>
                  <th class="text-end">Total Cobrado</th>
                  <th>Estado del Envío</th>
                  <th>Fecha y Hora</th>
                  <th class="text-end">Auditar</th>
                </tr>
              </thead>
              <tbody>
                @forelse($pedidos as $p)
                  @php
                    $bOrder = $p->status === 'paid' ? 'badge-primary' : ($p->status === 'shipped' ? 'badge-mint' : ($p->status === 'cancelled' ? 'badge-sun' : 'badge-soft'));
                    $st = ['pending'=>'Pendiente','paid'=>'Pagado','shipped'=>'Enviado','cancelled'=>'Cancelado'];
                  @endphp
                  <tr>
                    <td><span class="fw-bold">#{{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</span></td>
                    <td>
                        {{ $p->user->name ?? 'Usuario borrado' }}<br>
                        <small class="jg-muted">{{ $p->user->email ?? '--' }}</small>
                    </td>
                    <td><span class="badge badge-soft">{{ strtoupper($p->order_type) }}</span></td>
                    <td class="text-end fw-bold" style="color: var(--jg-mint);">{{ $fmt($p->total_amount) }} €</td>
                    <td><span class="badge {{ $bOrder }}">{{ $st[$p->status] ?? $p->status }}</span></td>
                    <td class="text-nowrap">{{ $p->created_at->format('Y-m-d H:i') }}</td>
                    <td class="text-end">
                      <a href="{{ route('admin.orders.show', $p) }}" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-eye"></i></a>
                      <form action="{{ route('admin.orders.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este pedido?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center py-4 jg-muted">No existen transacciones recientes.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
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
