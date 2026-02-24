@extends('layouts.app')

@section('title', 'Panel de administración • Jediga')

@section('content')
@php
  // Función de ayuda para formatear precios en Blade
  $fmt = fn($n) => number_format($n, 2, ',', '.');
@endphp

<div class="jg-admin jg-admin-wrap">
  <div class="container">

    {{-- ENCABEZADO --}}
    <div class="jg-admin-header p-4 mb-4">
      <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>
          <div class="jg-pill mb-2">
            <span class="jg-dot"></span>
            <span>Panel de administración</span>
          </div>

          <h1 class="jg-section-title h3 mb-2" style="display:block;">Administración Central</h1>
          <div class="jg-muted">Gestión de base de datos en tiempo real.</div>

          <div class="mt-3 d-flex flex-wrap gap-2">
            <span class="jg-chip"><i class="bi bi-shield-lock"></i> Solo admin</span>
            <span class="jg-chip"><i class="bi bi-sliders"></i> Filtros interactivos</span>
            <span class="jg-chip"><i class="bi bi-database"></i> Datos en vivo</span>
          </div>
        </div>

        <div class="d-flex gap-2 align-items-center">
          <a href="{{ route('admin.games.create') }}" class="btn jg-btn jg-btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Añadir Juego
          </a>
        </div>
      </div>
    </div>

    {{-- ESTADÍSTICAS GLOBALES (KPIs reales desde BBDD) --}}
    <div class="row g-3 mb-4">
      {{-- KPI: Total de Juegos --}}
      <div class="col-12 col-md-6 col-lg-3">
        <div class="jg-kpi p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="small jg-muted">Juegos Registrados</div>
              <div class="h3 mb-0">{{ $totalProductos }}</div>
            </div>
            <span class="badge badge-soft"><i class="bi bi-box-seam me-1"></i> Total</span>
          </div>
        </div>
      </div>

      {{-- KPI: Juegos con bajo stock --}}
      <div class="col-12 col-md-6 col-lg-3">
        <div class="jg-kpi p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="small jg-muted">Bajo stock (≤ 10)</div>
              <div class="h3 mb-0">{{ $bajoStock }}</div>
            </div>
            <span class="badge badge-sun"><i class="bi bi-exclamation-triangle me-1"></i> Atención</span>
          </div>
        </div>
      </div>

      {{-- KPI: Juegos agotados --}}
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

      {{-- KPI: Juegos inactivos (borradores) --}}
      <div class="col-12 col-md-6 col-lg-3">
        <div class="jg-kpi p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="small jg-muted">Ocultos / Borradores</div>
              <div class="h3 mb-0">{{ $borradores }}</div>
            </div>
            <span class="badge badge-mint"><i class="bi bi-pencil-square me-1"></i> Draft</span>
          </div>
        </div>
      </div>
    </div>

    {{-- PESTAÑAS DE NAVEGACIÓN Y RESÚMENES --}}
    <ul class="nav jg-admin-tabs gap-2 mb-3" id="adminTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tab-productos" data-bs-toggle="tab" data-bs-target="#productos" type="button" role="tab">
          <i class="bi bi-controller me-1"></i> Últimos Juegos
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-categorias" data-bs-toggle="tab" data-bs-target="#categorias" type="button" role="tab">
          <i class="bi bi-tags me-1"></i> Top Categorías
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-usuarios" data-bs-toggle="tab" data-bs-target="#usuarios" type="button" role="tab">
          <i class="bi bi-people me-1"></i> Nuevos Usuarios
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-pedidos" data-bs-toggle="tab" data-bs-target="#pedidos" type="button" role="tab">
          <i class="bi bi-receipt me-1"></i> Recientes Pedidos
        </button>
      </li>
    </ul>

    <div class="tab-content">
      {{-- ===================== PESTAÑA DE JUEGOS ===================== --}}
      <div class="tab-pane fade show active" id="productos" role="tabpanel" aria-labelledby="tab-productos">

        {{-- BARRA DE FILTROS EN TIEMPO REAL --}}
        <div class="jg-filterbar mb-3">
          <div class="row g-2 align-items-end">
            <div class="col-12 col-lg-4">
              <label class="form-label mb-1">Buscar Juego</label>
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
              </select>
            </div>

            <div class="col-6 col-lg-2">
              <label class="form-label mb-1">Ordenar</label>
              <select id="adminOrdenProductos" class="form-select">
                <option value="fecha_desc">Actualizado (reciente)</option>
                <option value="fecha_asc">Actualizado (antiguo)</option>
                <option value="nombre_asc">Nombre (A-Z)</option>
                <option value="nombre_desc">Nombre (Z-A)</option>
                <option value="precio_desc">Precio (mayor a menor)</option>
                <option value="stock_desc">Stock (mayor a menor)</option>
              </select>
            </div>

            <div class="col-12 col-lg-4 d-flex gap-2 justify-content-lg-end">
              <a href="{{ route('admin.games.index') }}" class="btn jg-btn jg-btn-outline" title="Ir al CRUD completo">
                <i class="bi bi-table me-1"></i> Ver Catálogo Completo
              </a>
            </div>
          </div>
        </div>

        {{-- TABLA DE ÚLTIMOS 10 JUEGOS --}}
        <div class="jg-table-wrap mb-3">
          <div class="table-responsive">
            <table class="table jg-table align-middle" id="tablaProductos">
              <thead>
                <tr>
                  <th style="width:48px;">
                    <i class="bi bi-image"></i>
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
                @forelse($productos as $p)
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
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="10" class="text-center py-4 jg-muted">No hay juegos en la base de datos.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {{-- ===================== PESTAÑA CATEGORÍAS ===================== --}}
      <div class="tab-pane fade" id="categorias" role="tabpanel" aria-labelledby="tab-categorias">
        <div class="jg-card p-3 mb-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="h4 mb-1">Top Categorías</div>
              <div class="jg-muted">Listado rápido de géneros con más popularidad.</div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.categories.index') }}" class="btn jg-btn jg-btn-outline"><i class="bi bi-table me-1"></i> Ver Todas</a>
                <a href="{{ route('admin.categories.create') }}" class="btn jg-btn jg-btn-primary"><i class="bi bi-plus-circle me-1"></i> Nueva</a>
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

      {{-- ===================== PESTAÑA USUARIOS ===================== --}}
      <div class="tab-pane fade" id="usuarios" role="tabpanel" aria-labelledby="tab-usuarios">
        <div class="jg-card p-3 mb-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="h4 mb-1">Últimos Registros</div>
              <div class="jg-muted">Nuevas cuentas de clientes y empresas.</div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.users.index') }}" class="btn jg-btn jg-btn-outline"><i class="bi bi-table me-1"></i> Ver Todos</a>
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

      {{-- ===================== PESTAÑA PEDIDOS ===================== --}}
      <div class="tab-pane fade" id="pedidos" role="tabpanel" aria-labelledby="tab-pedidos">
        <div class="jg-card p-3 mb-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="h4 mb-1">Histótico de Ventas</div>
              <div class="jg-muted">Los últimos pedidos completados o en proceso.</div>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="btn jg-btn jg-btn-outline"><i class="bi bi-table me-1"></i> Ver Todos los Pedidos</a>
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

@push('scripts')
<script>
  // Lógica JS para filtrar y ordenar las filas del lado del cliente (sin recargar la página)
  document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('adminSearchProductos');
    const estado = document.getElementById('adminEstadoProductos');
    const orden = document.getElementById('adminOrdenProductos');
    const tabla = document.getElementById('tablaProductos');
    
    if (!input || !estado || !orden || !tabla){
      return;
    }

    const tbody = tabla.querySelector('tbody');
    // Si no hay filas de datos reales (ej. "No hay juegos"), no procesamos filtros
    if (tbody.querySelector('tr[data-nombre]') === null) return;

    const rows = Array.from(tbody.querySelectorAll('tr[data-nombre]'));

    function aplicarFiltros(){
      const q = (input.value || '').trim().toLowerCase();
      const est = (estado.value || '').trim().toLowerCase();

      // Fase 1: Filtrado
      let visibles = rows.filter(r => {
        const nombre = r.dataset.nombre || '';
        const cat = r.dataset.categoria || '';
        const plat = r.dataset.plataforma || '';
        const estRow = r.dataset.estado || '';
        
        const matchQ = !q || (nombre.includes(q) || cat.includes(q) || plat.includes(q));
        const matchE = !est || estRow === est.toLowerCase();
        return matchQ && matchE;
      });

      // Fase 2: Ordenado
      const sortKey = orden.value;
      visibles.sort((a,b) => {
        const valA = a.dataset, valB = b.dataset;

        const cmpText = (x,y) => x.localeCompare(y, 'es', { sensitivity:'base' });
        const cmpNum = (x,y) => (parseFloat(x) || 0) - (parseFloat(y) || 0);

        if (sortKey === 'nombre_asc') return cmpText(valA.nombre, valB.nombre);
        if (sortKey === 'nombre_desc') return cmpText(valB.nombre, valA.nombre);
        if (sortKey === 'precio_asc') return cmpNum(valA.precio, valB.precio);
        if (sortKey === 'precio_desc') return cmpNum(valB.precio, valA.precio);
        if (sortKey === 'stock_asc') return cmpNum(valA.stock, valB.stock);
        if (sortKey === 'stock_desc') return cmpNum(valB.stock, valA.stock);
        // Las fechas en formato YYYY-MM-DD se ordenan naturalmente como string
        if (sortKey === 'fecha_asc') return cmpText(valA.fecha, valB.fecha);
        if (sortKey === 'fecha_desc') return cmpText(valB.fecha, valA.fecha);
        return 0;
      });

      // Fase 3: Renderizado
      tbody.innerHTML = '';
      if(visibles.length === 0) {
         tbody.innerHTML = '<tr><td colspan="10" class="text-center py-4 jg-muted">No se encontraron resultados para tu búsqueda.</td></tr>';
      } else {
         visibles.forEach(r => tbody.appendChild(r));
      }
    }

    // Escuchadores de eventos intermedios para redibujar la tabla automática al teclear o cambiar opciones
    input.addEventListener('input', aplicarFiltros);
    estado.addEventListener('change', aplicarFiltros);
    orden.addEventListener('change', aplicarFiltros);
  });
</script>
@endpush
@endsection
