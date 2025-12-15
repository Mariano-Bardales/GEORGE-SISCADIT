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
            <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
              <h1 class="text-4xl font-bold text-slate-800">Dashboard General</h1>
              <p class="text-slate-600 mt-2">Nivel de acceso: <span class="font-semibold">
                  @php
                    $role = strtolower(auth()->user()->role ?? '');
                  @endphp
                  @if($role === 'admin')
                  Administrador DIRESA
                  @elseif($role === 'jefe_red' || $role === 'jefedered' || $role === 'jefe_microred')
                  Jefe de Red
                  @elseif($role === 'coordinador_microred' || $role === 'coordinadordemicrored' || $role === 'coordinador_red')
                    Coordinador de Micro Red
                @else
                  {{ ucfirst(auth()->user()->role ?? 'Usuario') }}
                @endif
              </span></p>
              </div>
              @php
                $userRole = strtolower(auth()->user()->role ?? '');
                $isAdmin = ($userRole === 'admin' || $userRole === 'administrator');
              @endphp
              @if($isAdmin)
              <button onclick="confirmarEliminarTodosDatos(event)" 
                style="background-color: #dc2626; color: white; padding: 12px 24px; border-radius: 12px; font-weight: 600; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 8px; border: none; cursor: pointer; font-size: 16px;"
                onmouseover="this.style.backgroundColor='#b91c1c'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'"
                onmouseout="this.style.backgroundColor='#dc2626'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="3 6 5 6 21 6"></polyline>
                  <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                  <line x1="10" y1="11" x2="10" y2="17"></line>
                  <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
                Eliminar Datos Registrados de los Niños
              </button>
              @endif
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

    /* Estilos para modales personalizados */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.75);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      animation: fadeIn 0.2s ease-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes slideUp {
      from { 
        opacity: 0;
        transform: translateY(20px) scale(0.95);
      }
      to { 
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    .modal-container {
      background: white;
      border-radius: 16px;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      max-width: 500px;
      width: 90%;
      max-height: 90vh;
      overflow-y: auto;
      animation: slideUp 0.3s ease-out;
    }

    .modal-header {
      padding: 24px;
      border-bottom: 1px solid #e5e7eb;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .modal-header.warning {
      background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
      border-bottom: 2px solid #f59e0b;
    }

    .modal-header.success {
      background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
      border-bottom: 2px solid #10b981;
    }

    .modal-header.error {
      background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
      border-bottom: 2px solid #ef4444;
    }

    .modal-icon {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      flex-shrink: 0;
    }

    .modal-icon.warning {
      background: #fbbf24;
      color: white;
    }

    .modal-icon.success {
      background: #10b981;
      color: white;
    }

    .modal-icon.error {
      background: #ef4444;
      color: white;
    }

    .modal-title {
      font-size: 20px;
      font-weight: 700;
      color: #1f2937;
      margin: 0;
    }

    .modal-body {
      padding: 24px;
    }

    .modal-message {
      color: #4b5563;
      line-height: 1.6;
      margin-bottom: 16px;
    }

    .modal-list {
      list-style: none;
      padding: 0;
      margin: 16px 0;
    }

    .modal-list li {
      padding: 8px 0;
      padding-left: 24px;
      position: relative;
      color: #374151;
    }

    .modal-list li:before {
      content: "•";
      position: absolute;
      left: 8px;
      color: #dc2626;
      font-weight: bold;
      font-size: 18px;
    }

    .modal-input {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid #e5e7eb;
      border-radius: 8px;
      font-size: 16px;
      margin-top: 12px;
      transition: border-color 0.2s;
    }

    .modal-input:focus {
      outline: none;
      border-color: #dc2626;
      box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .modal-footer {
      padding: 16px 24px;
      border-top: 1px solid #e5e7eb;
      display: flex;
      gap: 12px;
      justify-content: flex-end;
    }

    .modal-button {
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.2s;
      border: none;
    }

    .modal-button-primary {
      background: #dc2626;
      color: white;
    }

    .modal-button-primary:hover {
      background: #b91c1c;
      transform: translateY(-1px);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .modal-button-secondary {
      background: #f3f4f6;
      color: #374151;
    }

    .modal-button-secondary:hover {
      background: #e5e7eb;
    }

    .modal-button:disabled {
      opacity: 0.5;
      cursor: not-allowed;
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

    // Función para crear modal personalizado
    function crearModal(tipo, titulo, mensaje, opciones = {}) {
      return new Promise((resolve) => {
        const overlay = document.createElement('div');
        overlay.className = 'modal-overlay';
        
        const container = document.createElement('div');
        container.className = 'modal-container';
        
        const header = document.createElement('div');
        header.className = `modal-header ${tipo}`;
        
        const icon = document.createElement('div');
        icon.className = `modal-icon ${tipo}`;
        if (tipo === 'warning') icon.innerHTML = '⚠️';
        else if (tipo === 'success') icon.innerHTML = '✅';
        else if (tipo === 'error') icon.innerHTML = '❌';
        
        const title = document.createElement('h3');
        title.className = 'modal-title';
        title.textContent = titulo;
        
        header.appendChild(icon);
        header.appendChild(title);
        
        const body = document.createElement('div');
        body.className = 'modal-body';
        
        const message = document.createElement('p');
        message.className = 'modal-message';
        message.innerHTML = mensaje;
        body.appendChild(message);
        
        // Si hay lista de items
        if (opciones.items) {
          const list = document.createElement('ul');
          list.className = 'modal-list';
          opciones.items.forEach(item => {
            const li = document.createElement('li');
            li.textContent = item;
            list.appendChild(li);
          });
          body.appendChild(list);
        }
        
        // Si necesita input
        let inputElement = null;
        if (opciones.necesitaInput) {
          const input = document.createElement('input');
          input.type = 'text';
          input.className = 'modal-input';
          input.placeholder = opciones.placeholder || '';
          input.autocomplete = 'off';
          inputElement = input;
          body.appendChild(input);
        }
        
        const footer = document.createElement('div');
        footer.className = 'modal-footer';
        
        const btnCancelar = document.createElement('button');
        btnCancelar.className = 'modal-button modal-button-secondary';
        btnCancelar.textContent = opciones.textoCancelar || 'Cancelar';
        btnCancelar.onclick = () => {
          document.body.removeChild(overlay);
          resolve(false);
        };
        
        const btnConfirmar = document.createElement('button');
        btnConfirmar.className = 'modal-button modal-button-primary';
        btnConfirmar.textContent = opciones.textoConfirmar || 'Confirmar';
        btnConfirmar.onclick = () => {
          if (opciones.necesitaInput) {
            if (inputElement.value.trim() === opciones.textoEsperado) {
              document.body.removeChild(overlay);
              resolve(true);
            } else {
              inputElement.style.borderColor = '#ef4444';
              inputElement.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
              // Mostrar mensaje de error temporal
              const errorMsg = document.createElement('p');
              errorMsg.style.color = '#ef4444';
              errorMsg.style.fontSize = '14px';
              errorMsg.style.marginTop = '8px';
              errorMsg.style.marginBottom = '0';
              errorMsg.textContent = '❌ El texto no coincide. Debe escribir exactamente: ' + opciones.textoEsperado;
              
              // Remover mensaje anterior si existe
              const errorAnterior = body.querySelector('.error-message');
              if (errorAnterior) {
                body.removeChild(errorAnterior);
              }
              
              errorMsg.className = 'error-message';
              body.appendChild(errorMsg);
              
              inputElement.focus();
              inputElement.select();
            }
          } else {
            document.body.removeChild(overlay);
            resolve(true);
          }
        };
        
        footer.appendChild(btnCancelar);
        footer.appendChild(btnConfirmar);
        
        container.appendChild(header);
        container.appendChild(body);
        container.appendChild(footer);
        overlay.appendChild(container);
        
        document.body.appendChild(overlay);
        
        // Focus en input si existe
        if (inputElement) {
          setTimeout(() => inputElement.focus(), 100);
        }
        
        // Cerrar con ESC
        const handleEsc = (e) => {
          if (e.key === 'Escape') {
            document.body.removeChild(overlay);
            document.removeEventListener('keydown', handleEsc);
            resolve(false);
          }
        };
        document.addEventListener('keydown', handleEsc);
      });
    }

    // Función para confirmar y eliminar todos los datos
    async function confirmarEliminarTodosDatos(event) {
      if (event) {
        event.preventDefault();
      }

      // Primera confirmación
      const primeraConfirmacion = await crearModal(
        'warning',
        '⚠️ ADVERTENCIA CRÍTICA',
        'Está a punto de eliminar <strong>TODOS</strong> los datos registrados de los niños. Esta acción <strong>NO SE PUEDE DESHACER</strong>.',
        {
          items: [
            'Todos los niños registrados',
            'Todos los controles (RN y CRED)',
            'Todas las madres',
            'Todos los datos extras',
            'Todos los tamizajes',
            'Todas las vacunas',
            'Todas las visitas domiciliarias',
            'Todos los CNV'
          ],
          textoCancelar: 'Cancelar',
          textoConfirmar: 'Continuar'
        }
      );

      if (!primeraConfirmacion) {
        return;
      }

      // Segunda confirmación con texto que debe escribir
      const segundaConfirmacion = await crearModal(
        'warning',
        '🔒 Confirmación Requerida',
        'Para confirmar esta acción destructiva, escriba exactamente el siguiente texto:<br><br><strong style="color: #dc2626; font-size: 18px; letter-spacing: 1px;">ELIMINAR TODO</strong>',
        {
          necesitaInput: true,
          placeholder: 'Escriba: ELIMINAR TODO',
          textoEsperado: 'ELIMINAR TODO',
          textoCancelar: 'Cancelar',
          textoConfirmar: 'Verificar'
        }
      );

      if (!segundaConfirmacion) {
        return;
      }

      // Tercera confirmación final
      const confirmacionFinal = await crearModal(
        'error',
        '🚨 ÚLTIMA CONFIRMACIÓN',
        'Está a punto de eliminar <strong>PERMANENTEMENTE</strong> todos los datos registrados de los niños.<br><br>Esta acción es <strong>IRREVERSIBLE</strong> y no se puede deshacer.',
        {
          textoCancelar: 'Cancelar',
          textoConfirmar: 'SÍ, ELIMINAR TODO'
        }
      );

      if (!confirmacionFinal) {
        return;
      }

      // Mostrar loading
      const boton = event.target.closest('button');
      const textoOriginal = boton.innerHTML;
      boton.disabled = true;
      boton.innerHTML = '<svg class="animate-spin" style="width: 20px; height: 20px; display: inline-block; margin-right: 8px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Eliminando...';

      // Realizar la petición
      try {
        const response = await fetch('{{ route("admin.eliminar-todos-datos") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          credentials: 'same-origin'
        });

        const data = await response.json();

        if (data.success) {
          // Mostrar mensaje de éxito
          const overlay = document.createElement('div');
          overlay.className = 'modal-overlay';
          
          const container = document.createElement('div');
          container.className = 'modal-container';
          
          const header = document.createElement('div');
          header.className = 'modal-header success';
          
          const icon = document.createElement('div');
          icon.className = 'modal-icon success';
          icon.innerHTML = '✅';
          
          const title = document.createElement('h3');
          title.className = 'modal-title';
          title.textContent = '✅ Eliminación Exitosa';
          
          header.appendChild(icon);
          header.appendChild(title);
          
          const body = document.createElement('div');
          body.className = 'modal-body';
          
          const message = document.createElement('p');
          message.className = 'modal-message';
          message.innerHTML = 'Todos los datos registrados de los niños han sido eliminados exitosamente.<br><br><strong>La página se recargará automáticamente...</strong>';
          body.appendChild(message);
          
          container.appendChild(header);
          container.appendChild(body);
          overlay.appendChild(container);
          
          document.body.appendChild(overlay);
          
          // Recargar la página después de 2 segundos
          setTimeout(() => {
            window.location.href = window.location.href;
          }, 2000);
        } else {
          await crearModal(
            'error',
            '❌ Error al Eliminar',
            'Ocurrió un error al intentar eliminar los datos:<br><br><strong>' + (data.message || 'Error desconocido') + '</strong>',
            {
              textoCancelar: '',
              textoConfirmar: 'Cerrar'
            }
          );
          boton.disabled = false;
          boton.innerHTML = textoOriginal;
        }
      } catch (error) {
        console.error('Error:', error);
        await crearModal(
          'error',
          '❌ Error de Conexión',
          'No se pudo conectar con el servidor. Por favor, verifique su conexión e intente nuevamente.',
          {
            textoCancelar: '',
            textoConfirmar: 'Cerrar'
          }
        );
        boton.disabled = false;
        boton.innerHTML = textoOriginal;
      }
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
