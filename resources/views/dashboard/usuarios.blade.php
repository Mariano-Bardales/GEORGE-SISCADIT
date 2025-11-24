<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#000000">
  <meta name="description" content="Sistema de Control y Alerta de Etapas de Vida del Niño - SISCADIT">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>SISCADIT - Usuarios y Solicitudes</title>
  <link rel="stylesheet" href="{{ asset('Css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/dashbord.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/dashboard-main.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/usuarios-solicitudes.css') }}">
  @stack('styles')
</head>
<body>
  <noscript>You need to enable JavaScript to run this app.</noscript>
  <div id="root">
    <div class="flex h-screen bg-slate-50 relative">
      <x-sidebar-main activeRoute="usuarios" />
      <main class="flex-1 overflow-auto">
        <div class="p-8">
          <div class="space-y-8">
            <!-- Header -->
            <div class="flex justify-between items-center">
              <div>
                <h1 class="text-4xl font-bold text-slate-800">Gestión de Usuarios y Solicitudes</h1>
                <p class="text-slate-600 mt-2">Administración unificada de usuarios y solicitudes del sistema</p>
              </div>
              <div class="flex gap-3">
                <button onclick="abrirModalCrearSolicitud()" id="btnCrearSolicitud"
                  class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-semibold flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="12" y1="18" x2="12" y2="12"></line>
                    <line x1="9" y1="15" x2="15" y2="15"></line>
                  </svg>
                  Nueva Solicitud
                </button>
                <button onclick="abrirModalCrearUsuario()" id="btnCrearUsuario"
                  class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <line x1="19" y1="8" x2="19" y2="14"></line>
                    <line x1="22" y1="11" x2="16" y2="11"></line>
                  </svg>
                  Nuevo Usuario
                </button>
              </div>
            </div>

            <!-- Pestañas -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
              <div class="border-b border-slate-200">
                <nav class="flex -mb-px">
                  <button onclick="cambiarTab('solicitudes')" id="tab-solicitudes" 
                    class="px-6 py-4 text-sm font-semibold text-blue-600 border-b-2 border-blue-600 bg-blue-50 transition-all">
                    <span class="flex items-center gap-2">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <path d="m9 15 2 2 4-4"></path>
                      </svg>
                      Solicitudes
                    </span>
                  </button>
                  <button onclick="cambiarTab('usuarios')" id="tab-usuarios" 
                    class="px-6 py-4 text-sm font-semibold text-slate-600 hover:text-blue-600 hover:border-blue-600 border-b-2 border-transparent transition-all">
                    <span class="flex items-center gap-2">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                      </svg>
                      Usuarios
                    </span>
                  </button>
                </nav>
              </div>

              <!-- Contenido de Solicitudes (PRIMERO) -->
              <div id="contenido-solicitudes" class="p-6">
                <!-- Estadísticas de Solicitudes -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                  <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-sm border border-blue-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                      <div>
                        <p class="text-sm text-slate-600 font-medium">Total Solicitudes</p>
                        <h3 class="text-3xl font-bold text-blue-700 mt-2">{{ $estadisticasSolicitudes['total'] }}</h3>
                      </div>
                      <div class="p-3 bg-blue-200 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-700">
                          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                          <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                      </div>
                    </div>
                  </div>
                  <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl shadow-sm border border-amber-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                      <div>
                        <p class="text-sm text-slate-600 font-medium">Pendientes</p>
                        <h3 class="text-3xl font-bold text-amber-700 mt-2">{{ $estadisticasSolicitudes['pendientes'] }}</h3>
                      </div>
                      <div class="p-3 bg-amber-200 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-700">
                          <circle cx="12" cy="12" r="10"></circle>
                          <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                      </div>
                    </div>
                  </div>
                  <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-sm border border-green-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                      <div>
                        <p class="text-sm text-slate-600 font-medium">Aprobadas</p>
                        <h3 class="text-3xl font-bold text-green-700 mt-2">{{ $estadisticasSolicitudes['aprobadas'] }}</h3>
                      </div>
                      <div class="p-3 bg-green-200 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-700">
                          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                          <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                      </div>
                    </div>
                  </div>
                  <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl shadow-sm border border-red-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                      <div>
                        <p class="text-sm text-slate-600 font-medium">Rechazadas</p>
                        <h3 class="text-3xl font-bold text-red-700 mt-2">{{ $estadisticasSolicitudes['rechazadas'] }}</h3>
                      </div>
                      <div class="p-3 bg-red-200 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-700">
                          <circle cx="12" cy="12" r="10"></circle>
                          <line x1="15" y1="9" x2="9" y2="15"></line>
                          <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Filtros y Búsqueda de Solicitudes -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
                  <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                      <label class="block text-sm font-medium text-slate-700 mb-2">Buscar</label>
                      <input type="text" id="buscarSolicitud" placeholder="Buscar por documento, correo o motivo..." 
                        class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div class="w-full md:w-48">
                      <label class="block text-sm font-medium text-slate-700 mb-2">Estado</label>
                      <select id="filtroEstado" class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
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
                      <thead class="bg-gradient-to-r from-slate-50 to-slate-100 border-b-2 border-slate-200">
                        <tr>
                          <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">ID</th>
                          <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Documento</th>
                          <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Correo</th>
                          <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Establecimiento</th>
                          <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Motivo</th>
                          <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Fecha</th>
                          <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Estado</th>
                          <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Usuario</th>
                          <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Acciones</th>
                        </tr>
                      </thead>
                      <tbody id="tablaSolicitudesBody" class="bg-white divide-y divide-slate-200">
                        @forelse($solicitudes as $solicitud)
                          <tr class="hover:bg-blue-50 transition-colors" id="solicitud-row-{{ $solicitud->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">#{{ $solicitud->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $solicitud->numero_documento }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $solicitud->correo }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ Str::limit($solicitud->id_establecimiento, 30) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ Str::limit($solicitud->motivo, 40) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $solicitud->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                              <span class="px-3 py-1.5 text-xs font-bold rounded-full
                                @if($solicitud->estado === 'pendiente') bg-amber-100 text-amber-800 border border-amber-300
                                @elseif($solicitud->estado === 'aprobada') bg-green-100 text-green-800 border border-green-300
                                @else bg-red-100 text-red-800 border border-red-300
                                @endif">
                                {{ ucfirst($solicitud->estado) }}
                              </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                              @if($solicitud->usuario)
                                <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-300">
                                  Creado
                                </span>
                                <a href="javascript:void(0)" onclick="cambiarTab('usuarios'); buscarUsuarioPorId({{ $solicitud->usuario->id }})" 
                                  class="text-blue-600 hover:text-blue-800 text-xs ml-2 font-semibold underline" title="Ver usuario">
                                  Ver
                                </a>
                              @else
                                <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-gray-100 text-gray-600 border border-gray-300">
                                  Sin usuario
                                </span>
                              @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                              <div class="flex items-center gap-2">
                                <button onclick="verDetalleSolicitud({{ $solicitud->id }})" 
                                  class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 font-semibold text-xs transition-colors">
                                  Ver
                                </button>
                                @if(!$solicitud->usuario)
                                  <button onclick="crearUsuarioDesdeSolicitud({{ $solicitud->id }})" 
                                    class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold text-xs transition-colors shadow-sm">
                                    Crear Usuario
                                  </button>
                                @endif
                                <button onclick="editarSolicitud({{ $solicitud->id }})" 
                                  class="px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 font-semibold text-xs transition-colors">
                                  Editar
                                </button>
                                <button onclick="eliminarSolicitud({{ $solicitud->id }})" 
                                  class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 font-semibold text-xs transition-colors">
                                  Eliminar
                                </button>
                              </div>
                            </td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                              <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400 mb-2">
                                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                  <polyline points="14 2 14 8 20 8"></polyline>
                                </svg>
                                <p class="text-slate-500 font-medium">No hay solicitudes registradas</p>
                              </div>
                            </td>
                          </tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                  <!-- Paginación -->
                  <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    {{ $solicitudes->links() }}
                  </div>
                </div>
              </div>

              <!-- Contenido de Usuarios -->
              <div id="contenido-usuarios" class="p-6 hidden">
                <!-- Estadísticas de Usuarios -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
                  <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-sm border border-blue-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                      <div>
                        <p class="text-sm text-slate-600 font-medium">Total Usuarios</p>
                        <h3 class="text-3xl font-bold text-blue-700 mt-2">{{ $estadisticas['total'] }}</h3>
                      </div>
                      <div class="p-3 bg-blue-200 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-700">
                          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                          <circle cx="9" cy="7" r="4"></circle>
                          <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                          <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                      </div>
                    </div>
                  </div>
                  <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-sm border border-purple-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                      <div>
                        <p class="text-sm text-slate-600 font-medium">Administradores</p>
                        <h3 class="text-3xl font-bold text-purple-700 mt-2">{{ $estadisticas['admin'] }}</h3>
                      </div>
                      <div class="p-3 bg-purple-200 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-700">
                          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                      </div>
                    </div>
                  </div>
                  <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-sm border border-green-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                      <div>
                        <p class="text-sm text-slate-600 font-medium">Jefes de Red</p>
                        <h3 class="text-3xl font-bold text-green-700 mt-2">{{ $estadisticas['jefe_red'] }}</h3>
                      </div>
                      <div class="p-3 bg-green-200 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-700">
                          <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                          <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                      </div>
                    </div>
                  </div>
                  <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl shadow-sm border border-amber-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                      <div>
                        <p class="text-sm text-slate-600 font-medium">Coordinadores</p>
                        <h3 class="text-3xl font-bold text-amber-700 mt-2">{{ $estadisticas['coordinador_microred'] }}</h3>
                      </div>
                      <div class="p-3 bg-amber-200 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-700">
                          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                          <circle cx="9" cy="7" r="4"></circle>
                          <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                          <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                      </div>
                    </div>
                  </div>
                  <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                      <div>
                        <p class="text-sm text-slate-600 font-medium">Usuarios</p>
                        <h3 class="text-3xl font-bold text-slate-700 mt-2">{{ $estadisticas['usuario'] }}</h3>
                      </div>
                      <div class="p-3 bg-slate-200 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-700">
                          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                          <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Filtros y Búsqueda de Usuarios -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
                  <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                      <label class="block text-sm font-medium text-slate-700 mb-2">Buscar</label>
                      <input type="text" id="buscarUsuario" placeholder="Buscar por nombre o correo..." 
                        class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div class="w-full md:w-48">
                      <label class="block text-sm font-medium text-slate-700 mb-2">Rol</label>
                      <select id="filtroRol" class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">Todos los roles</option>
                        <option value="admin">Administrador</option>
                        <option value="jefe_red">Jefe de Red</option>
                        <option value="coordinador_microred">Coordinador de Microred</option>
                        <option value="usuario">Usuario</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Tabla de Usuarios -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                  <div class="overflow-x-auto">
                    <table class="w-full">
                      <thead class="bg-gradient-to-r from-slate-50 to-slate-100 border-b-2 border-slate-200">
                        <tr>
                          <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">ID</th>
                          <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Nombre</th>
                          <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Correo</th>
                          <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Rol</th>
                          <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Solicitud</th>
                          <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Fecha Registro</th>
                          <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Acciones</th>
                        </tr>
                      </thead>
                      <tbody id="tablaUsuariosBody" class="bg-white divide-y divide-slate-200">
                        @forelse($usuarios as $usuario)
                          <tr class="hover:bg-blue-50 transition-colors" data-usuario-id="{{ $usuario->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">#{{ $usuario->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                              <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                  {{ strtoupper(substr($usuario->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-semibold text-slate-900">{{ $usuario->name }}</span>
                              </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $usuario->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                              <span class="px-3 py-1.5 text-xs font-bold rounded-full border
                                @if($usuario->role === 'admin') bg-purple-100 text-purple-800 border-purple-300
                                @elseif($usuario->role === 'jefe_red') bg-green-100 text-green-800 border-green-300
                                @elseif($usuario->role === 'coordinador_microred') bg-amber-100 text-amber-800 border-amber-300
                                @else bg-slate-100 text-slate-800 border-slate-300
                                @endif">
                                @if($usuario->role === 'admin') Administrador
                                @elseif($usuario->role === 'jefe_red') Jefe de Red
                                @elseif($usuario->role === 'coordinador_microred') Coordinador de Microred
                                @else Usuario
                                @endif
                              </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                              @if($usuario->solicitud)
                                <a href="javascript:void(0)" onclick="cambiarTab('solicitudes'); buscarSolicitudPorId({{ $usuario->solicitud->id }})" 
                                  class="text-blue-600 hover:text-blue-800 text-sm font-semibold underline">
                                  Ver Solicitud #{{ $usuario->solicitud->id }}
                                </a>
                              @else
                                <span class="text-slate-400 text-sm">-</span>
                              @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $usuario->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                              <div class="flex items-center gap-2">
                                <button onclick="editarUsuario({{ $usuario->id }})" 
                                  class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 font-semibold text-xs transition-colors">
                                  Editar
                                </button>
                                @if($usuario->id !== auth()->id())
                                  <button onclick="eliminarUsuario({{ $usuario->id }})" 
                                    class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 font-semibold text-xs transition-colors">
                                    Eliminar
                                  </button>
                                @endif
                              </div>
                            </td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                              <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400 mb-2">
                                  <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                  <circle cx="9" cy="7" r="4"></circle>
                                  <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                  <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <p class="text-slate-500 font-medium">No hay usuarios registrados</p>
                              </div>
                            </td>
                          </tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                  <!-- Paginación -->
                  <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    {{ $usuarios->links() }}
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Modal para crear/editar usuario -->
  <div id="modalUsuario" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
      <div class="p-6 border-b border-slate-200 flex justify-between items-center">
        <h3 id="modalUsuarioTitulo" class="text-xl font-bold text-slate-800">Nuevo Usuario</h3>
        <button onclick="cerrarModalUsuario()" class="text-slate-400 hover:text-slate-600">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18"></path>
            <path d="m6 6 12 12"></path>
          </svg>
        </button>
      </div>
      <form id="formUsuario" class="p-6 space-y-4">
        <input type="hidden" id="usuarioId" name="id">
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-2">Nombre</label>
          <input type="text" id="usuarioNombre" name="name" required
            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-2">Correo</label>
          <input type="email" id="usuarioCorreo" name="email" required
            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-2">Contraseña</label>
          <input type="password" id="usuarioPassword" name="password"
            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
          <p class="text-xs text-slate-500 mt-1">Dejar en blanco para mantener la actual (solo al editar)</p>
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-2">Confirmar Contraseña</label>
          <input type="password" id="usuarioPasswordConfirm" name="password_confirmation"
            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-2">Rol</label>
          <select id="usuarioRol" name="role" required
            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="usuario">Usuario</option>
            <option value="coordinador_microred">Coordinador de Microred</option>
            <option value="jefe_red">Jefe de Red</option>
            <option value="admin">Administrador</option>
          </select>
        </div>
        <div class="flex gap-3 pt-4">
          <button type="button" onclick="cerrarModalUsuario()" 
            class="flex-1 px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 transition-colors">
            Cancelar
          </button>
          <button type="submit" 
            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
            Guardar
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal para ver detalles de solicitud -->
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
    // Función para cambiar entre pestañas
    function cambiarTab(tab) {
      const tabUsuarios = document.getElementById('tab-usuarios');
      const tabSolicitudes = document.getElementById('tab-solicitudes');
      const contenidoUsuarios = document.getElementById('contenido-usuarios');
      const contenidoSolicitudes = document.getElementById('contenido-solicitudes');

      if (tab === 'usuarios') {
        tabUsuarios.classList.add('text-blue-600', 'border-blue-600', 'bg-blue-50');
        tabUsuarios.classList.remove('text-slate-600', 'border-transparent');
        tabSolicitudes.classList.remove('text-blue-600', 'border-blue-600', 'bg-blue-50');
        tabSolicitudes.classList.add('text-slate-600', 'border-transparent');
        contenidoUsuarios.classList.remove('hidden');
        contenidoSolicitudes.classList.add('hidden');
        // Mostrar botón de usuario, ocultar de solicitud
        document.getElementById('btnCrearUsuario').classList.remove('hidden');
        document.getElementById('btnCrearSolicitud').classList.add('hidden');
      } else {
        tabSolicitudes.classList.add('text-blue-600', 'border-blue-600', 'bg-blue-50');
        tabSolicitudes.classList.remove('text-slate-600', 'border-transparent');
        tabUsuarios.classList.remove('text-blue-600', 'border-blue-600', 'bg-blue-50');
        tabUsuarios.classList.add('text-slate-600', 'border-transparent');
        contenidoSolicitudes.classList.remove('hidden');
        contenidoUsuarios.classList.add('hidden');
        // Mostrar botón de solicitud, ocultar de usuario
        document.getElementById('btnCrearSolicitud').classList.remove('hidden');
        document.getElementById('btnCrearUsuario').classList.add('hidden');
      }
    }

    // Inicializar con solicitudes primero por defecto
    (function() {
      const urlParams = new URLSearchParams(window.location.search);
      const tab = urlParams.get('tab');
      if (tab === 'usuarios') {
        cambiarTab('usuarios');
      } else {
        // Por defecto mostrar solicitudes primero
        cambiarTab('solicitudes');
      }
    })();

    function buscarSolicitudPorId(id) {
      cambiarTab('solicitudes');
      setTimeout(() => {
        const row = document.getElementById('solicitud-row-' + id);
        if (row) {
          row.scrollIntoView({ behavior: 'smooth', block: 'center' });
          row.classList.add('bg-blue-50');
          setTimeout(() => row.classList.remove('bg-blue-50'), 2000);
        }
      }, 100);
    }

    function buscarUsuarioPorId(id) {
      cambiarTab('usuarios');
      setTimeout(() => {
        const row = document.querySelector(`tr[data-usuario-id="${id}"]`);
        if (row) {
          row.scrollIntoView({ behavior: 'smooth', block: 'center' });
          row.classList.add('bg-blue-50');
          setTimeout(() => row.classList.remove('bg-blue-50'), 2000);
        }
      }, 100);
    }

    function abrirModalCrearUsuario() {
      document.getElementById('modalUsuarioTitulo').textContent = 'Nuevo Usuario';
      document.getElementById('formUsuario').reset();
      document.getElementById('usuarioId').value = '';
      document.getElementById('usuarioPassword').required = true;
      document.getElementById('usuarioPasswordConfirm').required = true;
      document.getElementById('modalUsuario').classList.remove('hidden');
    }

    function editarUsuario(id) {
      fetch(`/usuarios/${id}`, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const u = data.data;
          document.getElementById('modalUsuarioTitulo').textContent = 'Editar Usuario';
          document.getElementById('usuarioId').value = u.id;
          document.getElementById('usuarioNombre').value = u.name;
          document.getElementById('usuarioCorreo').value = u.email;
          document.getElementById('usuarioRol').value = u.role;
          document.getElementById('usuarioPassword').required = false;
          document.getElementById('usuarioPasswordConfirm').required = false;
          document.getElementById('modalUsuario').classList.remove('hidden');
        }
      })
      .catch(error => {
        alert('Error al cargar el usuario');
      });
    }

    function cerrarModalUsuario() {
      document.getElementById('modalUsuario').classList.add('hidden');
    }

    function eliminarUsuario(id) {
      if (!confirm('¿Está seguro de eliminar este usuario?')) {
        return;
      }

      fetch(`/usuarios/${id}`, {
        method: 'DELETE',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload();
        } else {
          alert(data.message || 'Error al eliminar el usuario');
        }
      })
      .catch(error => {
        alert('Error al eliminar el usuario');
      });
    }

    document.getElementById('formUsuario').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const data = Object.fromEntries(formData);
      const id = document.getElementById('usuarioId').value;
      const url = id ? `/usuarios/${id}` : '/usuarios';
      const method = id ? 'PUT' : 'POST';

      fetch(url, {
        method: method,
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload();
        } else {
          alert(data.message || 'Error al guardar el usuario');
          if (data.errors) {
            console.error(data.errors);
          }
        }
      })
      .catch(error => {
        alert('Error al guardar el usuario');
      });
    });

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
                    <button onclick="cambiarTab('usuarios'); buscarUsuarioPorId(${s.usuario_creado.id}); cerrarModalDetalle();" class="text-blue-600 hover:text-blue-800 text-sm mt-2">Ver usuario →</button>
                  </div>
                </div>
                ` : !s.usuario_creado ? `
                <div class="col-span-2 border-t pt-4 mt-4">
                  <button onclick="crearUsuarioDesdeSolicitud(${s.id}); cerrarModalDetalle();" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold">
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

    function cerrarModalDetalle() {
      document.getElementById('modalDetalleSolicitud').classList.add('hidden');
    }

    function abrirModalCrearSolicitud() {
      document.getElementById('modalSolicitudTitulo').textContent = 'Nueva Solicitud';
      document.getElementById('formSolicitud').reset();
      document.getElementById('solicitudId').value = '';
      document.getElementById('modalSolicitud').classList.remove('hidden');
    }

    function editarSolicitud(id) {
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
          document.getElementById('modalSolicitudTitulo').textContent = 'Editar Solicitud';
          document.getElementById('solicitudId').value = s.id;
          
          // Mapear tipo de documento
          const tipoDocMap = {'DNI': 1, 'CE': 2, 'PASS': 3, 'DIE': 4, 'S/ DOCUMENTO': 5, 'CNV': 6};
          const tipoDocValue = Object.keys(tipoDocMap).find(key => key === s.tipo_documento);
          if (tipoDocValue) {
            document.getElementById('solicitudTipoDoc').value = tipoDocMap[tipoDocValue];
          }
          
          document.getElementById('solicitudNumeroDoc').value = s.numero_documento;
          
          // Mapear red
          const redMap = {
            'AGUAYTIA': 1, 'ATALAYA': 2, 'BAP-CURARAY': 3, 'CORONEL PORTILLO': 4,
            'ESSALUD': 5, 'FEDERICO BASADRE - YARINACOCHA': 6,
            'HOSPITAL AMAZONICO - YARINACOCHA': 7, 'HOSPITAL REGIONAL DE PUCALLPA': 8,
            'NO PERTENECE A NINGUNA RED': 9
          };
          const redValue = Object.keys(redMap).find(key => key === s.red);
          if (redValue) {
            document.getElementById('solicitudRed').value = redMap[redValue];
          }
          
          document.getElementById('solicitudMicrored').value = s.microred;
          document.getElementById('solicitudEstablecimiento').value = s.establecimiento;
          document.getElementById('solicitudMotivo').value = s.motivo;
          document.getElementById('solicitudCargo').value = s.cargo;
          document.getElementById('solicitudCelular').value = s.celular;
          document.getElementById('solicitudCorreo').value = s.correo;
          
          document.getElementById('modalSolicitud').classList.remove('hidden');
        }
      })
      .catch(error => {
        alert('Error al cargar la solicitud');
      });
    }

    function eliminarSolicitud(id) {
      if (!confirm('¿Está seguro de eliminar esta solicitud?')) {
        return;
      }

      fetch(`/solicitudes/${id}`, {
        method: 'DELETE',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload();
        } else {
          alert(data.message || 'Error al eliminar la solicitud');
        }
      })
      .catch(error => {
        alert('Error al eliminar la solicitud');
      });
    }

    function cerrarModalSolicitud() {
      document.getElementById('modalSolicitud').classList.add('hidden');
    }

    function abrirModalCrearSolicitud() {
      document.getElementById('modalSolicitudTitulo').textContent = 'Nueva Solicitud';
      document.getElementById('formSolicitud').reset();
      document.getElementById('solicitudId').value = '';
      document.getElementById('modalSolicitud').classList.remove('hidden');
    }

    function editarSolicitud(id) {
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
          document.getElementById('modalSolicitudTitulo').textContent = 'Editar Solicitud';
          document.getElementById('solicitudId').value = s.id;
          
          // Mapear tipo de documento
          const tipoDocMap = {'DNI': 1, 'CE': 2, 'PASS': 3, 'DIE': 4, 'S/ DOCUMENTO': 5, 'CNV': 6};
          const tipoDocKey = Object.keys(tipoDocMap).find(key => key === s.tipo_documento);
          if (tipoDocKey) {
            document.getElementById('solicitudTipoDoc').value = tipoDocMap[tipoDocKey];
          }
          
          document.getElementById('solicitudNumeroDoc').value = s.numero_documento;
          
          // Mapear red
          const redMap = {
            'AGUAYTIA': 1, 'ATALAYA': 2, 'BAP-CURARAY': 3, 'CORONEL PORTILLO': 4,
            'ESSALUD': 5, 'FEDERICO BASADRE - YARINACOCHA': 6,
            'HOSPITAL AMAZONICO - YARINACOCHA': 7, 'HOSPITAL REGIONAL DE PUCALLPA': 8,
            'NO PERTENECE A NINGUNA RED': 9
          };
          const redKey = Object.keys(redMap).find(key => key === s.red);
          if (redKey) {
            document.getElementById('solicitudRed').value = redMap[redKey];
          }
          
          document.getElementById('solicitudMicrored').value = s.microred;
          document.getElementById('solicitudEstablecimiento').value = s.establecimiento;
          document.getElementById('solicitudMotivo').value = s.motivo;
          document.getElementById('solicitudCargo').value = s.cargo;
          document.getElementById('solicitudCelular').value = s.celular;
          document.getElementById('solicitudCorreo').value = s.correo;
          
          document.getElementById('modalSolicitud').classList.remove('hidden');
        }
      })
      .catch(error => {
        alert('Error al cargar la solicitud');
      });
    }

    function eliminarSolicitud(id) {
      if (!confirm('¿Está seguro de eliminar esta solicitud?')) {
        return;
      }

      fetch(`/solicitudes/${id}`, {
        method: 'DELETE',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload();
        } else {
          alert(data.message || 'Error al eliminar la solicitud');
        }
      })
      .catch(error => {
        alert('Error al eliminar la solicitud');
      });
    }

    function cerrarModalSolicitud() {
      document.getElementById('modalSolicitud').classList.add('hidden');
    }

    // Manejar formulario de solicitud
    document.getElementById('formSolicitud').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const data = Object.fromEntries(formData);
      const id = document.getElementById('solicitudId').value;
      const url = id ? `/solicitudes/${id}` : '/solicitudes';
      const method = id ? 'PUT' : 'POST';

      fetch(url, {
        method: method,
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Solicitud guardada correctamente');
          location.reload();
        } else {
          alert(data.message || 'Error al guardar la solicitud');
          if (data.errors) {
            console.error(data.errors);
          }
        }
      })
      .catch(error => {
        alert('Error al guardar la solicitud');
        console.error(error);
      });
    });

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

    // Filtros de usuarios
    document.getElementById('buscarUsuario')?.addEventListener('keyup', function(e) {
      if (e.key === 'Enter') {
        const buscar = this.value;
        const rol = document.getElementById('filtroRol').value;
        window.location.href = `/usuarios?buscar=${buscar}&rol=${rol}`;
      }
    });

    document.getElementById('filtroRol')?.addEventListener('change', function() {
      const buscar = document.getElementById('buscarUsuario').value;
      const rol = this.value;
      window.location.href = `/usuarios?buscar=${buscar}&rol=${rol}`;
    });

    // Filtros de solicitudes
    document.getElementById('buscarSolicitud')?.addEventListener('keyup', function(e) {
      if (e.key === 'Enter') {
        const buscar = this.value;
        const estado = document.getElementById('filtroEstado').value;
        window.location.href = `/usuarios?tab=solicitudes&buscar_solicitud=${buscar}&estado=${estado}`;
      }
    });

    document.getElementById('filtroEstado')?.addEventListener('change', function() {
      const buscar = document.getElementById('buscarSolicitud').value;
      const estado = this.value;
      window.location.href = `/usuarios?tab=solicitudes&buscar_solicitud=${buscar}&estado=${estado}`;
    });

  </script>
</body>
</html>
