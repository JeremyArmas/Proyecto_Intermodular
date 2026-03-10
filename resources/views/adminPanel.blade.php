@extends('layouts.app')

@section('title', 'Panel de administración • Jediga')

@section('content')
@php
  // $fmt sirve para formatear precios, se puede seguir usando.
  $fmt = fn($n) => number_format($n, 2, ',', '.');
@endphp

<!-- CONTENIDO PRINCIPAL -->
<div class="jg-admin jg-admin-wrap">
  <div class="container">

    <!-- Header del panel de administración -->
    <div class="jg-admin-header p-4 mb-4">
      <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>

          <!-- Etiqueta principal del panel de admin -->
          <div class="jg-pill mb-2">
            <span class="jg-dot"></span>
            <span>Panel de administración</span>
          </div>

          <!-- Título y descripción breve -->
          <h1 class="jg-section-title h3 mb-2" style="display:block;">Administración</h1>
          <div class="jg-muted">Gestión de productos, categorías y más.</div>

          <!-- Chips (etiquetas informativas) descriptivos (solo UI) -->
          <div class="mt-3 d-flex flex-wrap gap-2">
            <span class="jg-chip"><i class="bi bi-shield-lock"></i> Solo admin</span>
            <span class="jg-chip"><i class="bi bi-sliders"></i> Filtros y ordenación</span>
            <span class="jg-chip"><i class="bi bi-table"></i> CRUD</span>
          </div>
        </div>

        <!-- Botones de acción rápida -->
        <div class="ms-auto">
          <a href="{{ route('admin.games.create') }}" class="btn jg-btn jg-btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Añadir Juego
          </a>
        </div>
        
        <!-- Nota aclaratoria (solo UI) -->
        <div class="jg-admin-note mt-3 small">
          Nota: esto es <strong>solo UI</strong>. Los botones están como “placeholder” para cuando conectéis BD + controladores.
        </div>
      </div>
    </div>
    
    <!-- KPIs principales (mediciones de datos) -->

    <!-- KPI del total de productos -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-md-6 col-lg-3">
        <div class="jg-kpi p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>

              <!-- Título del Kpi y la recolecta del total de productos existentes -->
              <div class="small jg-muted">Productos</div>
              <div class="h3 mb-0">{{ $totalProductos }}</div>
            </div>

            <!-- Etiqueta descriptiva del KPI -->
            <span class="badge badge-soft"><i class="bi bi-box-seam me-1"></i> Total</span>
          </div>
        </div>
      </div>

      <!-- KPI del total de productos con bajo stock -->
      <div class="col-12 col-md-6 col-lg-3">
        <div class="jg-kpi p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>

              <!-- Título del KPI y la recolecta del total -->
              <div class="small jg-muted">Bajo stock</div>
              <div class="h3 mb-0">{{ $bajoStock }}</div>
            </div>

            <!-- Etiqueta descriptiva del KPI -->
            <span class="badge badge-sun"><i class="bi bi-exclamation-triangle me-1"></i> Atención</span>
          </div>
        </div>
      </div>

      <!-- KPI de los productos sin stock -->
      <div class="col-12 col-md-6 col-lg-3">
        <div class="jg-kpi p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>

              <!-- Título del KPI y la recolecta de aquellos sin stock -->
              <div class="small jg-muted">Sin stock</div>
              <div class="h3 mb-0">{{ $sinStock }}</div>
            </div>

            <!-- Etiqueta descriptiva del KPI -->
            <span class="badge badge-soft"><i class="bi bi-x-circle me-1"></i> Agotado</span>
          </div>
        </div>
      </div>

      <!-- KPI de los borradores -->
      <div class="col-12 col-md-6 col-lg-3">
        <div class="jg-kpi p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>

              <!-- Título del KPI y la recolecta de borradores -->
              <div class="small jg-muted">Borradores</div>
              <div class="h3 mb-0">{{ $borradores }}</div>
            </div>

            <!-- Etiqueta descriptiva del KPI -->
            <span class="badge badge-mint"><i class="bi bi-pencil-square me-1"></i> Draft</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Pestañas para secciones principales -->

    <!-- Pestaña de productos -->
    <ul class="nav jg-admin-tabs gap-2 mb-3" id="adminTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tab-productos" data-bs-toggle="tab" data-bs-target="#productos" type="button" role="tab">
          <i class="bi bi-box-seam me-1"></i> Productos
        </button>
      </li>

      <!-- Pestaña de categorías -->
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-categorias" data-bs-toggle="tab" data-bs-target="#categorias" type="button" role="tab">
          <i class="bi bi-tags me-1"></i> Categorías
        </button>
      </li>

      <!-- Pestaña de usuarios -->
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-usuarios" data-bs-toggle="tab" data-bs-target="#usuarios" type="button" role="tab">
          <i class="bi bi-people me-1"></i> Usuarios
        </button>
      </li>

      <!-- Pestaña de pedidos -->
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-pedidos" data-bs-toggle="tab" data-bs-target="#pedidos" type="button" role="tab">
          <i class="bi bi-receipt me-1"></i> Pedidos
        </button>
      </li>
    </ul>

    <!-- Contenido de cada pestaña -->
    <div class="tab-content">

      <!-- Pestaña de productos -->
      <div class="tab-pane fade show active" id="productos" role="tabpanel" aria-labelledby="tab-productos">

        <!-- Barra de filtros y acciones rápidas -->
        <div class="jg-filterbar mb-3">
          <div class="row g-2 align-items-end">
            <div class="col-12 col-lg-4">
              
              <!-- Campo de búsqueda con icono -->
              <label class="form-label mb-1">Buscar</label>
              <div class="input-group">
                <span class="input-group-text" style="background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.12); color:#fff;">
                  <i class="bi bi-search"></i>
                </span>
                <input id="adminSearchProductos" class="form-control" type="text" placeholder="Nombre, categoría, plataforma…">
              </div>
            </div>

            <!-- Filtro de estado -->
            <div class="col-6 col-lg-2">
              <label class="form-label mb-1">Estado</label>
              <select id="adminEstadoProductos" class="form-select">
                <option value="">Todos</option>
                <option>Publicado</option>
                <option>Borrador</option>
                <option>Oculto</option>
              </select>
            </div>

            <!-- Selector de ordenación -->
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

            <!-- Botones de acción rápida (placeholder) -->
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

        <div id="contenedorProductos">
            <!-- Tabla de productos -->
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
                
                <!-- Recorre los productos para mostrar cada uno en una fila de la tabla -->
                @foreach($productos as $p)
                  
                  <!-- Lógica para determinar las etiquetas visuales según los datos de cada producto -->
                  @php
                    $estadoStr = $p->is_active ? 'Publicado' : 'Borrador';
                    $badgeEstado = $p->is_active ? 'badge-mint' : 'badge-soft';
                    $stockWarn = $p->stock === 0 ? 'badge-sun' : ($p->stock <= 10 ? 'badge-sun' : 'badge-soft');
                    $categoriasStr = $p->categories->pluck('name')->join(', ');
                  @endphp
                  
                  <!-- Atributos data-* usados por nuestro JS local para ordenar y buscar -->
                  <tr data-nombre="{{ strtolower($p->title) }}"
                      data-categoria="{{ strtolower($categoriasStr) }}"
                      data-plataforma="{{ strtolower($p->platform->name ?? '') }}"
                      data-estado="{{ strtolower($estadoStr) }}"
                      data-precio="{{ $p->price }}"
                      data-stock="{{ $p->stock }}"
                      data-fecha="{{ $p->updated_at }}">
                    
                    <!-- Checkbox para selección múltiple (sin funcionalidad real) -->
                    <td>
                        @if($p->cover_image)
                            <img src="{{ asset('storage/' . $p->cover_image) }}" alt="Img" style="width: 32px; height: 32px; object-fit: cover; border-radius: 4px;">
                        @else
                            <div style="width: 32px; height: 32px; border-radius: 4px; background: rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:center;">
                                <i class="bi bi-joystick text-white-50"></i>
                            </div>
                        @endif
                    </td>
                    
                    <!-- ID del producto -->
                    <td class="text-nowrap">#{{ $p->id }}</td>
                    
                    <!-- Título del producto -->
                    <td class="fw-bold">{{ $p->title }}</td>
                    
                    <!-- Categorías asociadas al producto -->
                    <td>{{ $categoriasStr ?: 'Sin categorizar' }}</td>
                    
                    <!-- Plataforma asociada al producto -->
                    <td><span class="badge badge-soft">{{ $p->platform->name ?? 'N/A' }}</span></td>
                    
                    <!-- Precio formateado del producto -->
                    <td class="text-end fw-bold" style="color: var(--jg-mint);">{{ $fmt($p->price) }} €</td>
                    
                    <!-- Stock del producto con etiqueta de advertencia si es bajo o agotado -->
                    <td class="text-end">
                      <span class="badge {{ $stockWarn }}">
                        {{ $p->stock }}
                      </span>
                    </td>
                    
                    <!-- Estado del producto con etiqueta visual -->
                    <td><span class="badge {{ $badgeEstado }}">{{ $estadoStr }}</span></td>
                    
                    <!-- Fecha de última actualización del producto -->
                    <td class="text-nowrap">{{ $p->updated_at->format('Y-m-d') }}</td>
                    
                    <!-- Botones de acción para editar o eliminar el producto -->
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

        <!-- Barra de paginación y conteo de productos -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
          
          <!-- Conteo de productos mostrados y total -->
          <div class="jg-muted small">Mostrando {{ $productos->firstItem() ?? 0 }} – {{ $productos->lastItem() ?? 0 }} de {{ $productos->total() }}</div>
          
          <!-- Paginación cada 10 -->
          <div class="ms-auto">
            {{ $productos->links() }}
          </div>
        </div>
      </div>
        </div>
        

      <!-- Pestaña de categorías -->
      <div class="tab-pane fade" id="categorias" role="tabpanel" aria-labelledby="tab-categorias">
        <div class="jg-card p-3 mb-3">
          <div class="d-flex justify-content-between align-items-center">
            
            <!-- Título de la sección de categorías y descripción breve -->
            <div>
              <div class="h4 mb-1">Categorías</div>
              <div class="jg-muted">Estructura del catálogo.</div>
            </div>
            
            <!-- Botones de acción rápida para gestionar categorías -->
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

        <!-- Tabla de categorías -->
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

                <!-- Recorre las categorías para mostrar cada una en una fila de la tabla -->
                @forelse($categorias as $c)
                  <tr>

                    <!-- ID de la categoría -->
                    <td>#{{ $c->id }}</td>
                    
                    <!-- Nombre de la categoría -->
                    <td class="fw-bold">{{ $c->name }}</td>
                    
                    <!-- Identificador de la categoría -->
                    <td><span class="badge badge-soft">{{ $c->slug }}</span></td>
                    
                    <!-- Conteo de juegos asignados a la categoría -->
                    <td class="text-end">{{ $c->games_count }}</td>
                    
                    <!-- Fecha de creación de la categoría -->
                    <td class="text-nowrap">{{ $c->created_at->format('Y-m-d') }}</td>
                    
                    <!-- Botones de acción para editar o eliminar la categoría -->
                    <td class="text-end">
                      <a href="{{ route('admin.categories.edit', $c) }}" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-pencil"></i></a>
                      <form action="{{ route('admin.categories.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-trash"></i></button>
                    </td>
                  </tr>
                
                <!-- Si no hay categorías, muestra un mensaje centrado -->  
                @empty
                 <tr><td colspan="6" class="text-center py-4 jg-muted">No hay categorías.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Pestaña de usuarios -->
      <div class="tab-pane fade" id="usuarios" role="tabpanel" aria-labelledby="tab-usuarios">
        <div class="jg-card p-3 mb-3">
          <div class="d-flex justify-content-between align-items-center">
            
            <!-- Título de la sección de usuarios y descripción breve -->
            <div>
              <div class="h4 mb-1">Usuarios</div>
              <div class="jg-muted">Gestión de roles y estado.</div>
            </div>
            
            <!-- Botones de acción rápida para gestionar usuarios -->
            <div class="justify-content-end d-flex gap-2">
              <a href="{{ route('admin.users.index') }}" class="btn jg-btn jg-btn-sun">
                <i class="bi bi-eye me-1"></i> Ver todos los usuarios
              </a>
              <a href="{{ route('admin.users.create') }}" class="btn jg-btn jg-btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Invitar
              </a>
            </div>
          </div>
        </div>

        <!-- Tabla de usuarios -->
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

                <!-- Recorre los usuarios para mostrar cada uno en una fila de la tabla -->
                @forelse($usuarios as $u)
                  
                  <!-- Lógica para determinar la etiqueta visual del rol del usuario -->
                  @php
                    $bRol = $u->role === 'admin' ? 'badge-sun' : ($u->role === 'company' ? 'badge-soft' : 'badge-mint');
                  @endphp
                  <tr>
                    
                    <!-- ID del usuario -->
                    <td>#{{ $u->id }}</td>
                    
                    <!-- Nombre y apellidos del usuario -->
                    <td class="fw-bold">{{ $u->name }}</td>
                    
                    <!-- Correo electrónico del usuario con enlace mailto -->
                    <td><a href="mailto:{{ $u->email }}" class="text-white text-decoration-none">{{ $u->email }}</a></td>
                    
                    <!-- Rol del usuario con etiqueta visual -->
                    <td><span class="badge {{ $bRol }}">{{ ucfirst($u->role) }}</span></td>
                    
                    <!-- Fecha de registro del usuario -->
                    <td class="text-nowrap">{{ $u->created_at->format('Y-m-d H:i') }}</td>
                    
                    <!-- Botones de acción para editar o eliminar el usuario -->
                    <td class="text-end">
                      <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-pencil"></i></a>
                      <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                
                <!-- Si no hay usuarios, muestra un mensaje centrado -->  
                @empty
                  <tr><td colspan="6" class="text-center py-4 jg-muted">No hay usuarios registrados.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Pestaña de pedidos -->
      <div class="tab-pane fade" id="pedidos" role="tabpanel" aria-labelledby="tab-pedidos">
        <div class="jg-card p-3 mb-3">
          <div class="d-flex justify-content-between align-items-center">
            
            <!-- Título de la sección de pedidos y descripción breve -->
            <div>
              <div class="h4 mb-1">Pedidos</div>
              <div class="jg-muted">Histórico y estados.</div>
            </div>
            
            <!-- Botones de acción rápida para gestionar pedidos -->
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

        <!-- Tabla de pedidos -->
        <div class="jg-table-wrap">
          <div class="table-responsive">
            <table class="table jg-table align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Datos del Comprador</th>
                  <th>Tipo (B2B/B2C)</th>
                  <th class="text-end">Total Cobrado</th>
                  <th>Estado del Envío</th>
                  <th>Fecha y Hora</th>
                  <th class="text-end">Auditar</th>
                </tr>
              </thead>
              <tbody>
                
                <!-- Recorre los pedidos para mostrar cada uno en una fila de la tabla -->
                @forelse($pedidos as $p)
                  
                  <!-- Lógica para determinar la etiqueta visual del estado del pedido -->
                  @php
                    $bOrder = $p->status === 'paid' ? 'badge-primary' : ($p->status === 'shipped' ? 'badge-mint' : ($p->status === 'cancelled' ? 'badge-sun' : 'badge-soft'));
                    $st = ['pending'=>'Pendiente','paid'=>'Pagado','shipped'=>'Enviado','cancelled'=>'Cancelado'];
                  @endphp
                  
                  <tr>
                    <!-- ID del pedido -->
                    <td><span class="fw-bold">#{{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</span></td>
                    
                    <!-- Datos del comprador: nombre, email (con manejo de caso de usuario borrado) -->
                    <td>
                        {{ $p->user->name ?? 'Usuario borrado' }}<br>
                        <small class="jg-muted">{{ $p->user->email ?? '--' }}</small>
                    </td>
                    
                    <!-- Tipo de pedido (B2B o B2C) con etiqueta visual -->
                    <td><span class="badge badge-soft">{{ strtoupper($p->order_type) }}</span></td>
                    
                    <!-- Total cobrado por el pedido, formateado como precio -->
                    <td class="text-end fw-bold" style="color: var(--jg-mint);">{{ $fmt($p->total_amount) }} €</td>
                    
                    <!-- Estado del envío del pedido con etiqueta visual -->
                    <td><span class="badge {{ $bOrder }}">{{ $st[$p->status] ?? $p->status }}</span></td>
                    
                    <!-- Fecha y hora de creación del pedido -->
                    <td class="text-nowrap">{{ $p->created_at->format('Y-m-d H:i') }}</td>
                    
                    <!-- Botones de acción para auditar el pedido o eliminarlo -->
                    <td class="text-end">
                      <a href="{{ route('admin.orders.show', $p) }}" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-eye"></i></a>
                      <form action="{{ route('admin.orders.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este pedido?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                
                <!-- Si no hay pedidos, muestra un mensaje centrado -->  
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

<!-- Script para el filtro básico de productos en la pestaña de productos -->
@push('scripts')
<script>
  
  // Filtro básico front
  document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('adminSearchProductos');
    const estado = document.getElementById('adminEstadoProductos');
    const orden = document.getElementById('adminOrdenProductos');
    const tabla = document.getElementById('tablaProductos');
    
    // Si no se encuentran los elementos necesarios, no se aplica el filtro
    if (!input || !estado || !orden || !tabla){
      return;
    }

    // Obtiene todas las filas del cuerpo de la tabla para su manipulación
    const tbody = tabla.querySelector('tbody');
    const filas = Array.from(tbody.querySelectorAll('tr'));

    // Función principal para aplicar el filtro y ordenación según los inputs del usuario
    function aplicar(){

      // obtiene y normaliza el texto de búsqueda y el estado seleccionado para comparaciones insensibles a mayúsculas y espacios
      const input = (input.value || '').trim().toLowerCase();
      const estado = (estado.value || '').trim().toLowerCase();

      // filtra las filas según el texto de búsqueda y el estado seleccionado
      let visibles = filas.filter(fil => {
        const nombre = fil.dataset.nombre || '';
        const categoria = fil.dataset.categoria || '';
        const plataforma = fil.dataset.plataforma || '';
        const estado = fil.dataset.estado || '';
        const matchInput = !input || (nombre.includes(input) || categoria.includes(input) || plataforma.includes(input));
        const matchEstado = !estado || estado === estado.toLowerCase();
        return matchInput && matchEstado;
      });

      // ordena las filas visibles según el criterio seleccionado en el ordenamiento
      const ordenar = orden.value;
      
      // funciones de comparación para texto y números, con manejo de casos especiales
      visibles.sort((a,b) => {
        
        // obtiene los datos relevantes de cada fila para la comparación según el criterio de ordenamiento
        const A = a.dataset, B = b.dataset;

        // función de comparación para texto, usando localeCompare para los caracteres y acentos
        const compararTexto = (x,y)=> x.localeCompare(y, 'es', { sensitivity:'base' });
        
        // función de comparación para números, manejando casos donde el valor no sea un número válido
        const compararNumber = (x,y)=> (parseFloat(x) || 0) - (parseFloat(y) || 0);

        // lógica de ordenamiento según el valor seleccionado en el selector de ordenación
        if (ordenar === 'nombre_asc') return compararTexto(A.nombre, B.nombre);
        if (ordenar === 'nombre_desc') return compararTexto(B.nombre, A.nombre);
        if (ordenar === 'precio_asc') return compararNumber(A.precio, B.precio);
        if (ordenar === 'precio_desc') return compararNumber(B.precio, A.precio);
        if (ordenar === 'stock_asc') return compararNumber(A.stock, B.stock);
        if (ordenar === 'stock_desc') return compararNumber(B.stock, A.stock);
        if (ordenar === 'fecha_asc') return compararTexto(A.fecha, B.fecha);
        if (ordenar === 'fecha_desc') return compararTexto(B.fecha, A.fecha);
        return 0;
      });

      // pinta las filas visibles
      tbody.innerHTML = '';
      visibles.forEach(r => tbody.appendChild(r));
    }

    // agrega los event listeners para aplicar el filtro y ordenación cada vez que el usuario interactúe con los inputs
    input.addEventListener('input', aplicar);
    estado.addEventListener('change', aplicar);
    orden.addEventListener('change', aplicar);
  });


  // Paginación con fetch + reemplazo de tabla (sin recargar toda la página)
  document.addEventListener('click', async (e) => {
    
    // verifica si el clic ocurrió en un enlace de paginación dentro del contenedor de productos
    const link = e.target.closest('#contenedorProductos .pagination a');
    
    // si no se encuentra un enlace de paginación, no se realiza ninguna acción especial
    if (!link){
      return;
    }

    e.preventDefault();

    // obtiene el contenedor de productos para mostrar un mini "loading" mientras se realiza la petición
    const contenedor = document.getElementById('contenedorProductos');
    
    // si no se encuentra el contenedor, no se puede mostrar el "loading" ni reemplazar la tabla, por lo que se detiene la ejecución
    if (!contenedor){
      return;
    }

    // Mini “loading” solo de la tabla (sin preloader)
    contenedor.style.opacity = '0.6';
    contenedor.style.pointerEvents = 'none';

    // realiza una petición fetch al enlace de paginación, esperando una respuesta HTML que contenga la nueva tabla de productos
    try {
      const res = await fetch(link.href, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
      });

      // si la respuesta no es exitosa, se lanza un error para manejarlo en el bloque catch
      const html = await res.text();
      
      // se parsea la respuesta HTML para extraer el nuevo contenedor de productos que contiene la tabla actualizada
      const doc = new DOMParser().parseFromString(html, 'text/html');
      
      // se busca el nuevo contenedor de productos en la respuesta parseada
      const nuevoContenedor = doc.querySelector('#contenedorProductos');

      // si se encuentra el nuevo contenedor, se reemplaza el actual con el nuevo y se actualiza la URL sin recargar la página
      if (nuevoContenedor) {
        contenedor.replaceWith(nuevoContenedor);
        history.pushState({}, '', link.href);

        // Si existe una función global para inicializar funcionalidades específicas de la tabla de productos, se llama después de reemplazar el contenido
        if (typeof window.initAdminProductos === 'function') {
          window.initAdminProductos();
        }
      }
    } catch (err) {

      // Si falla el fetch, navega normal
      window.location.href = link.href;
    } finally {
      
      // Independientemente del resultado, se restablece la opacidad y la capacidad de interacción del contenedor de productos
      const current = document.getElementById('contenedorProductos');
      if (current) {
        current.style.opacity = '';
        current.style.pointerEvents = '';
      }
    }
});

</script>
@endpush

@endsection
