<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#000000">
  <meta name="description" content="Sistema de Control y Alerta de Etapas de Vida del Niño - SISCADIT">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>SISCADIT - Dashboard</title>
  <link rel="stylesheet" href="{{ asset('Css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/dashbord.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/dashboard-main.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  @stack('styles')
</head>
<body>
  <noscript>You need to enable JavaScript to run this app.</noscript>
  <div id="root">
    <div class="flex h-screen bg-slate-50 relative">
      <x-sidebar-main activeRoute="dashboard" />
      <main class="flex-1 overflow-auto">
        <div class="p-8">
          <div class="space-y-8" data-testid="dashboard-page">
            <div>
              <h1 class="text-4xl font-bold text-slate-800">Dashboard General</h1>
              <p class="text-slate-600 mt-2">Nivel de acceso: <span class="font-semibold">
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'ADMIN')
                  Administrador DIRESA
                @elseif(auth()->user()->role === 'jefe_red' || auth()->user()->role === 'JefeDeRed')
                  Jefe de Red
                @elseif(auth()->user()->role === 'coordinador_microred' || auth()->user()->role === 'CoordinadorDeMicroRed')
                  Coordinador de Microred
                @else
                  {{ ucfirst(auth()->user()->role ?? 'Usuario') }}
                @endif
              </span></p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div data-testid="stat-card-total-registrados"
                class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                  <div>
                    <p class="text-sm text-slate-600 font-medium">Total Registrados</p>
                    <h3 class="text-4xl font-bold text-slate-800 mt-2">0</h3>
                    <p class="text-xs text-slate-500 mt-2">Niños registrados en el sistema</p>
                  </div>
                  <div class="p-3 bg-purple-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-baby w-6 h-6" style="color: rgb(147, 51, 234);">
                      <path d="M9 12h.01"></path>
                      <path d="M15 12h.01"></path>
                      <path d="M10 16c.5.3 1.2.5 2 .5s1.5-.2 2-.5"></path>
                      <path d="M19 6.3a9 9 0 0 1 1.8 3.9 2 2 0 0 1 0 3.6 9 9 0 0 1-17.6 0 2 2 0 0 1 0-3.6A9 9 0 0 1 12 3a4 4 0 0 1 2 1 4 4 0 0 1 2 1 4 4 0 0 1 2 1 4 4 0 0 1 2 1z"></path>
                      <path d="M7.5 6.3a9 9 0 0 1 2-1 4 4 0 0 1 2-1 4 4 0 0 1 2-1 4 4 0 0 1 2-1"></path>
                    </svg>
                  </div>
                </div>
              </div>
              <div data-testid="stat-card-total-usuarios"
                class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                  <div>
                    <p class="text-sm text-slate-600 font-medium">Total Usuarios</p>
                    <h3 class="text-4xl font-bold text-slate-800 mt-2">0</h3>
                    <p class="text-xs text-slate-500 mt-2">Usuarios activos del sistema</p>
                  </div>
                  <div class="p-3 bg-green-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-6 h-6" style="color: rgb(16, 185, 129);">
                      <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                      <path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
                      <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                      <circle cx="9" cy="7" r="4"></circle>
                    </svg>
                  </div>
                </div>
              </div>
              <div data-testid="stat-card-alertas-detectadas"
                class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                  <div>
                    <p class="text-sm text-slate-600 font-medium">Alertas Detectadas</p>
                    <h3 id="contadorAlertasDashboard" class="text-4xl font-bold text-slate-800 mt-2">0</h3>
                    <p class="text-xs text-slate-500 mt-2">Errores en registros</p>
                  </div>
                  <div class="p-3 bg-amber-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert w-6 h-6" style="color: rgb(245, 158, 11);">
                      <circle cx="12" cy="12" r="10"></circle>
                      <line x1="12" x2="12" y1="8" y2="12"></line>
                      <line x1="12" x2="12.01" y1="16" y2="16"></line>
                    </svg>
                  </div>
                </div>
              </div>
            </div>
            <!-- Gráfico: Distribución por Género -->
            <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 mt-6">
              <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-6">
                  <div class="p-2 bg-purple-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-6 h-6 text-purple-600">
                      <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                      <circle cx="9" cy="7" r="4"></circle>
                      <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                      <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-lg font-bold text-slate-800">Distribución por Género</h3>
                    <p class="text-sm text-slate-500">Masculino y Femenino</p>
                  </div>
                </div>
                <div style="position: relative; height: 300px;">
                  <canvas id="chartGenero"></canvas>
                </div>
              </div>
            </div>
            <!-- Tabla de Datos de Controles CRED -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mt-6">
              <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                  <div class="p-2 bg-blue-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text w-6 h-6 text-blue-600">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                      <polyline points="14 2 14 8 20 8"></polyline>
                      <line x1="16" x2="8" y1="13" y2="13"></line>
                      <line x1="16" x2="8" y1="17" y2="17"></line>
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-lg font-bold text-slate-800">Datos de Controles CRED</h3>
                    <p class="text-sm text-slate-500">Últimos 10 registros del sistema</p>
                  </div>
                </div>
                <a href="{{ route('controles-cred') }}" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all text-sm font-semibold flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                    <polyline points="10 17 15 12 10 7"></polyline>
                    <line x1="15" x2="3" y1="12" y2="12"></line>
                  </svg>
                  Ver Completo
                </a>
              </div>
              <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                  <thead>
                    <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                      <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Establecimiento</th>
                      <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Documento</th>
                      <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Apellidos y Nombres</th>
                      <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">F. Nacimiento</th>
                      <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Género</th>
                      <th style="padding: 12px; text-align: center; font-weight: 600; font-size: 13px; text-transform: uppercase;">Controles</th>
                    </tr>
                  </thead>
                  <tbody id="tablaControlesBody">
                    <tr>
                      <td colspan="6" style="padding: 24px; text-align: center; color: #64748b;">
                        <div class="spinner" style="margin: 0 auto; border: 4px solid #f3f4f6; border-top: 4px solid #3b82f6; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite;"></div>
                        <p style="margin-top: 1rem;">Cargando datos de controles...</p>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div style="padding: 1rem; border-top: 1px solid #e5e7eb; background: #f9fafb; display: flex; justify-content: space-between; align-items: center;">
                <div style="font-size: 0.875rem; color: #64748b;">
                  Mostrando los últimos <span id="totalControlesTabla" style="font-weight: 600; color: #1e293b;">0</span> registros del sistema
                </div>
                <a href="{{ route('controles-cred') }}" style="color: #667eea; text-decoration: none; font-weight: 600; font-size: 0.875rem;">
                  Ver todos los controles →
                </a>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
  <style>
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    .spinner {
      border: 4px solid #f3f4f6;
      border-top: 4px solid #3b82f6;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      animation: spin 1s linear infinite;
    }
  </style>
  <script>
    // Definir rutas como variables globales
    window.dashboardRoutes = {
      stats: '{{ route("api.dashboard.stats") }}',
      reportes: '{{ route("api.reportes.estadisticas") }}',
      ninos: '{{ route("api.ninos") }}',
      controlesRn: '{{ route("api.controles-recien-nacido") }}',
      controlesCred: '{{ route("api.controles-cred-mensual") }}',
      tamizaje: '{{ route("api.tamizaje") }}',
      vacunas: '{{ route("api.vacunas") }}',
      alertasTotal: '{{ route("api.alertas.total") }}',
    };

    // Función para cargar el contador de alertas en el dashboard
    function cargarContadorAlertasDashboard() {
      const contadorElement = document.getElementById('contadorAlertasDashboard');
      if (!contadorElement) return;
      
      const url = window.dashboardRoutes.alertasTotal;
      
      fetch(url, {
        method: 'GET',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        credentials: 'same-origin'
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
      })
      .then(data => {
        if (data.success && data.total !== undefined) {
          const total = data.total;
          contadorElement.textContent = total;
        } else {
          contadorElement.textContent = '0';
        }
      })
      .catch(error => {
        console.error('Error al cargar contador de alertas:', error);
        contadorElement.textContent = '0';
      });
    }

    // Cargar contador de alertas al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
      cargarContadorAlertasDashboard();
      
      // Actualizar cada 5 minutos
      setInterval(cargarContadorAlertasDashboard, 300000);
    });
  </script>
  <script src="{{ asset('JS/dashbord.js') }}"></script>
</body>
</html>
