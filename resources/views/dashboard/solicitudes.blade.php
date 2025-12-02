<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#000000">
  <meta name="description" content="Sistema de Control y Alerta de Etapas de Vida del Niño - SISCADIT">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>SISCADIT - Solicitudes</title>
  <link rel="stylesheet" href="{{ asset('Css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/dashbord.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/dashboard-main.css') }}">
  @stack('styles')
</head>
<body>
  <noscript>You need to enable JavaScript to run this app.</noscript>
  <div id="root">
    <div class="flex h-screen bg-slate-50 relative">
      <x-sidebar-main activeRoute="solicitudes" />
      <main class="flex-1 overflow-auto">
        <div class="p-8">
          <div class="space-y-8">
            <!-- Header -->
            <div>
              <h1 class="text-4xl font-bold text-slate-800">Gestión de Solicitudes</h1>
              <p class="text-slate-600 mt-2">Administración de solicitudes de acceso al sistema</p>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
              <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm text-slate-600 font-medium">Total Solicitudes</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-2">{{ $estadisticas['total'] }}</h3>
                  </div>
                  <div class="p-3 bg-blue-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                      <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                  </div>
                </div>
              </div>
              <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm text-slate-600 font-medium">Pendientes</p>
                    <h3 class="text-3xl font-bold text-amber-600 mt-2">{{ $estadisticas['pendientes'] }}</h3>
                  </div>
                  <div class="p-3 bg-amber-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600">
                      <circle cx="12" cy="12" r="10"></circle>
                      <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                  </div>
                </div>
              </div>
              <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm text-slate-600 font-medium">Aprobadas</p>
                    <h3 class="text-3xl font-bold text-green-600 mt-2">{{ $estadisticas['aprobadas'] }}</h3>
                  </div>
                  <div class="p-3 bg-green-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                      <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                      <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                  </div>
                </div>
              </div>
              <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm text-slate-600 font-medium">Rechazadas</p>
                    <h3 class="text-3xl font-bold text-red-600 mt-2">{{ $estadisticas['rechazadas'] }}</h3>
                  </div>
                  <div class="p-3 bg-red-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600">
                      <circle cx="12" cy="12" r="10"></circle>
                      <line x1="15" y1="9" x2="9" y2="15"></line>
                      <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                  </div>
                </div>
              </div>
            </div>

            <!-- Filtros y Búsqueda -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
              <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                  <input type="text" id="buscarSolicitud" placeholder="Buscar por documento, correo o motivo..." 
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="w-full md:w-48">
                  <select id="filtroEstado" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="aprobada">Aprobadas</option>
                    <option value="rechazada">Rechazadas</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Tabla de Solicitudes -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">ID</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Documento</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Correo</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Establecimiento</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Motivo</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Fecha</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Estado</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Usuario</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Acciones</th>
                    </tr>
                  </thead>
                  <tbody id="tablaSolicitudesBody" class="bg-white divide-y divide-slate-200">
                    @forelse($solicitudes as $solicitud)
                      <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">#{{ $solicitud->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $solicitud->numero_documento }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $solicitud->correo }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ Str::limit($solicitud->id_establecimiento, 30) }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ Str::limit($solicitud->motivo, 40) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $solicitud->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <span class="px-2 py-1 text-xs font-semibold rounded-full
                            @if($solicitud->estado === 'pendiente') bg-amber-100 text-amber-800
                            @elseif($solicitud->estado === 'aprobada') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($solicitud->estado) }}
                          </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          @if($solicitud->usuario)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                              Creado
                            </span>
                            <a href="{{ route('usuarios') }}?buscar={{ $solicitud->usuario->email }}" 
                              class="text-blue-600 hover:text-blue-800 text-xs ml-2" title="Ver usuario">
                              Ver
                            </a>
                          @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                              Sin usuario
                            </span>
                          @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                          <button onclick="verDetalleSolicitud({{ $solicitud->id }})" 
                            class="text-blue-600 hover:text-blue-800 mr-3">Ver</button>
                          @if($solicitud->estado === 'pendiente')
                            <button onclick="cambiarEstado({{ $solicitud->id }}, 'aprobada')" 
                              class="text-green-600 hover:text-green-800 mr-3">Aprobar</button>
                            <button onclick="cambiarEstado({{ $solicitud->id }}, 'rechazada')" 
                              class="text-red-600 hover:text-red-800">Rechazar</button>
                          @elseif($solicitud->estado === 'aprobada' && !$solicitud->usuario)
                            <button onclick="crearUsuarioDesdeSolicitud({{ $solicitud->id }})" 
                              class="text-indigo-600 hover:text-indigo-800 font-semibold">Crear Usuario</button>
                          @endif
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="9" class="px-6 py-8 text-center text-slate-500">No hay solicitudes registradas</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              <!-- Paginación -->
              <div class="px-6 py-4 border-t border-slate-200">
                {{ $solicitudes->links() }}
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Modal para ver detalles -->
  <div id="modalDetalleSolicitud" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
      <div class="p-6 border-b border-slate-200 flex justify-between items-center">
        <h3 class="text-xl font-bold text-slate-800">Detalles de la Solicitud</h3>
        <button onclick="cerrarModalDetalle()" class="text-slate-400 hover:text-slate-600">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18"></path>
            <path d="m6 6 12 12"></path>
          </svg>
        </button>
      </div>
      <div id="detalleSolicitudContent" class="p-6">
        <div class="text-center py-8">
          <div class="spinner border-4 border-slate-200 border-t-blue-600 rounded-full w-12 h-12 mx-auto animate-spin"></div>
          <p class="mt-4 text-slate-600">Cargando detalles...</p>
        </div>
      </div>
    </div>
  </div>

  <script>
    function verDetalleSolicitud(id) {
      const modal = document.getElementById('modalDetalleSolicitud');
      const content = document.getElementById('detalleSolicitudContent');
      modal.classList.remove('hidden');
      
      content.innerHTML = '<div class="text-center py-8"><div class="spinner border-4 border-slate-200 border-t-blue-600 rounded-full w-12 h-12 mx-auto animate-spin"></div><p class="mt-4 text-slate-600">Cargando detalles...</p></div>';
      
      fetch(`/solicitudes/${id}`, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const s = data.data;
          content.innerHTML = `
            <div class="space-y-4">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <p class="text-sm text-slate-600">Tipo de Documento</p>
                  <p class="font-semibold text-slate-900">${s.tipo_documento}</p>
                </div>
                <div>
                  <p class="text-sm text-slate-600">Número de Documento</p>
                  <p class="font-semibold text-slate-900">${s.numero_documento}</p>
                </div>
                <div>
                  <p class="text-sm text-slate-600">Red</p>
                  <p class="font-semibold text-slate-900">${s.red}</p>
                </div>
                <div>
                  <p class="text-sm text-slate-600">Microred</p>
                  <p class="font-semibold text-slate-900">${s.microred}</p>
                </div>
                <div class="col-span-2">
                  <p class="text-sm text-slate-600">Establecimiento</p>
                  <p class="font-semibold text-slate-900">${s.establecimiento}</p>
                </div>
                <div>
                  <p class="text-sm text-slate-600">Motivo</p>
                  <p class="font-semibold text-slate-900">${s.motivo}</p>
                </div>
                <div>
                  <p class="text-sm text-slate-600">Cargo</p>
                  <p class="font-semibold text-slate-900">${s.cargo}</p>
                </div>
                <div>
                  <p class="text-sm text-slate-600">Celular</p>
                  <p class="font-semibold text-slate-900">${s.celular}</p>
                </div>
                <div>
                  <p class="text-sm text-slate-600">Correo</p>
                  <p class="font-semibold text-slate-900">${s.correo}</p>
                </div>
                <div>
                  <p class="text-sm text-slate-600">Estado</p>
                  <span class="px-2 py-1 text-xs font-semibold rounded-full ${s.estado === 'pendiente' ? 'bg-amber-100 text-amber-800' : s.estado === 'aprobada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${s.estado.charAt(0).toUpperCase() + s.estado.slice(1)}</span>
                </div>
                <div>
                  <p class="text-sm text-slate-600">Fecha de Solicitud</p>
                  <p class="font-semibold text-slate-900">${s.fecha_solicitud}</p>
                </div>
                ${s.usuario_creado ? `
                <div class="col-span-2 border-t pt-4 mt-4">
                  <p class="text-sm font-semibold text-slate-700 mb-2">Usuario Creado</p>
                  <div class="bg-blue-50 p-3 rounded-lg">
                    <p class="text-sm text-slate-600">Nombre: <span class="font-semibold">${s.usuario_creado.name}</span></p>
                    <p class="text-sm text-slate-600">Email: <span class="font-semibold">${s.usuario_creado.email}</span></p>
                    <a href="/usuarios?buscar=${s.usuario_creado.email}" class="text-blue-600 hover:text-blue-800 text-sm mt-2 inline-block">Ver usuario →</a>
                  </div>
                </div>
                ` : s.estado === 'aprobada' ? `
                <div class="col-span-2 border-t pt-4 mt-4">
                  <button onclick="crearUsuarioDesdeSolicitud(${s.id})" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold">
                    Crear Usuario desde esta Solicitud
                  </button>
                </div>
                ` : ''}
              </div>
            </div>
          `;
        }
      })
      .catch(error => {
        content.innerHTML = '<div class="text-center py-8 text-red-600">Error al cargar los detalles</div>';
      });
    }

    function crearUsuarioDesdeSolicitud(id) {
      if (!confirm('¿Está seguro de crear un usuario desde esta solicitud? Se generará una contraseña temporal.')) {
        return;
      }

      fetch(`/solicitudes/${id}/crear-usuario`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          let mensaje = 'Usuario creado correctamente.\n\n';
          if (data.password_temporal) {
            mensaje += `Contraseña temporal: ${data.password_temporal}\n\n`;
            mensaje += `Email: ${data.email}\n\n`;
            mensaje += '¡IMPORTANTE! Guarde esta contraseña, no se mostrará nuevamente.';
          }
          alert(mensaje);
          location.reload();
        } else {
          alert(data.message || 'Error al crear el usuario');
        }
      })
      .catch(error => {
        alert('Error al crear el usuario');
        console.error(error);
      });
    }

    function cerrarModalDetalle() {
      document.getElementById('modalDetalleSolicitud').classList.add('hidden');
    }

    // Variables globales para el modal de rechazo
    let solicitudIdARechazarEstado = null;
    let estadoSolicitud = '';

    function cambiarEstado(id, estado) {
      // Si es rechazo, mostrar modal de confirmación
      if (estado === 'rechazada') {
        solicitudIdARechazarEstado = id;
        estadoSolicitud = estado;
        
        // Limpiar el campo de motivo
        const motivoInput = document.getElementById('motivoRechazoEstado');
        if (motivoInput) {
          motivoInput.value = '';
        }
        
        // Abrir el modal
        const modal = document.getElementById('modalConfirmarRechazarEstado');
        if (modal) {
          modal.classList.add('show');
        }
        return;
      }
      
      // Si es aprobación, usar confirm simple
      if (!confirm(`¿Está seguro de aprobar esta solicitud?`)) {
        return;
      }

      fetch(`/solicitudes/${id}/estado`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ estado })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload();
        } else {
          alert('Error al actualizar el estado');
        }
      })
      .catch(error => {
        alert('Error al actualizar el estado');
      });
    }

    // Función para cerrar el modal de rechazo de estado
    function closeModalConfirmarRechazarEstado(event) {
      if (event && event.target === event.currentTarget) {
        document.getElementById('modalConfirmarRechazarEstado').classList.remove('show');
      } else if (!event) {
        document.getElementById('modalConfirmarRechazarEstado').classList.remove('show');
      }
      // Limpiar variables
      solicitudIdARechazarEstado = null;
      estadoSolicitud = '';
    }

    // Función para confirmar y ejecutar el rechazo de estado
    async function confirmarRechazarEstado() {
      if (!solicitudIdARechazarEstado) {
        console.error('No hay solicitud seleccionada para rechazar');
        return;
      }

      // Obtener el motivo de rechazo (opcional)
      const motivoInput = document.getElementById('motivoRechazoEstado');
      const motivo = motivoInput ? motivoInput.value.trim() : '';

      // Obtener los botones para deshabilitarlos y mostrar loading
      const btnRechazar = document.querySelector('#modalConfirmarRechazarEstado button[onclick*="confirmarRechazarEstado"]');
      const btnCancelar = document.querySelector('#modalConfirmarRechazarEstado button[onclick*="closeModalConfirmarRechazarEstado"]');
      
      // Guardar el contenido original del botón
      const contenidoOriginal = btnRechazar ? btnRechazar.innerHTML : '';
      
      // Deshabilitar botones y mostrar loading
      if (btnRechazar) {
        btnRechazar.disabled = true;
        btnRechazar.innerHTML = `
          <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Rechazando...
        `;
      }
      if (btnCancelar) {
        btnCancelar.disabled = true;
      }

      try {
        const response = await fetch(`/solicitudes/${solicitudIdARechazarEstado}/estado`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({ 
            estado: estadoSolicitud,
            motivo: motivo
          })
        });

        const data = await response.json();

        // Restaurar botones
        if (btnRechazar) {
          btnRechazar.disabled = false;
          btnRechazar.innerHTML = contenidoOriginal;
        }
        if (btnCancelar) {
          btnCancelar.disabled = false;
        }

        if (data.success) {
          // Cerrar el modal
          closeModalConfirmarRechazarEstado();
          // Recargar la página
          location.reload();
        } else {
          alert('Error al rechazar la solicitud: ' + (data.message || 'Error desconocido'));
        }
      } catch (error) {
        console.error('Error al rechazar solicitud:', error);
        
        // Restaurar botones en caso de error
        if (btnRechazar) {
          btnRechazar.disabled = false;
          btnRechazar.innerHTML = contenidoOriginal;
        }
        if (btnCancelar) {
          btnCancelar.disabled = false;
        }

        alert('Error al rechazar la solicitud. Por favor, intente nuevamente.');
      }
    }

    // Filtros
    document.getElementById('buscarSolicitud')?.addEventListener('keyup', function(e) {
      if (e.key === 'Enter') {
        const buscar = this.value;
        const estado = document.getElementById('filtroEstado').value;
        window.location.href = `/solicitudes?buscar=${buscar}&estado=${estado}`;
      }
    });

    document.getElementById('filtroEstado')?.addEventListener('change', function() {
      const buscar = document.getElementById('buscarSolicitud').value;
      const estado = this.value;
      window.location.href = `/solicitudes?buscar=${buscar}&estado=${estado}`;
    });
  </script>

  <!-- Modal de Confirmación para Rechazar Solicitud (Cambio de Estado) -->
  <div id="modalConfirmarRechazarEstado" class="modal-usuario-overlay" onclick="closeModalConfirmarRechazarEstado(event)" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div class="modal-usuario-container" style="max-width: 32rem; background: white; border-radius: 0.75rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);" onclick="event.stopPropagation()">
      <!-- Header del Modal -->
      <div class="modal-usuario-header" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #b91c1c 100%); padding: 1.5rem; border-radius: 0.75rem 0.75rem 0 0; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 1rem;">
          <div style="width: 3rem; height: 3rem; background: rgba(255, 255, 255, 0.25); border-radius: 9999px; display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: white;">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
          </div>
          <div>
            <h3 style="font-size: 1.25rem; font-weight: 700; color: white; margin: 0;">Confirmar Rechazo de Solicitud</h3>
            <p style="font-size: 0.875rem; color: rgba(255, 255, 255, 0.9); margin: 0.25rem 0 0 0;">Esta acción no se puede deshacer</p>
          </div>
        </div>
        <button onclick="closeModalConfirmarRechazarEstado()" style="background: rgba(255, 255, 255, 0.2); border: none; color: white; width: 2rem; height: 2rem; border-radius: 9999px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" onmouseover="this.style.background='rgba(255, 255, 255, 0.3)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.2)'">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>
      
      <!-- Contenido del Modal -->
      <div style="padding: 2rem;">
        <div style="margin-bottom: 1.5rem; text-align: center;">
          <div style="margin: 0 auto; width: 5rem; height: 5rem; background-color: #fee2e2; border-radius: 9999px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
              <line x1="12" y1="9" x2="12" y2="13"></line>
              <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
          </div>
          <h4 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">¿Está seguro de rechazar esta solicitud?</h4>
          <p style="color: #475569; margin-bottom: 0.5rem; font-size: 0.875rem;">
            La solicitud será marcada como <strong style="color: #0f172a; font-weight: 600;">rechazada</strong>.
          </p>
          <p style="font-size: 0.875rem; color: #dc2626; font-weight: 600; margin-top: 0.75rem;">
            ⚠️ Esta acción no se puede deshacer
          </p>
        </div>
        
        <!-- Campo para motivo de rechazo (opcional) -->
        <div style="margin-top: 1.5rem;">
          <label for="motivoRechazoEstado" style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
            Motivo de rechazo <span style="color: #9ca3af; font-weight: 400;">(Opcional)</span>
          </label>
          <textarea 
            id="motivoRechazoEstado" 
            name="motivoRechazoEstado" 
            rows="3" 
            placeholder="Ingrese el motivo del rechazo de la solicitud..."
            style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; color: #374151; resize: vertical; transition: border-color 0.2s; box-sizing: border-box;"
            onfocus="this.style.borderColor='#667eea'; this.style.outline='none';"
            onblur="this.style.borderColor='#d1d5db';"
          ></textarea>
          <p style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;">
            Este motivo puede ser útil para referencia futura.
          </p>
        </div>
      </div>
      
      <!-- Footer del Modal -->
      <div style="padding: 1.5rem; border-top: 1px solid #e5e7eb; display: flex; gap: 0.75rem; justify-content: space-between;">
        <button type="button" onclick="closeModalConfirmarRechazarEstado()" style="flex: 0 1 auto; min-width: 140px; padding: 0.625rem 1.5rem; background: #f3f4f6; color: #374151; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: background 0.2s;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
          Cancelar
        </button>
        <button type="button" onclick="confirmarRechazarEstado()" style="background: linear-gradient(135deg, #ef4444, #dc2626, #b91c1c); color: white; flex: 1; margin-left: 0.75rem; padding: 0.625rem 1.5rem; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.3); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
            <line x1="12" y1="9" x2="12" y2="13"></line>
            <line x1="12" y1="17" x2="12.01" y2="17"></line>
          </svg>
          Sí, Rechazar Solicitud
        </button>
      </div>
    </div>
  </div>

  <style>
    .modal-usuario-overlay.show {
      display: flex !important;
    }
  </style>
</body>
</html>

