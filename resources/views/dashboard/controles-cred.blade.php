<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#000000">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="Sistema de Control y Alerta de Etapas de Vida del Niño - SISCADIT">
  <title>SISCADIT - Controles CRED</title>
  <link rel="stylesheet" href="{{ asset('Css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/dashbord.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/dashboard-main.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/modal-agregar-nino.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/modal-ver-controles.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/modal-registro-controles.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/modal-importar-controles.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/modal-datos-extras.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/modal-advertencia-agregar-nino.css') }}">
  @stack('styles')
  <link rel="stylesheet" href="{{ asset('Css/modal-eliminar-nino.css') }}">
</head>

<body>
  <noscript>You need to enable JavaScript to run this app.</noscript>
  <div id="root">
    <div x-file-name="index" x-line-number="9" x-component="App" x-id="index_9" x-dynamic="true"
      data-debug-wrapper="true" style="display: contents;">
      <div class="App" x-file-name="App" x-line-number="13" x-component="div" x-id="App_13" x-dynamic="false">
        <div x-file-name="App" x-line-number="14" x-component="BrowserRouter" x-id="App_14" x-dynamic="false"
          data-debug-wrapper="true" style="display: contents;">
          <div x-file-name="App" x-line-number="16" x-component="Layout" x-id="App_16" x-dynamic="true"
            data-debug-wrapper="true" style="display: contents;">
            <div class="flex h-screen bg-slate-50 relative" x-file-name="Layout" x-line-number="18" x-component="div"
              x-id="Layout_18" x-dynamic="false">
              <x-sidebar-main activeRoute="controles-cred" />
              <main class="flex-1 overflow-auto" x-file-name="Layout" x-line-number="123" x-component="main"
                x-id="Layout_123" x-dynamic="false">
                <div class="p-8" x-file-name="Layout" x-line-number="124" x-component="div" x-id="Layout_124"
                  x-dynamic="false">
                  <div class="space-y-6" data-testid="controles-cred-page">
                    <!-- Header Section -->
                    <div class="flex items-center justify-between flex-wrap gap-6">
                      <div>
                        <h1 class="text-4xl font-bold text-slate-700 mb-1">Controles CRED</h1>
                        <p class="text-slate-500 text-base">Gestión de controles de crecimiento y desarrollo</p>
                      </div>
                    </div>

                    <!-- Search and Filter Section -->
                    <div class="flex gap-4 items-center flex-wrap">
                      <div class="search-container-cred">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <circle cx="11" cy="11" r="8"></circle>
                          <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input type="text" id="searchInput" placeholder="Buscar por nombre o documento..."
                          class="search-input-cred" onkeyup="filtrarTabla()">
                      </div>
                      <!-- Filtros de Género -->
                      <div class="flex gap-2 items-center">
                        <span class="text-sm font-medium text-slate-700">Género:</span>
                        <button id="filtroTodos" onclick="filtrarPorGenero('')" class="filtro-genero activo bg-purple-600 text-white px-4 py-2 rounded-lg font-medium transition-all shadow-sm hover:shadow-md border-2 border-purple-700">
                          Todos
                        </button>
                        <button id="filtroM" onclick="filtrarPorGenero('M')" class="filtro-genero bg-white border border-slate-200 hover:bg-blue-50 text-slate-700 px-4 py-2 rounded-lg font-medium transition-all flex items-center gap-2 shadow-sm hover:shadow">
                          <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-500 text-white text-xs font-semibold">M</span>
                          Masculino
                        </button>
                        <button id="filtroF" onclick="filtrarPorGenero('F')" class="filtro-genero bg-white border border-slate-200 hover:bg-rose-50 text-slate-700 px-4 py-2 rounded-lg font-medium transition-all flex items-center gap-2 shadow-sm hover:shadow">
                          <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-white text-xs font-semibold genero-femenino" style="background-color: #f43f5e !important;">F</span>
                          Femenino
                        </button>
                      </div>
                      @if(auth()->user()->role === 'admin')
                      <button class="btn-cred-primary" onclick="openAgregarNinoModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M12 5v14"></path>
                          <path d="M5 12h14"></path>
                        </svg>
                        Agregar Niño
                      </button>
                      <button class="btn-cred-primary" onclick="openImportarControlesModal()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                          <polyline points="17 8 12 3 7 8"></polyline>
                          <line x1="12" x2="12" y1="3" y2="15"></line>
                        </svg>
                        Importar desde Excel
                      </button>
                      @endif
                    </div>

                    <!-- Table Section -->
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm mt-6">
                      <div class="overflow-x-auto">
                        <table class="w-full">
                          <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">ESTABLECIMIENTO</th>
                              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">TIPO DOC.</th>
                              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">N° DOCUMENTO</th>
                              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">APELLIDOS Y NOMBRES</th>
                              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">F. NACIMIENTO</th>
                              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">GÉNERO</th>
                              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">DATOS EXTRAS</th>
                              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">ACCIONES</th>
                            </tr>
                          </thead>
                          <tbody id="tablaNinosBody" class="bg-white divide-y divide-slate-200">
                            <!-- Las filas se cargarán dinámicamente desde la base de datos -->
                          </tbody>
                        </table>
                      </div>
                      <!-- Pie de página de la tabla -->
                      <div class="px-6 py-4 border-t border-slate-200 bg-white flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-4 text-sm text-slate-600">
                          <span>Mostrando <span id="desdeRegistro" class="font-semibold text-slate-900">1</span> a <span id="hastaRegistro" class="font-semibold text-slate-900">10</span> de <span id="totalRegistros" class="font-semibold text-slate-900">0</span> registros</span>
                          <span class="hidden sm:inline">|</span>
                          <span>Página <span id="paginaActual" class="font-semibold text-slate-900">1</span> de <span id="totalPaginas" class="font-semibold text-slate-900">1</span></span>
                        </div>
                        <div class="flex items-center gap-3">
                          <div class="flex items-center gap-2">
                            <label for="registrosPorPagina" class="text-sm text-slate-600">Mostrar:</label>
                            <select id="registrosPorPagina" onchange="cambiarRegistrosPorPagina()" class="bg-white border border-slate-300 text-slate-700 px-3 py-2 rounded-lg text-sm font-medium transition-all shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                              <option value="10">10</option>
                              <option value="15">15</option>
                              <option value="25" selected>25</option>
                              <option value="50">50</option>
                              <option value="100">100</option>
                            </select>
                          </div>
                          <div class="flex items-center gap-2">
                            <button id="btnAnterior" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                              Anterior
                            </button>
                            <button id="btnSiguiente" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                              Siguiente
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Modal para Agregar Niño -->
                    <div id="agregarNinoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 overflow-y-auto" onclick="closeAgregarNinoModal(event)">
                      <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-4xl w-full mx-4 my-8 transform transition-all" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-between mb-6">
                          <h3 class="text-2xl font-bold text-slate-800">Agregar Niño</h3>
                          <button onclick="closeAgregarNinoModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <line x1="18" y1="6" x2="6" y2="18"></line>
                              <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                          </button>
                        </div>
                        <form id="agregarNinoForm" method="POST" action="{{ route('controles-cred.store') }}">
                          @csrf
                          <div class="space-y-6 max-h-[70vh] overflow-y-auto pr-2">
                            <!-- Datos del Niño -->
                            <div class="border-b border-slate-200 pb-4">
                              <h4 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                  <path d="M12 12h.01"></path>
                                  <path d="M16 8v8"></path>
                                  <path d="M8 12v4"></path>
                                  <circle cx="12" cy="12" r="10"></circle>
                                </svg>
                                Datos del Niño
                              </h4>
                                <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                  <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Nombre del Establecimiento <span class="text-red-500">*</span></label>
                                    <input type="text" name="Nombre_Establecimiento" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nombre del Establecimiento" required>
                                  </div>
                                  <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Tipo de Documento <span class="text-red-500">*</span></label>
                                    <select name="Id_Tipo_Documento" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                      <option value="">Seleccione</option>
                                      <option value="1">DNI</option>
                                      <option value="2">CE</option>
                                      <option value="3">PASS</option>
                                      <option value="4">DIE</option>
                                      <option value="5">S/ DOCUMENTO</option>
                                      <option value="6">CNV</option>
                                    </select>
                                  </div>
                                  <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Número de Documento <span class="text-red-500">*</span></label>
                                    <input 
                                      type="text" 
                                      name="Numero_Documento" 
                                      id="numeroDocumentoInput"
                                      class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                      placeholder="Ej: 12345678" 
                                      maxlength="8"
                                      pattern="[0-9]{8}"
                                      inputmode="numeric"
                                      required
                                      oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8)"
                                    >
                                    <p class="text-xs text-slate-500 mt-1">Máximo 8 dígitos numéricos</p>
                                  </div>
                                  <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Apellidos y Nombres <span class="text-red-500">*</span></label>
                                    <input type="text" name="Apellidos_Nombres" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Apellidos y Nombres" required>
                                  </div>
                                  <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">
                                      <span class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-600">
                                          <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                          <line x1="16" y1="2" x2="16" y2="6"></line>
                                          <line x1="8" y1="2" x2="8" y2="6"></line>
                                          <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                        Fecha de Nacimiento <span class="text-red-500">*</span>
                                      </span>
                                    </label>
                                    <div class="relative">
                                      <input 
                                        type="date" 
                                        name="Fecha_Nacimiento" 
                                        id="fechaNacimientoInput"
                                        class="w-full px-4 py-3 pr-12 border-2 border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all cursor-pointer bg-white hover:border-indigo-400 hover:bg-indigo-50/30" 
                                        required
                                        max="{{ date('Y-m-d') }}"
                                        style="font-size: 0.9375rem; color: #1e293b; z-index: 1; position: relative;"
                                      >
                                      <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none z-10">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-500">
                                          <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                          <line x1="16" y1="2" x2="16" y2="6"></line>
                                          <line x1="8" y1="2" x2="8" y2="6"></line>
                                          <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                      </div>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                      </svg>
                                      Seleccione la fecha de nacimiento del niño (no puede ser una fecha futura)
                                    </p>
                                  </div>
                                  <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Género <span class="text-red-500">*</span></label>
                                    <select name="Genero" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                      <option value="">Seleccione</option>
                                      <option value="M">Masculino</option>
                                      <option value="F">Femenino</option>
                                    </select>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- Dos columnas: Más Datos del Niño y Datos de la Madre -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                              <!-- Columna Izquierda: Más Datos del Niño -->
                              <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M12 6v6l4 2"></path>
                                  </svg>
                                  Más Datos del Niño
                                </h4>
                                <div class="space-y-4">
                                  <!-- Información del Establecimiento -->
                                  <div class="pb-3 border-b border-slate-200">
                                    <h5 class="text-sm font-semibold text-slate-600 mb-3 flex items-center gap-2">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16"></path>
                                        <path d="M9 21v-8H4v8"></path>
                                        <path d="M15 21V9h-5v12"></path>
                                        <path d="M20 21V9h-5v12"></path>
                                        <path d="M3 10h5"></path>
                                        <path d="M14 10h5"></path>
                                      </svg>
                                      Selección de Red y Establecimiento
                                    </h5>
                                    <div class="space-y-3">
                                      <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Red <span class="text-red-500">*</span></label>
                                        <select id="modalCodigoRed" name="Codigo_Red" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                          <option value="">Seleccione una Red</option>
                                          <option value="1">AGUAYTIA</option>
                                          <option value="2">ATALAYA</option>
                                          <option value="3">BAP-CURARAY</option>
                                          <option value="4">CORONEL PORTILLO</option>
                                          <option value="5">ESSALUD</option>
                                          <option value="6">FEDERICO BASADRE - YARINACOCHA</option>
                                          <option value="7">HOSPITAL AMAZONICO - YARINACOCHA</option>
                                          <option value="8">HOSPITAL REGIONAL DE PUCALLPA</option>
                                          <option value="9">NO PERTENECE A NINGUNA RED</option>
                                        </select>
                                      </div>
                                      <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">MicroRed <span class="text-red-500">*</span></label>
                                        <select id="modalCodigoMicrored" name="Codigo_Microred" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required disabled>
                                          <option value="">Seleccione una Microred</option>
                                        </select>
                                      </div>
                                      <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Establecimiento <span class="text-red-500">*</span></label>
                                        <select id="modalIdEstablecimiento" name="Id_Establecimiento" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required disabled>
                                          <option value="">Seleccione un Establecimiento</option>
                                        </select>
                                      </div>
                                    </div>
                                  </div>
                                  <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Distrito <span class="text-red-500">*</span></label>
                                    <input type="text" id="input-distrito" name="Distrito" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Distrito" required>
                                  </div>
                                  <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Provincia <span class="text-red-500">*</span></label>
                                    <input type="text" id="input-provincia" name="Provincia" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Provincia" required>
                                  </div>
                                  <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Departamento <span class="text-red-500">*</span></label>
                                    <input type="text" name="Departamento" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Departamento" required>
                                  </div>
                                  <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Seguro <span class="text-red-500">*</span></label>
                                    <select name="Seguro" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                      <option value="">Seleccione</option>
                                      <option value="SIS">SIS</option>
                                      <option value="ESSALUD">ESSALUD</option>
                                      <option value="PRIVADO">Privado</option>
                                      <option value="SIN_SEGURO">Sin Seguro</option>
                                    </select>
                                  </div>
                                  <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Programa <span class="text-red-500">*</span></label>
                                    <select name="Programa" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                      <option value="">Seleccione</option>
                                      <option value="CRED">CRED</option>
                                      <option value="PIANE">PIANE</option>
                                      <option value="PIM">PIM</option>
                                      <option value="JUNTOS">JUNTOS</option>
                                      <option value="PAIS">PAIS</option>
                                    </select>
                                  </div>
                                </div>
                              </div>

                              <!-- Columna Derecha: Datos de la Madre del Niño -->
                              <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                  </svg>
                                  Datos de la Madre del Niño
                                </h4>
                                <div class="space-y-4">
                                  <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">DNI de la Madre <span class="text-red-500">*</span></label>
                                    <input 
                                      type="text" 
                                      name="DNI_Madre" 
                                      id="dniMadreInput"
                                      class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                      placeholder="Ej: 12345678" 
                                      maxlength="8"
                                      pattern="[0-9]{8}"
                                      inputmode="numeric"
                                      required
                                      oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8)"
                                    >
                                    <p class="text-xs text-slate-500 mt-1">Máximo 8 dígitos numéricos</p>
                                  </div>
                                  <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Apellidos y Nombres de la Madre <span class="text-red-500">*</span></label>
                                    <input type="text" name="Apellidos_Nombres_Madre" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Apellidos y Nombres" required>
                                  </div>
                                  <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Celular de la Madre <span class="text-red-500">*</span></label>
                                    <input 
                                      type="tel" 
                                      name="Celular_Madre" 
                                      id="celularMadreInput"
                                      class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                      placeholder="Ej: 987654321" 
                                      maxlength="9"
                                      pattern="[0-9]{9}"
                                      inputmode="numeric"
                                      required
                                      oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9)"
                                    >
                                    <p class="text-xs text-slate-500 mt-1">Máximo 9 dígitos numéricos</p>
                                  </div>
                                  <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Domicilio de la Madre <span class="text-red-500">*</span></label>
                                    <input type="text" name="Domicilio_Madre" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Domicilio" required>
                                  </div>
                                  <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Referencia de Dirección <span class="text-red-500">*</span></label>
                                    <input type="text" name="Referencia_Direccion" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Referencia de dirección" required>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="flex gap-3 mt-6 pt-6 border-t border-slate-200">
                            <button type="button" onclick="closeAgregarNinoModal()" class="flex-1 px-6 py-3 border border-slate-300 rounded-xl text-slate-700 font-medium hover:bg-slate-50 transition-colors">
                              Cancelar
                            </button>
                            <button type="submit" id="submitAgregarNino" class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-medium transition-all shadow-lg hover:shadow-xl">
                              Registrar Niño
                            </button>
                          </div>
                        </form>
                      </div>
                    </div>
                    
                    <!-- Modal de Advertencias/Errores para Agregar Niño -->
                    <div id="modalAdvertenciaAgregarNino" class="modal-advertencia-nino-overlay" onclick="closeModalAdvertenciaAgregarNinoOnOverlay(event)">
                      <div class="modal-advertencia-nino-container" onclick="event.stopPropagation()">
                        <div class="modal-advertencia-nino-header">
                          <div class="modal-advertencia-nino-header-content">
                            <div class="modal-advertencia-nino-icon" id="modalAdvertenciaIcon">
                              <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                                <path d="M12 9v4"></path>
                                <path d="M12 17h.01"></path>
                              </svg>
                            </div>
                            <div>
                              <h3 class="modal-advertencia-nino-title" id="modalAdvertenciaTitulo">Advertencia</h3>
                              <p class="modal-advertencia-nino-subtitle" id="modalAdvertenciaSubtitulo">Revise la información antes de continuar</p>
                            </div>
                          </div>
                          <button onclick="closeModalAdvertenciaAgregarNino(event)" class="modal-advertencia-nino-close">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                              <line x1="18" y1="6" x2="6" y2="18"></line>
                              <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                          </button>
                        </div>
                        
                        <div class="modal-advertencia-nino-content">
                          <div class="modal-advertencia-nino-icon-large" id="modalAdvertenciaIconLarge">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                              <path d="M12 9v4"></path>
                              <path d="M12 17h.01"></path>
                            </svg>
                          </div>
                          <div class="modal-advertencia-nino-message" id="modalAdvertenciaMensaje">
                            <!-- El mensaje se insertará aquí -->
                          </div>
                          
                          <!-- Contador automático para modal de éxito -->
                          <div id="modalAdvertenciaContador" style="display: none; margin-top: 1rem; text-align: center;">
                            <p style="font-size: 0.875rem; color: #64748b;">
                              Cerrando automáticamente en <span id="contadorSegundos" style="font-weight: 700; color: #10b981; font-size: 1.125rem;">3</span> segundos...
                            </p>
                            <div style="width: 100%; height: 4px; background: #e2e8f0; border-radius: 2px; margin-top: 0.5rem; overflow: hidden;">
                              <div id="contadorBarra" style="height: 100%; background: linear-gradient(90deg, #10b981, #059669); width: 100%; transition: width 0.1s linear; border-radius: 2px;"></div>
                            </div>
                          </div>
                          
                          <!-- Lista de campos faltantes (si aplica) -->
                          <ul class="modal-advertencia-nino-list" id="modalAdvertenciaLista" style="display: none;">
                            <!-- Los campos faltantes se insertarán aquí -->
                          </ul>
                        </div>
                        
                        <div class="modal-advertencia-nino-footer">
                          <button type="button" onclick="closeModalAdvertenciaAgregarNino(event)" class="modal-advertencia-nino-btn modal-advertencia-nino-btn-cancel">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                              <line x1="18" y1="6" x2="6" y2="18"></line>
                              <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            Cerrar
                          </button>
                          <button 
                            type="button" 
                            id="btnConfirmarAdvertenciaAgregarNino" 
                            onclick="confirmarAdvertenciaAgregarNino(event)" 
                            class="modal-advertencia-nino-btn modal-advertencia-nino-btn-confirm"
                            style="display: none;"
                          >
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                              <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Continuar
                          </button>
                        </div>
                      </div>
                    </div>

                    @include('controles.modales-datos-extras')

                    <!-- Modal para Ver Controles de Salud -->
                    @include('controles.modales-ver-controles')


  <style>
    @keyframes modalSlideIn {
      from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }
  </style>

  <!-- Contenedor de Modales de Registro de Controles -->
  <div class="modales-registro-container">
  <!-- Modal para Registrar Control Recién Nacido -->
    <div id="modalRegistroControl" class="modal-registro-overlay modal-tipo-recien-nacido hidden" onclick="closeModalRegistro(event)">
      <div class="modal-registro-content modal-tipo-recien-nacido" onclick="event.stopPropagation()">
        <div class="modal-registro-header modal-recien-nacido-header">
          <div class="modal-recien-nacido-header-left">
            <div class="modal-recien-nacido-icon-circle">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
              </svg>
            </div>
            <h3 class="modal-registro-title modal-recien-nacido-title">Control Recién Nacido <span id="controlNumeroTexto"></span></h3>
          </div>
          <button class="modal-registro-close modal-recien-nacido-close" onclick="closeModalRegistro()" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18"></path>
            <path d="m6 6 12 12"></path>
          </svg>
        </button>
      </div>
        <div class="modal-registro-body">
          <form id="formRegistroControl" class="modal-registro-form" onsubmit="registrarControl(event)">
        <input type="hidden" id="controlNumero" name="numero_control">
        <input type="hidden" id="controlNinoId" name="nino_id">

            <!-- Sección Información del Control -->
            <div style="margin-bottom: 1.5rem; padding: 1.25rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0.75rem; color: white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
              <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                <div style="background: rgba(255, 255, 255, 0.2); padding: 0.5rem; border-radius: 0.5rem;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white;">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" x2="8" y1="13" y2="13"></line>
                    <line x1="16" x2="8" y1="17" y2="17"></line>
                  </svg>
                </div>
                <div>
                  <h4 style="margin: 0; font-size: 1.125rem; font-weight: 700; color: white;">Control Recién Nacido</h4>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; opacity: 0.9;">Registro de control para recién nacido (0-28 días)</p>
                </div>
              </div>
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.2);">
                <div>
                  <p style="margin: 0; font-size: 0.75rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Paciente</p>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; font-weight: 600;" id="controlInfoPaciente">-</p>
                </div>
                <div>
                  <p style="margin: 0; font-size: 0.75rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Documento</p>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; font-weight: 600;" id="controlInfoDocumento">-</p>
                </div>
                <div>
                  <p style="margin: 0; font-size: 0.75rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Establecimiento</p>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; font-weight: 600;" id="controlInfoEstablecimiento">-</p>
                </div>
                <div>
                  <p style="margin: 0; font-size: 0.75rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Edad Actual</p>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; font-weight: 600;" id="controlInfoEdad">-</p>
                </div>
              </div>
            </div>

            <!-- Sección Datos Básicos -->
            <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f8fafc; border-radius: 0.625rem; border: 1.5px solid #e2e8f0;">
              <h4 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 1.25rem;">📅</span>
                Datos Básicos
              </h4>

              <div class="modal-registro-form-group">
                <label class="modal-registro-label">
                  Fecha del Control <span class="required">*</span>
                </label>
                <input type="date" id="controlFecha" name="fecha_control" required class="modal-registro-input">
                <p class="modal-registro-info">Rango: <span id="controlRango" class="highlight"></span> días</p>
              </div>
            </div>

            <!-- Sección Antropometría -->
            <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f8fafc; border-radius: 0.625rem; border: 1.5px solid #e2e8f0;">
              <h4 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 1.25rem;">📏</span>
                Antropometría
              </h4>

              <div class="modal-registro-form-group">
                <div class="modal-registro-grid modal-registro-grid-3">
                  <div>
                    <label class="modal-registro-label">Peso (g)</label>
                    <input type="number" id="controlPeso" name="peso" step="0.01" min="0" placeholder="3200" class="modal-registro-input">
                  </div>
                  <div>
                    <label class="modal-registro-label">Talla (cm)</label>
                    <input type="number" id="controlTalla" name="talla" step="0.1" min="0" placeholder="50.5" class="modal-registro-input">
                  </div>
                  <div>
                    <label class="modal-registro-label">PC (cm)</label>
                    <input type="number" id="controlPerimetroCefalico" name="perimetro_cefalico" step="0.1" min="0" placeholder="35.0" class="modal-registro-input">
                  </div>
                </div>
              </div>
            </div>

            <!-- Sección Signos de Alarma -->
            <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f8fafc; border-radius: 0.625rem; border: 1.5px solid #e2e8f0;">
              <h4 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 1.25rem;">⚠️</span>
                Signos de Alarma
              </h4>

              <div class="modal-registro-form-group">
                <div class="modal-registro-checkbox-group">
                  <div class="modal-registro-checkbox-item">
                    <input type="checkbox" name="signos_alarma[]" value="ictericia" id="signo_ictericia">
                    <label for="signo_ictericia">Ictericia</label>
                  </div>
                  <div class="modal-registro-checkbox-item">
                    <input type="checkbox" name="signos_alarma[]" value="fiebre" id="signo_fiebre">
                    <label for="signo_fiebre">Fiebre (>37.5°C)</label>
                  </div>
                  <div class="modal-registro-checkbox-item">
                    <input type="checkbox" name="signos_alarma[]" value="hipotermia" id="signo_hipotermia">
                    <label for="signo_hipotermia">Hipotermia (<36°C)</label>
                  </div>
                  <div class="modal-registro-checkbox-item">
                    <input type="checkbox" name="signos_alarma[]" value="dificultad_alimentacion" id="signo_dificultad">
                    <label for="signo_dificultad">Dificultad en la alimentación</label>
                  </div>
                  <div class="modal-registro-checkbox-item">
                    <input type="checkbox" name="signos_alarma[]" value="no_signos" id="signo_no_signos">
                    <label for="signo_no_signos">Sin signos de alarma</label>
                  </div>
                </div>
              </div>
            </div>

            <!-- Sección Lactancia Materna -->
            <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f8fafc; border-radius: 0.625rem; border: 1.5px solid #e2e8f0;">
              <h4 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 1.25rem;">🍼</span>
                Lactancia Materna
              </h4>

              <div class="modal-registro-form-group">
                <label class="modal-registro-label">Tipo de Lactancia</label>
                <select id="controlLactancia" name="lactancia_materna" class="modal-registro-select">
                  <option value="">Seleccionar...</option>
                  <option value="exclusiva">Lactancia Materna Exclusiva</option>
                  <option value="mixta">Lactancia Mixta</option>
                  <option value="artificial">Lactancia Artificial</option>
                  <option value="no_iniciada">No iniciada</option>
                </select>
              </div>
            </div>

            <!-- Sección Observaciones -->
            <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f8fafc; border-radius: 0.625rem; border: 1.5px solid #e2e8f0;">
              <h4 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 1.25rem;">📝</span>
                Observaciones
              </h4>

              <div class="modal-registro-form-group">
                <label class="modal-registro-label">Notas adicionales</label>
                <textarea id="controlObservaciones" name="observaciones" rows="3" placeholder="Ingrese observaciones adicionales sobre el control..." class="modal-registro-textarea"></textarea>
              </div>
            </div>

            <div class="modal-registro-footer">
              <button type="button" onclick="closeModalRegistro()" class="modal-registro-btn modal-registro-btn-cancel">
            Cancelar
          </button>
              <button type="submit" class="modal-registro-btn modal-registro-btn-submit modal-recien-nacido-btn-submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                  <polyline points="17 21 17 13 7 13 7 21"></polyline>
                  <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
            Guardar Control
          </button>
        </div>
      </form>
        </div>
    </div>
  </div>

  <!-- Modal para Registrar CNV -->
    <div id="modalCNV" class="modal-registro-overlay modal-tipo-cnv hidden" onclick="closeModalCNV(event)">
      <div class="modal-registro-content modal-tipo-cnv" onclick="event.stopPropagation()">
        <div class="modal-registro-header modal-cnv-header">
          <div class="modal-cnv-header-left">
            <div class="modal-cnv-icon-circle">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="12" y1="18" x2="12" y2="12"></line>
                <line x1="9" y1="15" x2="15" y2="15"></line>
              </svg>
            </div>
            <h3 class="modal-registro-title modal-cnv-title">CNV (Carné de Nacido Vivo)</h3>
          </div>
          <button class="modal-registro-close modal-cnv-close" onclick="closeModalCNV()" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18"></path>
            <path d="m6 6 12 12"></path>
          </svg>
        </button>
      </div>
        <div class="modal-registro-body">
          <form id="formCNV" class="modal-registro-form" onsubmit="registrarCNV(event)">
        <input type="hidden" id="cnvNinoId" name="nino_id">

            <!-- Sección Información del Control -->
            <div style="margin-bottom: 1.5rem; padding: 1.25rem; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 0.75rem; color: white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
              <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                <div style="background: rgba(255, 255, 255, 0.2); padding: 0.5rem; border-radius: 0.5rem;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white;">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="12" y1="18" x2="12" y2="12"></line>
                    <line x1="9" y1="15" x2="15" y2="15"></line>
                  </svg>
                </div>
                <div>
                  <h4 style="margin: 0; font-size: 1.125rem; font-weight: 700; color: white;">CNV (Carné de Nacido Vivo)</h4>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; opacity: 0.9;">Registro de datos del recién nacido</p>
                </div>
              </div>
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.2);">
                <div>
                  <p style="margin: 0; font-size: 0.75rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Paciente</p>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; font-weight: 600;" id="cnvInfoPaciente">-</p>
                </div>
                <div>
                  <p style="margin: 0; font-size: 0.75rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Documento</p>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; font-weight: 600;" id="cnvInfoDocumento">-</p>
                </div>
                <div>
                  <p style="margin: 0; font-size: 0.75rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Establecimiento</p>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; font-weight: 600;" id="cnvInfoEstablecimiento">-</p>
                </div>
                <div>
                  <p style="margin: 0; font-size: 0.75rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Edad Actual</p>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; font-weight: 600;" id="cnvInfoEdad">-</p>
                </div>
              </div>
            </div>

            <!-- Sección Datos al Nacer -->
            <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f8fafc; border-radius: 0.625rem; border: 1.5px solid #e2e8f0;">
              <h4 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                <span style="display: inline-flex; width: 24px; height: 24px; border-radius: 999px; background: #3b82f6; align-items: center; justify-content: center;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="4"></circle>
                    <path d="M5 21c1.5-3 4-5 7-5s5.5 2 7 5"></path>
                  </svg>
                </span>
                Datos al Nacer
              </h4>

              <div class="modal-registro-form-group">
                <div class="modal-registro-grid modal-registro-grid-2">
                  <div>
                    <label class="modal-registro-label">
                      Peso (g) <span class="required">*</span>
                    </label>
                    <input type="number" id="cnvPeso" name="peso_nacer" required min="0" step="0.01" placeholder="3200" class="modal-registro-input">
                  </div>
                  <div>
                    <label class="modal-registro-label">
                      Edad Gest. (sem) <span class="required">*</span>
                    </label>
                    <input type="number" id="cnvEdadGestacional" name="edad_gestacional" required min="20" max="45" step="0.1" placeholder="38.5" class="modal-registro-input">
                  </div>
                </div>
              </div>
            </div>

            <!-- Sección Clasificación -->
            <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f8fafc; border-radius: 0.625rem; border: 1.5px solid #e2e8f0;">
              <h4 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 1.25rem;">🏷️</span>
                Clasificación
              </h4>

              <div class="modal-registro-form-group">
                <label class="modal-registro-label">
                  Clasificación del Recién Nacido <span class="required">*</span>
                </label>
                <select id="cnvClasificacion" name="clasificacion" required class="modal-registro-select">
                  <option value="">Seleccionar...</option>
                  <option value="Normal">Normal</option>
                  <option value="Bajo Peso al Nacer y/o Prematuro">Bajo Peso al Nacer y/o Prematuro</option>
                </select>
              </div>
            </div>

            <div class="modal-registro-footer">
              <button type="button" onclick="closeModalCNV()" class="modal-registro-btn modal-registro-btn-cancel">
            Cancelar
          </button>
              <button type="submit" class="modal-registro-btn modal-registro-btn-submit modal-cnv-btn-submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                  <polyline points="17 21 17 13 7 13 7 21"></polyline>
                  <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Guardar CNV
          </button>
        </div>
      </form>
        </div>
    </div>
  </div>

  <!-- Modal para Registrar Visita Domiciliaria -->
    <div id="modalVisita" class="modal-registro-overlay modal-tipo-visita hidden" onclick="closeModalVisita(event)">
      <div class="modal-registro-content modal-tipo-visita" onclick="event.stopPropagation()">
        <div class="modal-registro-header modal-visita-header">
          <div class="modal-visita-header-left">
            <div class="modal-visita-icon-circle">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
              </svg>
            </div>
            <h3 class="modal-registro-title modal-visita-title">Visita Domiciliaria</h3>
          </div>
          <button class="modal-registro-close modal-visita-close" onclick="closeModalVisita()" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18"></path>
            <path d="m6 6 12 12"></path>
          </svg>
        </button>
      </div>
        <div class="modal-registro-body">
          <form id="formVisita" class="modal-registro-form" onsubmit="registrarVisita(event)">
        <input type="hidden" id="visitaNinoId" name="nino_id">
        <input type="hidden" id="visitaPeriodo" name="periodo">

            <!-- Sección Información del Control -->
            <div style="margin-bottom: 1.5rem; padding: 1.25rem; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 0.75rem; color: white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
              <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                <div style="background: rgba(255, 255, 255, 0.2); padding: 0.5rem; border-radius: 0.5rem;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white;">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                  </svg>
                </div>
                <div>
                  <h4 style="margin: 0; font-size: 1.125rem; font-weight: 700; color: white;">Visita Domiciliaria</h4>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; opacity: 0.9;">Registro de visita domiciliaria al paciente</p>
                </div>
              </div>
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.2);">
                <div>
                  <p style="margin: 0; font-size: 0.75rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Paciente</p>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; font-weight: 600;" id="visitaInfoPaciente">-</p>
                </div>
                <div>
                  <p style="margin: 0; font-size: 0.75rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Documento</p>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; font-weight: 600;" id="visitaInfoDocumento">-</p>
                </div>
                <div>
                  <p style="margin: 0; font-size: 0.75rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Establecimiento</p>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; font-weight: 600;" id="visitaInfoEstablecimiento">-</p>
                </div>
                <div>
                  <p style="margin: 0; font-size: 0.75rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Edad Actual</p>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; font-weight: 600;" id="visitaInfoEdad">-</p>
                </div>
              </div>
            </div>

            <!-- Sección Datos de la Visita -->
            <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f8fafc; border-radius: 0.625rem; border: 1.5px solid #e2e8f0;">
              <h4 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 1.25rem;">📅</span>
                Datos de la Visita
              </h4>

              <div class="modal-registro-form-group">
                <label class="modal-registro-label">Periodo</label>
                <input type="text" id="visitaPeriodoText" readonly class="modal-registro-input">
              </div>

              <div class="modal-registro-form-group">
                <label class="modal-registro-label">
                  Fecha de la Visita <span class="required">*</span>
                </label>
                <input type="date" id="visitaFecha" name="fecha_visita" required class="modal-registro-input">
              </div>
            </div>

            <!-- Sección Tipo de Visita -->
            <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f8fafc; border-radius: 0.625rem; border: 1.5px solid #e2e8f0;">
              <h4 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 1.25rem;">🏠</span>
                Tipo de Visita
              </h4>

              <div class="modal-registro-form-group">
                <label class="modal-registro-label">Clasificación</label>
                <select id="visitaTipo" name="tipo_visita" class="modal-registro-select">
                  <option value="">Seleccionar...</option>
                  <option value="programada">Programada</option>
                  <option value="seguimiento">Seguimiento</option>
                  <option value="emergencia">Emergencia</option>
                  <option value="educativa">Educativa</option>
                </select>
              </div>
            </div>

            <div class="modal-registro-footer">
              <button type="button" onclick="closeModalVisita()" class="modal-registro-btn modal-registro-btn-cancel">
            Cancelar
          </button>
              <button type="submit" class="modal-registro-btn modal-registro-btn-submit modal-visita-btn-submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                  <polyline points="17 21 17 13 7 13 7 21"></polyline>
                  <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Guardar Visita
          </button>
        </div>
      </form>
        </div>
    </div>
  </div>

  <!-- Modal para Registrar Control CRED Mensual -->
    <div id="modalCredMensual" class="modal-registro-overlay modal-tipo-cred-mensual hidden" onclick="closeModalCredMensual(event)">
      <div class="modal-registro-content modal-tipo-cred-mensual" onclick="event.stopPropagation()">
        <div class="modal-registro-header modal-cred-mensual-header">
          <div class="modal-cred-mensual-header-left">
            <div class="modal-cred-mensual-icon-circle">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
                <path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01"></path>
              </svg>
            </div>
            <h3 class="modal-registro-title modal-cred-mensual-title">CRED Mensual</h3>
          </div>
          <button class="modal-registro-close modal-cred-mensual-close" onclick="closeModalCredMensual()" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18"></path>
            <path d="m6 6 12 12"></path>
          </svg>
        </button>
      </div>
        <div class="modal-registro-body">
          <form id="formCredMensual" class="modal-registro-form" onsubmit="registrarCredMensual(event)">
        <input type="hidden" id="credMensualNinoId" name="nino_id">
        <input type="hidden" id="credMensualMes" name="mes">
        <input type="hidden" id="credMensualControlId" name="control_id" value="">

            <!-- Sección Información del Control -->
            <div style="margin-bottom: 1.5rem; padding: 1.25rem; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 0.75rem; color: white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
              <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                <div style="background: rgba(255, 255, 255, 0.2); padding: 0.5rem; border-radius: 0.5rem;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                  </svg>
                </div>
                <div>
                  <h4 style="margin: 0; font-size: 1.125rem; font-weight: 700; color: white;">Control CRED Mensual</h4>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; opacity: 0.9;" id="credMensualInfoTipo">Control mensual de crecimiento y desarrollo</p>
                </div>
              </div>
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.2);">
                <div>
                  <p style="margin: 0; font-size: 0.75rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Paciente</p>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; font-weight: 600;" id="credMensualInfoPaciente">-</p>
                </div>
                <div>
                  <p style="margin: 0; font-size: 0.75rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Documento</p>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; font-weight: 600;" id="credMensualInfoDocumento">-</p>
                </div>
                <div>
                  <p style="margin: 0; font-size: 0.75rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Establecimiento</p>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; font-weight: 600;" id="credMensualInfoEstablecimiento">-</p>
                </div>
                <div>
                  <p style="margin: 0; font-size: 0.75rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Edad Actual</p>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; font-weight: 600;" id="credMensualInfoEdad">-</p>
                </div>
              </div>
            </div>

            <!-- Sección Datos Básicos -->
            <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f8fafc; border-radius: 0.625rem; border: 1.5px solid #e2e8f0;">
              <h4 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 1.25rem;">📅</span>
                Datos Básicos
              </h4>

              <div class="modal-registro-form-group">
                <label class="modal-registro-label">Mes del Control</label>
                <input type="text" id="credMensualMesText" readonly class="modal-registro-input">
              </div>

              <div class="modal-registro-form-group">
                <label class="modal-registro-label">
                  Fecha del Control <span class="required">*</span>
                </label>
                <input type="date" id="credMensualFecha" name="fecha_control" required class="modal-registro-input">
              </div>
            </div>


            <div class="modal-registro-footer">
              <button type="button" onclick="closeModalCredMensual()" class="modal-registro-btn modal-registro-btn-cancel">
            Cancelar
          </button>
              <button type="submit" class="modal-registro-btn modal-registro-btn-submit modal-cred-mensual-btn-submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                  <polyline points="17 21 17 13 7 13 7 21"></polyline>
                  <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Guardar Control
          </button>
        </div>
      </form>
    </div>
  </div>
    </div>
  </div>
  <!-- Fin del Contenedor de Modales de Registro -->

  <!-- Modal para Importar Controles desde Excel -->
  <div id="importarControlesModal" class="modal-importar-overlay hidden" onclick="closeImportarControlesModal(event)">
    <div class="modal-importar-container" onclick="event.stopPropagation()">
      <!-- Header con gradiente -->
      <div class="modal-importar-header">
        <div class="modal-importar-header-content">
          <div class="modal-importar-title-section">
            <div class="modal-importar-icon-wrapper">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="17 8 12 3 7 8"></polyline>
                <line x1="12" x2="12" y1="3" y2="15"></line>
              </svg>
            </div>
            <div>
              <h3 class="modal-importar-title">Importar Controles</h3>
              <p class="modal-importar-subtitle">Sube un archivo Excel o CSV con los datos</p>
            </div>
          </div>
          <button type="button" onclick="closeImportarControlesModal()" class="modal-importar-close-btn" aria-label="Cerrar modal">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </div>
      </div>

      <div class="modal-importar-body">
        <!-- Mensajes de éxito/error -->
        @if(session('import_success'))
          <div class="modal-importar-message modal-importar-message-success">
            <div class="modal-importar-message-icon">
              <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <div class="modal-importar-message-content">
              <h4 class="modal-importar-message-title">✅ Importación exitosa</h4>
              <pre class="modal-importar-message-text">{{ session('import_success') }}</pre>
            </div>
          </div>
          
          <!-- Resumen Detallado de Niños Importados -->
          @if(session('ninos_detallados') && count(session('ninos_detallados')) > 0)
            <div class="mt-6 bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
              <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" x2="8" y1="13" y2="13"></line>
                    <line x1="16" x2="8" y1="17" y2="17"></line>
                  </svg>
                  Resumen Detallado de Datos Importados
                </h3>
                <p class="text-sm text-indigo-100 mt-1">Datos generales, datos extra, madre y controles</p>
              </div>
              
              <div class="p-6 space-y-6 max-h-96 overflow-y-auto">
                @foreach(session('ninos_detallados') as $index => $ninoData)
                  <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
                    <!-- Datos del Niño -->
                    <div class="mb-4">
                      <h4 class="font-semibold text-slate-800 mb-2 flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold">{{ $index + 1 }}</span>
                        {{ $ninoData['nino']['apellidos_nombres'] ?? 'N/A' }}
                      </h4>
                      <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm">
                        <div><span class="text-slate-600">ID:</span> <span class="font-medium">{{ $ninoData['nino']['id_niño'] }}</span></div>
                        <div><span class="text-slate-600">Documento:</span> <span class="font-medium">{{ $ninoData['nino']['numero_doc'] ?? 'N/A' }}</span></div>
                        <div><span class="text-slate-600">Nacimiento:</span> <span class="font-medium">{{ $ninoData['nino']['fecha_nacimiento'] ?? 'N/A' }}</span></div>
                        <div><span class="text-slate-600">Género:</span> <span class="font-medium">{{ $ninoData['nino']['genero'] ?? 'N/A' }}</span></div>
                      </div>
                    </div>
                    
                    <!-- Datos Extra -->
                    @if($ninoData['datos_extra'])
                      <div class="mb-3 p-3 bg-blue-50 rounded border border-blue-200">
                        <h5 class="font-semibold text-blue-800 text-sm mb-2">📋 Datos Extra</h5>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs">
                          <div><span class="text-blue-600">Red:</span> <span class="font-medium">{{ $ninoData['datos_extra']['red'] ?? '-' }}</span></div>
                          <div><span class="text-blue-600">MicroRed:</span> <span class="font-medium">{{ $ninoData['datos_extra']['microred'] ?? '-' }}</span></div>
                          <div><span class="text-blue-600">Distrito:</span> <span class="font-medium">{{ $ninoData['datos_extra']['distrito'] ?? '-' }}</span></div>
                          <div><span class="text-blue-600">Seguro:</span> <span class="font-medium">{{ $ninoData['datos_extra']['seguro'] ?? '-' }}</span></div>
                        </div>
                      </div>
                    @endif
                    
                    <!-- Datos de la Madre -->
                    @if($ninoData['madre'])
                      <div class="mb-3 p-3 bg-pink-50 rounded border border-pink-200">
                        <h5 class="font-semibold text-pink-800 text-sm mb-2">👩 Madre</h5>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-xs">
                          <div><span class="text-pink-600">DNI:</span> <span class="font-medium">{{ $ninoData['madre']['dni'] ?? '-' }}</span></div>
                          <div><span class="text-pink-600">Nombre:</span> <span class="font-medium">{{ $ninoData['madre']['apellidos_nombres'] ?? '-' }}</span></div>
                          <div><span class="text-pink-600">Celular:</span> <span class="font-medium">{{ $ninoData['madre']['celular'] ?? '-' }}</span></div>
                        </div>
                      </div>
                    @endif
                    
                    <!-- Controles -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                      <!-- Controles RN -->
                      @if(count($ninoData['controles_rn']) > 0)
                        <div class="p-3 bg-green-50 rounded border border-green-200">
                          <h5 class="font-semibold text-green-800 text-sm mb-2">👶 Controles RN ({{ count($ninoData['controles_rn']) }})</h5>
                          <div class="space-y-1 text-xs">
                            @foreach($ninoData['controles_rn'] as $control)
                              <div class="flex justify-between">
                                <span>Control {{ $control['numero_control'] }}:</span>
                                <span class="font-medium">{{ $control['fecha'] ?? '-' }} ({{ $control['edad'] ?? '-' }} días) - <span class="text-green-700">{{ $control['estado'] ?? '-' }}</span></span>
                              </div>
                            @endforeach
                          </div>
                        </div>
                      @endif
                      
                      <!-- Controles CRED -->
                      @if(count($ninoData['controles_cred']) > 0)
                        <div class="p-3 bg-purple-50 rounded border border-purple-200">
                          <h5 class="font-semibold text-purple-800 text-sm mb-2">📊 Controles CRED ({{ count($ninoData['controles_cred']) }})</h5>
                          <div class="space-y-1 text-xs">
                            @foreach($ninoData['controles_cred'] as $control)
                              <div class="flex justify-between">
                                <span>Control {{ $control['numero_control'] }}:</span>
                                <span class="font-medium">{{ $control['fecha'] ?? '-' }} ({{ $control['edad'] ?? '-' }} días) - <span class="text-purple-700">{{ $control['estado'] ?? '-' }}</span></span>
                              </div>
                            @endforeach
                          </div>
                        </div>
                      @endif
                    </div>
                    
                    <!-- Otros Controles -->
                    <div class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-2 text-xs">
                      @if($ninoData['tamizaje'])
                        <div class="p-2 bg-yellow-50 rounded border border-yellow-200">
                          <span class="text-yellow-700 font-semibold">Tamizaje:</span>
                          <div class="text-yellow-800 text-xs">Fecha: {{ $ninoData['tamizaje']['fecha_tam_neo'] ?? '-' }}</div>
                          @if(!empty($ninoData['tamizaje']['galen_fecha_tam_feo']))
                            <div class="text-yellow-600 text-xs">Galen: {{ $ninoData['tamizaje']['galen_fecha_tam_feo'] ?? '-' }}</div>
                          @endif
                        </div>
                      @endif
                      
                      @if($ninoData['vacunas'])
                        <div class="p-2 bg-orange-50 rounded border border-orange-200">
                          <span class="text-orange-700 font-semibold">Vacunas:</span>
                          <div class="text-orange-800 text-xs">BCG: {{ $ninoData['vacunas']['fecha_bcg'] ?? '-' }}</div>
                          <div class="text-orange-800 text-xs">HVB: {{ $ninoData['vacunas']['fecha_hvb'] ?? '-' }}</div>
                        </div>
                      @endif
                      
                      @if($ninoData['cnv'])
                        <div class="p-2 bg-teal-50 rounded border border-teal-200">
                          <span class="text-teal-700 font-semibold">CNV:</span>
                          <div class="text-teal-800 text-xs">Peso: {{ $ninoData['cnv']['peso'] ?? '-' }} kg</div>
                          <div class="text-teal-800 text-xs">EG: {{ $ninoData['cnv']['edad_gestacional'] ?? '-' }} sem</div>
                          <div class="text-teal-800 text-xs">{{ $ninoData['cnv']['clasificacion'] ?? '-' }}</div>
                        </div>
                      @endif
                      
                      @if(count($ninoData['visitas']) > 0)
                        <div class="p-2 bg-cyan-50 rounded border border-cyan-200">
                          <span class="text-cyan-700 font-semibold">Visitas:</span>
                          <div class="text-cyan-800 text-xs">{{ count($ninoData['visitas']) }} registradas</div>
                          <div class="text-cyan-600 text-xs mt-1">
                            @foreach($ninoData['visitas'] as $visita)
                              Visita {{ $visita['control_de_visita'] ?? '-' }}: {{ $visita['fecha_visita'] ?? '-' }}<br>
                            @endforeach
                          </div>
                        </div>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        @endif

        @if(session('import_error'))
          <div class="modal-importar-message modal-importar-message-error">
            <div class="modal-importar-message-icon">
              <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </div>
            <div class="modal-importar-message-content">
              <h4 class="modal-importar-message-title">❌ Error en la importación</h4>
              <p class="modal-importar-message-text">{{ session('import_error') }}</p>
            </div>
          </div>
        @endif

        <form action="{{ route('importar-controles.import') }}" method="POST" enctype="multipart/form-data" id="formImportarControles">
          @csrf
          
          <!-- Área de carga de archivo -->
          <div class="modal-importar-dropzone" id="fileDropZone">
            <input 
              type="file" 
              id="archivo_excel_modal" 
              name="archivo_excel" 
              accept=".xlsx,.xls,.csv"
              class="modal-importar-file-input"
              required
            >
            <div class="modal-importar-dropzone-content">
              <div class="modal-importar-dropzone-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                  <polyline points="17 8 12 3 7 8"></polyline>
                  <line x1="12" x2="12" y1="3" y2="15"></line>
                </svg>
              </div>
              <label class="modal-importar-dropzone-label">
                Haz clic para seleccionar
              </label>
              <p class="modal-importar-dropzone-hint">o arrastra y suelta tu archivo aquí</p>
              <p id="fileName" class="modal-importar-file-name">Ningún archivo seleccionado</p>
              <div class="modal-importar-file-formats">
                <span class="modal-importar-format-badge">.xlsx</span>
                <span class="modal-importar-format-badge">.xls</span>
                <span class="modal-importar-format-badge">.csv</span>
                <span>Máx. 10MB</span>
              </div>
            </div>
          </div>

          <!-- Barra de progreso -->
          <div id="progressContainer" class="modal-importar-progress">
            <div class="modal-importar-progress-bar-container">
              <div id="progressBar" class="modal-importar-progress-bar"></div>
            </div>
            <p id="progressText" class="modal-importar-progress-text">Procesando...</p>
          </div>

          <!-- Botones de acción -->
          <div class="modal-importar-actions">
            <button 
              type="submit" 
              id="btnImportar"
              class="modal-importar-btn modal-importar-btn-primary"
              disabled
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="17 8 12 3 7 8"></polyline>
                <line x1="12" x2="12" y1="3" y2="15"></line>
              </svg>
              Importar Controles
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <script src="{{ asset('JS/dashbord.js') }}"></script>
  <script src="{{ asset('JS/formulario-selec-de-EESS.js') }}"></script>
  <script src="{{ asset('JS/modal-importar-controles.js') }}"></script>
  <script>
    // ========== SISTEMA DE GESTIÓN DE MODALES ==========
    // Gestor centralizado para prevenir apertura múltiple de modales
    const ModalManager = {
      activeModal: null,
      timeouts: new Set(),
      isProcessing: false,

      // Cerrar todos los modales de registro
      cerrarTodos() {
        const modalesRegistro = [
          'modalRegistroControl',
          'modalTamizaje',
          'modalCNV',
          'modalVisita',
          'modalVacuna'
        ];

        modalesRegistro.forEach(modalId => {
          const modal = document.getElementById(modalId);
          if (modal && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
            modal.classList.remove('show');
          }
        });

        // Cerrar todos los modales informativos
        const modalesInfo = [
          'modalInfoRecienNacido',
          'modalInfoTamizaje',
          'modalInfoCNV',
          'modalInfoVisita',
          'modalInfoVacuna',
          'modalInfoCredMensual'
        ];

        modalesInfo.forEach(modalId => {
          const modal = document.getElementById(modalId);
          if (modal && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
          }
        });

        this.activeModal = null;
      },

      // Abrir modal de forma segura (cerrando otros primero)
      abrirSeguro(modalId, callback) {
        // Si ya hay un modal abierto, cerrarlo primero
        if (this.activeModal && this.activeModal !== modalId) {
          this.cerrarTodos();
          // Pequeño delay para asegurar que el modal anterior se cerró
          setTimeout(() => {
            this._ejecutarApertura(modalId, callback);
          }, 100);
        } else {
          this._ejecutarApertura(modalId, callback);
        }
      },

      // Ejecutar la apertura del modal
      _ejecutarApertura(modalId, callback) {
        const modal = document.getElementById(modalId);
        if (!modal) {
          console.warn(`Modal ${modalId} no encontrado`);
          return;
        }

        // Verificar que el modal no esté ya abierto
        if (!modal.classList.contains('hidden')) {
          console.log(`Modal ${modalId} ya está abierto`);
          return;
        }

        this.activeModal = modalId;

        if (typeof callback === 'function') {
          callback();
        }

        modal.classList.remove('hidden');
        modal.classList.add('show');
      },

      // Registrar un timeout para poder cancelarlo después
      registrarTimeout(timeoutId) {
        this.timeouts.add(timeoutId);
      },

      // Cancelar todos los timeouts pendientes
      cancelarTimeouts() {
        this.timeouts.forEach(timeoutId => {
          clearTimeout(timeoutId);
        });
        this.timeouts.clear();
      },

      // Cerrar modal específico
      cerrar(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
          modal.classList.add('hidden');
          modal.classList.remove('show');
        }

        if (this.activeModal === modalId) {
          this.activeModal = null;
        }
      }
    };

    // ========== VARIABLES DE PAGINACIÓN ==========
    let filtroGeneroActual = '';
    let registrosPorPagina = 25;
    let paginaActual = 1;
    let todosLosNinos = []; // Almacenar todos los niños cargados
    let paginacionInfo = null; // Información de paginación

    // ========== CARGAR DATOS DESDE LA BASE DE DATOS ==========
    // Hacer la función disponible globalmente inmediatamente
    window.cargarNinos = function cargarNinos(page = 1) {
      console.log('🔄 cargarNinos llamada con página:', page);
      const params = new URLSearchParams();
      if (filtroGeneroActual) params.append('genero', filtroGeneroActual);
      
      // Obtener el valor del selector de registros por página
      const selectRegistros = document.getElementById('registrosPorPagina');
      const perPage = selectRegistros ? parseInt(selectRegistros.value) : 25;
      params.append('per_page', perPage);
      params.append('page', page); // Página actual

      const searchInput = document.querySelector('.search-input-cred');
      if (searchInput && searchInput.value) {
        params.append('buscar', searchInput.value);
      }

      // Mostrar indicador de carga
      const tbody = document.getElementById('tablaNinosBody');
      if (!tbody) {
        console.error('❌ No se encontró el elemento tablaNinosBody');
        return;
      }
      tbody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center text-slate-500">Cargando...</td></tr>';

      const apiUrl = '{{ route("api.ninos") }}?' + params.toString();
      console.log('🌐 Llamando a API:', apiUrl);
      
      fetch(apiUrl, {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
      })
      .then(response => {
        console.log('📡 Respuesta del servidor:', response.status, response.statusText);
        if (!response.ok) {
          throw new Error('Error en la respuesta del servidor: ' + response.status);
        }
        return response.json();
      })
      .then(data => {
        console.log('✅ Datos recibidos del API:', data); // Debug
        if (data.success) {
          todosLosNinos = data.data || [];
          paginacionInfo = data.pagination || null;
          paginaActual = page;
          console.log('📊 Total de niños a renderizar:', todosLosNinos.length);
          if (todosLosNinos.length > 0) {
            console.log('👶 Primer niño:', todosLosNinos[0]);
          } else {
            console.warn('⚠️ No hay niños en la respuesta');
          }
          renderizarTabla(todosLosNinos);
          actualizarControlesPaginacion();
        } else {
          console.error('❌ Error al cargar los niños:', data.message);
          if (tbody) {
            tbody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center text-slate-500">No se pudieron cargar los datos: ' + (data.message || 'Error desconocido') + '</td></tr>';
          }
        }
      })
      .catch(error => {
        console.error('Error al cargar niños:', error);
        if (tbody) {
          tbody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center text-slate-500">Error al cargar los datos. Por favor, recarga la página.</td></tr>';
        }
      });
    }

    // ========== ACTUALIZAR CONTROLES DE PAGINACIÓN ==========
    function actualizarControlesPaginacion() {
      if (!paginacionInfo) return;

      const { current_page, last_page, total, per_page } = paginacionInfo;
      const desde = ((current_page - 1) * per_page) + 1;
      const hasta = Math.min(current_page * per_page, total);

      // Actualizar elementos existentes del HTML
      const desdeEl = document.getElementById('desdeRegistro');
      const hastaEl = document.getElementById('hastaRegistro');
      const totalEl = document.getElementById('totalRegistros');
      const paginaEl = document.getElementById('paginaActual');
      const totalPagEl = document.getElementById('totalPaginas');
      const btnAnterior = document.getElementById('btnAnterior');
      const btnSiguiente = document.getElementById('btnSiguiente');

      if (desdeEl) desdeEl.textContent = total > 0 ? desde : '0';
      if (hastaEl) hastaEl.textContent = hasta > total ? total : hasta;
      if (totalEl) totalEl.textContent = total;
      if (paginaEl) paginaEl.textContent = current_page;
      if (totalPagEl) totalPagEl.textContent = last_page;

      // Actualizar botones de paginación
      if (btnAnterior) {
        btnAnterior.disabled = current_page === 1;
        btnAnterior.onclick = current_page > 1 ? () => cargarNinos(current_page - 1) : null;
      }
      if (btnSiguiente) {
        btnSiguiente.disabled = current_page === last_page;
        btnSiguiente.onclick = current_page < last_page ? () => cargarNinos(current_page + 1) : null;
      }
    }

    // ========== RENDERIZAR TABLA CON LOS DATOS ==========
    // Función para obtener el texto del tipo de documento
    function obtenerTipoDocumento(idTipoDoc) {
      // Si ya es texto, devolverlo directamente
      if (typeof idTipoDoc === 'string' && ['DNI', 'CE', 'PASS', 'DIE', 'S/ DOCUMENTO', 'CNV', 'S/ DOC.'].includes(idTipoDoc)) {
        return idTipoDoc === 'S/ DOCUMENTO' ? 'S/ DOC.' : idTipoDoc;
      }
      
      // Si es número, mapearlo
      const tiposDocumento = {
        '1': 'DNI',
        '2': 'CE',
        '3': 'PASS',
        '4': 'DIE',
        '5': 'S/ DOC.',
        '6': 'CNV'
      };
      return tiposDocumento[idTipoDoc] || idTipoDoc || '-';
    }

    function renderizarTabla(ninos) {
      console.log('🎨 renderizarTabla llamada con', ninos.length, 'niños');
      const tbody = document.getElementById('tablaNinosBody');
      if (!tbody) {
        console.error('❌ No se encontró el elemento tablaNinosBody');
        return;
      }

      console.log('🎨 Renderizando tabla con', ninos.length, 'niños');

      if (ninos.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center text-slate-500">No hay registros disponibles</td></tr>';
        return;
      }

      tbody.innerHTML = ninos.map(nino => {
        // Obtener el ID correcto del niño
        const ninoId = nino.id_niño || nino.id || 0;
        const genero = nino.genero || 'M';
        const generoColor = genero === 'F' ? '#f43f5e' : '#3b82f6';
        const generoClass = genero === 'F' ? 'genero-femenino' : '';
        const fechaNacimiento = nino.fecha_nacimiento ? formatearFechaISO(nino.fecha_nacimiento) : '-';
        const establecimiento = (nino.establecimiento || '-').replace(/'/g, "&#39;");
        const nombre = (nino.apellidos_nombres || '-').replace(/'/g, "&#39;");
        const documento = (nino.numero_doc || nino.numero_documento || '-').replace(/'/g, "&#39;");
        const tipoDocumento = obtenerTipoDocumento(nino.id_tipo_documento || nino.tipo_doc);

        return `
          <tr class="hover:bg-slate-50 transition-colors" data-genero="${genero}">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">${establecimiento}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 font-medium">${tipoDocumento}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">${documento}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">${nombre}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">${fechaNacimiento}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="inline-flex items-center justify-center w-8 h-8 rounded-full ${generoClass} text-white font-semibold text-sm" style="background-color: ${generoColor} !important;">${genero}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <button class="btn-cred-secondary" onclick="openDatosExtrasModal(${ninoId}, '${nombre.replace(/'/g, "\\'")}', '${documento.replace(/'/g, "\\'")}')" title="Ver datos extras">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <path d="M12 16v-4"></path>
                  <path d="M12 8h.01"></path>
                </svg>
                Datos Extras
              </button>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <button class="btn-cred-secondary" onclick="openVerControlesModal(${ninoId}, '${nombre.replace(/'/g, "\\'")}', '${documento.replace(/'/g, "\\'")}', '${establecimiento.replace(/'/g, "\\'")}')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                </svg>
                Ver Controles
              </button>
            </td>
          </tr>
        `;
      }).join('');
    }

    // ========== FILTRO POR GÉNERO ==========
    function filtrarPorGenero(genero) {
      filtroGeneroActual = genero;
      paginaActual = 1; // Resetear a primera página

      // Actualizar estado de los botones
      document.querySelectorAll('.filtro-genero').forEach(btn => {
        btn.classList.remove('activo', 'bg-purple-600', 'bg-blue-500', 'bg-rose-500', 'text-white', 'border-purple-700', 'border-blue-700', 'border-rose-700');
        btn.classList.add('bg-white', 'text-slate-700', 'border-slate-200');
      });

      // Actualizar botón activo
      if (genero === '') {
        const btnTodos = document.getElementById('filtroTodos');
        if (btnTodos) {
          btnTodos.classList.add('activo', 'bg-purple-600', 'text-white', 'border-2', 'border-purple-700', 'shadow-md');
          btnTodos.classList.remove('bg-white', 'text-slate-700', 'border-slate-200');
        }
      } else if (genero === 'M') {
        const btnM = document.getElementById('filtroM');
        if (btnM) {
          btnM.classList.add('activo', 'bg-blue-500', 'text-white', 'border-2', 'border-blue-700', 'shadow-md');
          btnM.classList.remove('bg-white', 'text-slate-700', 'border-slate-200');
        }
      } else if (genero === 'F') {
        const btnF = document.getElementById('filtroF');
        if (btnF) {
          btnF.classList.add('activo', 'text-white', 'border-2', 'border-rose-700', 'shadow-md');
          btnF.style.backgroundColor = '#f43f5e';
          btnF.classList.remove('bg-white', 'text-slate-700', 'border-slate-200');
        }
      }

      // Recargar datos desde la API con el filtro aplicado (página 1)
      cargarNinos(1);
    }

    // ========== CAMBIAR REGISTROS POR PÁGINA ==========
    function cambiarRegistrosPorPagina() {
      const select = document.getElementById('registrosPorPagina');
      if (select) {
        const nuevoPerPage = parseInt(select.value);
        paginaActual = 1; // Resetear a primera página
        cargarNinos(1); // Recargar con nuevo tamaño de página
      }
    }

    // ========== FILTRAR TABLA POR BÚSQUEDA ==========
    function filtrarTabla() {
      paginaActual = 1;
      cargarNinos(1); // Recargar desde la API con búsqueda
    }

    // ========== INICIALIZAR AL CARGAR ==========
    // ========== INICIALIZAR AL CARGAR ==========
    function configurarEventos() {
      // Configurar búsqueda
      const searchInput = document.querySelector('.search-input-cred');
      if (searchInput) {
        searchInput.addEventListener('keyup', filtrarTabla);
      }

      // Configurar selector de registros por página
      const selectRegistros = document.getElementById('registrosPorPagina');
      if (selectRegistros) {
        selectRegistros.addEventListener('change', cambiarRegistrosPorPagina);
      }
    }
    
    // Inicializar cuando el DOM esté listo
    (function() {
      let cargaIniciada = false;
      
      function iniciarCarga() {
        // Evitar múltiples cargas
        if (cargaIniciada) {
          console.log('⚠️ La carga ya fue iniciada, omitiendo...');
          return;
        }
        
        if (typeof window.cargarNinos === 'function') {
          console.log('✅ Iniciando carga de datos...');
          cargaIniciada = true;
          configurarEventos();
          window.cargarNinos(1);
        } else {
          console.log('⏳ Esperando que cargarNinos esté disponible...');
          setTimeout(iniciarCarga, 100);
        }
      }
      
      // Ejecutar cuando el DOM esté listo
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', iniciarCarga);
      } else {
        // El DOM ya está listo, ejecutar inmediatamente
        setTimeout(iniciarCarga, 100);
      }
    })();
    // ========== FUNCIONES PARA MODAL DE AGREGAR NIÑO ==========
    function openAgregarNinoModal() {
      const modal = document.getElementById('agregarNinoModal');
      if (!modal) {
        console.error('Modal agregarNinoModal no encontrado');
        return;
      }
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      // Asegurar que los selects estén inicializados
      if (typeof initModalSelects === 'function') {
        if (!modalSelectsInitialized && !window.modalSelectsInitialized) {
          initModalSelects();
        }
      } else {
        console.warn('initModalSelects no está disponible');
      }
    }

    // Las funciones del modal de importar están en modal-importar-controles.js
    // Manejar mensajes de importación después de cargar la página
    document.addEventListener('DOMContentLoaded', function() {
      @if(session('import_success'))
        // Mostrar notificación de éxito
        console.log('✅ Importación exitosa detectada');
        
        // Cerrar el modal si está abierto
        if (typeof window.closeImportarControlesModal === 'function') {
          const modal = document.getElementById('importarControlesModal');
          if (modal && !modal.classList.contains('hidden')) {
            window.closeImportarControlesModal();
          }
        }
        
        // Recargar la tabla después de importar para mostrar los datos guardados
        console.log('✅ Datos guardados en BD, recargando tabla...');
        
        // Forzar recarga de la tabla para mostrar los datos importados
        if (typeof window.cargarNinos === 'function') {
          console.log('🔄 Recargando tabla después de importación exitosa...');
          
          // Recargar inmediatamente
          window.cargarNinos(1);
          
          // Recargar nuevamente después de un breve delay para asegurar que se muestren todos los datos
          setTimeout(() => {
            console.log('🔄 Segunda recarga para asegurar que todos los datos se muestren...');
            window.cargarNinos(1);
          }, 1500);
        } else {
          console.warn('⚠️ cargarNinos no está disponible, recargando página completa...');
          // Recargar la página después de 2 segundos para mostrar el mensaje y los datos
          setTimeout(() => {
            window.location.reload();
          }, 2000);
        }
        
        // Mostrar mensaje de confirmación de guardado en BD
        @if(session('verificacion_bd'))
          const verificacion = @json(session('verificacion_bd'));
          if (verificacion && verificacion.total_verificado) {
            console.log('✅ Verificación BD:', verificacion);
            console.log(`✅ ${verificacion.ninos_en_bd} niños en BD`);
            console.log(`✅ ${verificacion.controles_cred_en_bd} controles CRED en BD`);
            console.log(`✅ ${verificacion.controles_rn_en_bd} controles RN en BD`);
          }
        @endif
      @endif
      
      @if(session('import_error'))
        // Mantener el modal abierto si hay error para que el usuario vea el mensaje
        console.log('❌ Error en importación detectado');
        setTimeout(() => {
          if (typeof window.openImportarControlesModal === 'function') {
            window.openImportarControlesModal();
          }
        }, 300);
      @endif
    });

    function closeAgregarNinoModal(event) {
      if (event && event.target !== event.currentTarget && event.currentTarget) return;
      const modal = document.getElementById('agregarNinoModal');
      if (!modal) return;
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      // Resetear el formulario
      const form = document.getElementById('agregarNinoForm');
      if (form) form.reset();
      // Resetear selects del modal
      resetModalSelects();
    }

    function resetModalSelects() {
      const microredSelect = document.getElementById('modalCodigoMicrored');
      const eessSelect = document.getElementById('modalIdEstablecimiento');
      const redSelect = document.getElementById('modalCodigoRed');
      const redDisplay = document.getElementById('input-red-display');
      const microredDisplay = document.getElementById('input-microred-display');
      const distritoInput = document.getElementById('input-distrito');
      const provinciaInput = document.getElementById('input-provincia');

      if (microredSelect) {
        microredSelect.innerHTML = '<option value="">Seleccione una Microred</option>';
        microredSelect.disabled = true;
        microredSelect.value = '';
      }

      if (eessSelect) {
        eessSelect.innerHTML = '<option value="">Seleccione un Establecimiento</option>';
        eessSelect.disabled = true;
        eessSelect.value = '';
      }

      if (redSelect) {
        redSelect.value = '';
      }

      // Limpiar campos readonly
      if (redDisplay) redDisplay.value = '';
      if (microredDisplay) microredDisplay.value = '';
      if (distritoInput) distritoInput.value = '';
      if (provinciaInput) provinciaInput.value = '';
    }

    // Variable para verificar si los eventos ya fueron inicializados
    let modalSelectsInitialized = false;
    window.modalSelectsInitialized = false; // También disponible globalmente

    function initModalSelects() {
      if (modalSelectsInitialized) return; // Evitar múltiples inicializaciones

      const redSelect = document.getElementById('modalCodigoRed');
      const microredSelect = document.getElementById('modalCodigoMicrored');
      const eessSelect = document.getElementById('modalIdEstablecimiento');

      if (!redSelect || !microredSelect || !eessSelect) return;

      // Evento cambio de Red en el modal
      redSelect.addEventListener('change', function() {
        const redValue = this.value;
        const redDisplay = document.getElementById('input-red-display');

        // Actualizar campo readonly de Red
        if (redDisplay) {
          if (redValue) {
            const selectedOption = this.options[this.selectedIndex];
            redDisplay.value = selectedOption ? selectedOption.textContent : '';
          } else {
            redDisplay.value = '';
          }
        }

        // Limpiar microrred y establecimiento
        microredSelect.innerHTML = '<option value="">Seleccione una Microred</option>';
        eessSelect.innerHTML = '<option value="">Seleccione un Establecimiento</option>';

        // Limpiar campos readonly dependientes
        const microredDisplay = document.getElementById('input-microred-display');
        const distritoInput = document.getElementById('input-distrito');
        const provinciaInput = document.getElementById('input-provincia');
        if (microredDisplay) microredDisplay.value = '';
        if (distritoInput) distritoInput.value = '';
        if (provinciaInput) provinciaInput.value = '';

        // Deshabilitar selects dependientes
        microredSelect.disabled = true;
        eessSelect.disabled = true;

        // Si se seleccionó una red válida
        if (redValue && typeof data !== 'undefined' && data[redValue]) {
          // Habilitar microrred
          microredSelect.disabled = false;

          // Llenar microrredes
          Object.keys(data[redValue]).forEach((microredNombre) => {
            const option = document.createElement('option');
            option.value = microredNombre;
            option.textContent = microredNombre;
            microredSelect.appendChild(option);
          });
        }
      });

      // Evento cambio de MicroRed en el modal
      microredSelect.addEventListener('change', function() {
        const redValue = redSelect.value;
        const microredValue = this.value;
        const microredDisplay = document.getElementById('input-microred-display');

        // Actualizar campo readonly de Microrred
        if (microredDisplay) {
          if (microredValue) {
            const selectedOption = this.options[this.selectedIndex];
            microredDisplay.value = selectedOption ? selectedOption.textContent : '';
          } else {
            microredDisplay.value = '';
          }
        }

        // Limpiar establecimiento y campos readonly dependientes
        eessSelect.innerHTML = '<option value="">Seleccione un Establecimiento</option>';
        const distritoInput = document.getElementById('input-distrito');
        const provinciaInput = document.getElementById('input-provincia');
        if (distritoInput) distritoInput.value = '';
        if (provinciaInput) provinciaInput.value = '';

        // Deshabilitar establecimiento
        eessSelect.disabled = true;

        // Si se seleccionó una microrred válida
        if (redValue && microredValue && typeof data !== 'undefined' && data[redValue] && data[redValue][microredValue]) {
          // Habilitar establecimiento
          eessSelect.disabled = false;

          // Llenar establecimientos
          data[redValue][microredValue].forEach((establecimiento) => {
            const option = document.createElement('option');
            option.value = establecimiento.value;
            option.textContent = establecimiento.text;
            eessSelect.appendChild(option);
          });
        }
      });

      // Evento cambio de Establecimiento en el modal
      eessSelect.addEventListener('change', function() {
        const establecimientoValue = this.value;
        const selectedOption = this.options[this.selectedIndex];
        const nombreEstablecimiento = selectedOption ? selectedOption.textContent : '';

        // Actualizar el campo Nombre_Establecimiento con el nombre del establecimiento seleccionado
        const nombreEstablecimientoInput = document.querySelector('input[name="Nombre_Establecimiento"]');
        if (nombreEstablecimientoInput && nombreEstablecimiento) {
          nombreEstablecimientoInput.value = nombreEstablecimiento;
        }

        // Aquí podrías agregar lógica para llenar Distrito y Provincia
        // basándote en el establecimiento seleccionado si tienes esa información
        // Por ahora, estos campos quedan como readonly y se llenarían desde el backend
      });

      modalSelectsInitialized = true;
      window.modalSelectsInitialized = true;
    }

    // ========== FUNCIONES PARA MODAL VER CONTROLES ==========
    // Función para abrir el modal de Datos Extras
    // Variable global para guardar el ID del niño actual
    let datosExtrasNinoId = null;
    let datosExtrasDocumento = null;
    let datosExtrasOriginales = {};

    function openDatosExtrasModal(ninoId, nombre, documento) {
      const modal = document.getElementById('datosExtrasModal');
      if (!modal) {
        console.error('Modal datosExtrasModal no encontrado');
        return;
      }

      datosExtrasDocumento = documento;
      datosExtrasNinoId = ninoId || null;

      // Mostrar el modal inmediatamente
      modal.classList.remove('hidden');
      modal.classList.add('flex');

      // Asegurar que estemos en modo vista (solo lectura)

      // Obtener datos reales desde la base de datos
      fetch('{{ route("api.nino.datos-extras") }}?documento=' + encodeURIComponent(documento), {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success && data.data) {
          const nino = data.data;
          
          // Guardar el ID del niño (usar el que se pasó como parámetro o el de la respuesta)
          const ninoIdFinal = datosExtrasNinoId || nino.id_niño || nino.id;
          if (ninoIdFinal) {
            datosExtrasNinoId = ninoIdFinal;
          }
          
          // Guardar datos originales para poder cancelar
          datosExtrasOriginales = {
            red: String(nino.codigo_red || ''),
            microred: nino.codigo_microred || '',
            eess_nacimiento: nino.id_establecimiento || '',
            distrito: nino.distrito || '',
            provincia: nino.provincia || '',
            departamento: nino.departamento || '',
            seguro: nino.seguro || '',
            programa: nino.programa || ''
          };

          // Función para obtener el nombre de la red desde el código
          function obtenerNombreRed(codigoRed) {
            const nombresRedes = {
              '1': 'AGUAYTIA',
              '2': 'ATALAYA',
              '3': 'BAP-CURARAY',
              '4': 'CORONEL PORTILLO',
              '5': 'ESSALUD',
              '6': 'FEDERICO BASADRE - YARINACOCHA',
              '7': 'HOSPITAL AMAZONICO - YARINACOCHA',
              '8': 'HOSPITAL REGIONAL DE PUCALLPA',
              '9': 'NO PERTENECE A NINGUNA RED'
            };
            return nombresRedes[codigoRed] || codigoRed || '-';
          }

          // Función para obtener el nombre del establecimiento
          function obtenerNombreEstablecimiento(codigoRed, codigoMicrored, idEstablecimiento) {
            // Intentar obtener desde el objeto data del archivo formulario-selec-de-EESS.js si está disponible
            // El objeto data se define en ese archivo y contiene el mapeo completo
            if (typeof data !== 'undefined' && data[codigoRed] && data[codigoRed][codigoMicrored]) {
              const establecimientos = data[codigoRed][codigoMicrored];
              const establecimiento = establecimientos.find(est => est.value === idEstablecimiento);
              if (establecimiento && establecimiento.text) {
                return establecimiento.text;
              }
            }
            // Si no se encuentra en el objeto data, mostrar el nombre del establecimiento guardado
            // o el valor del campo establecimiento
            return nino.establecimiento || nino.nombre_establecimiento || idEstablecimiento || '-';
          }

          // Obtener nombres de Red, MicroRed y Establecimiento
          const codigoRed = String(nino.codigo_red || '');
          const codigoMicrored = nino.codigo_microred || '';
          const idEstablecimiento = nino.id_establecimiento || '';

          const nombreRed = obtenerNombreRed(codigoRed);
          const nombreMicrored = codigoMicrored || '-';
          const nombreEstablecimiento = obtenerNombreEstablecimiento(
            codigoRed,
            codigoMicrored,
            idEstablecimiento
          );

          // Llenar los campos de Red, MicroRed y Establecimiento
          const redElement = document.getElementById('datosExtras-red');
          const microredElement = document.getElementById('datosExtras-microred');
          const establecimientoElement = document.getElementById('datosExtras-establecimiento');

          if (redElement) {
            redElement.textContent = nombreRed;
          }
          if (microredElement) {
            microredElement.textContent = nombreMicrored;
          }
          if (establecimientoElement) {
            establecimientoElement.textContent = nombreEstablecimiento;
          }

          // Llenar los campos del modal con datos reales
          const distritoEl = document.getElementById('datosExtras-distrito');
          const provinciaEl = document.getElementById('datosExtras-provincia');
          const departamentoEl = document.getElementById('datosExtras-departamento');
          const seguroEl = document.getElementById('datosExtras-seguro');
          const programaEl = document.getElementById('datosExtras-programa');
          
          if (distritoEl) {
            distritoEl.textContent = nino.distrito || '-';
          }
          if (provinciaEl) {
            provinciaEl.textContent = nino.provincia || '-';
          }
          if (departamentoEl) {
            departamentoEl.textContent = nino.departamento || '-';
          }
          if (seguroEl) {
            seguroEl.textContent = nino.seguro || '-';
          }
          if (programaEl) {
            programaEl.textContent = nino.programa || '-';
          }
          document.getElementById('datosExtras-dni-madre').textContent = nino.dni_madre || '-';
          document.getElementById('datosExtras-nombre-madre').textContent = nino.apellidos_nombres_madre || '-';
          document.getElementById('datosExtras-celular-madre').textContent = nino.celular_madre || '-';
          document.getElementById('datosExtras-domicilio-madre').textContent = nino.domicilio_madre || '-';
          document.getElementById('datosExtras-referencia-madre').textContent = nino.referencia_direccion || '-';
        } else {
          // Si no se encuentran datos, mostrar guiones
          document.getElementById('datosExtras-red').textContent = '-';
          document.getElementById('datosExtras-microred').textContent = '-';
          document.getElementById('datosExtras-establecimiento').textContent = '-';
          document.getElementById('datosExtras-distrito').textContent = '-';
          document.getElementById('datosExtras-provincia').textContent = '-';
          document.getElementById('datosExtras-departamento').textContent = '-';
          document.getElementById('datosExtras-seguro').textContent = '-';
          document.getElementById('datosExtras-programa').textContent = '-';
          document.getElementById('datosExtras-dni-madre').textContent = '-';
          document.getElementById('datosExtras-nombre-madre').textContent = '-';
          document.getElementById('datosExtras-celular-madre').textContent = '-';
          document.getElementById('datosExtras-domicilio-madre').textContent = '-';
          document.getElementById('datosExtras-referencia-madre').textContent = '-';
        }
      })
      .catch(error => {
        console.error('Error al obtener datos extras:', error);
        // Mostrar guiones en caso de error
        document.getElementById('datosExtras-red').textContent = '-';
        document.getElementById('datosExtras-microred').textContent = '-';
        document.getElementById('datosExtras-establecimiento').textContent = '-';
        document.getElementById('datosExtras-distrito').textContent = '-';
        document.getElementById('datosExtras-provincia').textContent = '-';
        document.getElementById('datosExtras-departamento').textContent = '-';
        document.getElementById('datosExtras-seguro').textContent = '-';
        document.getElementById('datosExtras-programa').textContent = '-';
        document.getElementById('datosExtras-dni-madre').textContent = '-';
        document.getElementById('datosExtras-nombre-madre').textContent = '-';
        document.getElementById('datosExtras-celular-madre').textContent = '-';
        document.getElementById('datosExtras-domicilio-madre').textContent = '-';
        document.getElementById('datosExtras-referencia-madre').textContent = '-';
      });

      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    // Función para cerrar el modal de Datos Extras
    function closeDatosExtrasModal(event) {
      if (event && event.target !== event.currentTarget) return;
      const modal = document.getElementById('datosExtrasModal');
      if (!modal) return;
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function openVerControlesModal(ninoId, nombre, dni, eess) {
      // Guardar ID del niño seleccionado en variable local y global
      ninoIdActual = ninoId;
      if (typeof window !== 'undefined') {
        window.ninoIdActual = ninoId;
      }
      const modal = document.getElementById('verControlesModal');
      if (!modal) return;

      // Logging removido - las rutas de logs fueron eliminadas del sistema

      // Ocultar todos los tabs primero
      document.querySelectorAll('.tab-content').forEach(tab => {
        tab.style.display = 'none';
        tab.style.visibility = 'hidden';
        tab.style.opacity = '0';
      });

      // Asegurar que el tab activo por defecto se muestre correctamente
      setTimeout(() => {
        const activeTab = document.querySelector('.tab-content.active');
        if (activeTab) {
          activeTab.style.display = 'block';
          activeTab.style.visibility = 'visible';
          activeTab.style.opacity = '1';

          // Asegurar que todos los elementos dentro del tab activo sean visibles
          const allElements = activeTab.querySelectorAll('.control-section, .controles-grid, .info-card, .visitas-list, .vacunas-grid, .control-card, .visita-item, .vacuna-card, .info-row, .section-header, .control-card-body, .control-info-item');
          allElements.forEach(el => {
            el.style.display = '';
            el.style.visibility = 'visible';
            el.style.opacity = '1';

            // Si es control-card-body, asegurar display block
            if (el.classList.contains('control-card-body')) {
              el.style.display = 'block';
              // Asegurar que los <p> dentro también sean visibles
              const paragraphs = el.querySelectorAll('p');
              paragraphs.forEach(p => {
                p.style.display = 'block';
                p.style.visibility = 'visible';
                p.style.opacity = '1';
              });
              // Asegurar que los spans dentro de los <p> también sean visibles
              const spans = el.querySelectorAll('p span');
              spans.forEach(span => {
                span.style.display = 'inline';
                span.style.visibility = 'visible';
                span.style.opacity = '1';
              });
            }
            // Si es control-info-item, asegurar display flex
            if (el.classList.contains('control-info-item')) {
              el.style.display = 'flex';
            }
          });

          // Asegurar específicamente que control-info-item se muestren
          const infoItems = activeTab.querySelectorAll('.control-info-item');
          infoItems.forEach(item => {
            item.style.display = 'flex';
            item.style.visibility = 'visible';
            item.style.opacity = '1';
          });
        }
      }, 100);

      // Actualizar información del paciente si se proporcionan datos
      if (nombre && dni) {
        const nameElement = document.getElementById('modalPatientName');
        const infoElement = document.getElementById('modalPatientInfo');
        if (nameElement) {
          nameElement.innerHTML = '<strong>' + nombre + '</strong>';
        }
        if (infoElement) {
          // Obtener tipo de documento del niño desde la tabla
          const ninoEnTabla = todosLosNinos.find(n => (n.id_niño || n.id) === ninoId);
          const tipoDoc = ninoEnTabla ? obtenerTipoDocumento(ninoEnTabla.tipo_doc || ninoEnTabla.id_tipo_documento) : 'DNI';
          infoElement.textContent = tipoDoc + ': ' + dni + ' • ' + (eess || 'No registrado');
        }
      }

      // Obtener y mostrar fecha de nacimiento
      if (ninoId && dni) {
        fetch(`{{ route("api.nino.datos-extras") }}?documento=${dni}`, {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success && data.data && data.data.fecha_nacimiento) {
            // Manejar fecha sin problemas de zona horaria
            const fechaNacimientoStr = data.data.fecha_nacimiento;
            const fechaISO = formatearFechaISO(fechaNacimientoStr);
            const fechaNacimiento = crearFechaLocal(fechaNacimientoStr);

            const fechaFormateada = fechaNacimiento.toLocaleDateString('es-PE', {
              year: 'numeric',
              month: 'long',
              day: 'numeric'
            });

            // Mostrar en el header del modal
            const fechaNacimientoHeader = document.getElementById('fechaNacimientoValue');
            if (fechaNacimientoHeader) {
              fechaNacimientoHeader.textContent = fechaFormateada + ' (' + fechaISO + ')';
            }

            // Mostrar en la sección de Control Recién Nacido
            const fechaNacimientoControl = document.getElementById('fecha-nacimiento-control-recien-nacido');
            if (fechaNacimientoControl) {
              fechaNacimientoControl.textContent = fechaFormateada + ' (' + fechaISO + ')';
            }

            // Mostrar en la sección de CRED Mensual
            const fechaNacimientoCredMensual = document.getElementById('fecha-nacimiento-cred-mensual');
            if (fechaNacimientoCredMensual) {
              fechaNacimientoCredMensual.textContent = fechaFormateada + ' (' + fechaISO + ')';
            }

            // Mostrar en la sección de Tamizaje Neonatal
            const fechaNacimientoTamizaje = document.getElementById('fecha-nacimiento-tamizaje');
            if (fechaNacimientoTamizaje) {
              fechaNacimientoTamizaje.textContent = fechaFormateada + ' (' + fechaISO + ')';
            }

            // Mostrar en la sección de Vacunas RN
            const fechaNacimientoVacunas = document.getElementById('fecha-nacimiento-vacunas');
            if (fechaNacimientoVacunas) {
              fechaNacimientoVacunas.textContent = fechaFormateada + ' (' + fechaISO + ')';
            }

            // Mostrar en la sección de Visitas Domiciliarias
            const fechaNacimientoVisitas = document.getElementById('fecha-nacimiento-visitas');
            if (fechaNacimientoVisitas) {
              fechaNacimientoVisitas.textContent = fechaFormateada + ' (' + fechaISO + ')';
            }
          } else {
            // Si no se encuentra, intentar obtener desde la tabla de niños
            const ninoEnTabla = todosLosNinos.find(n => n.id === ninoId);
            if (ninoEnTabla && ninoEnTabla.fecha_nacimiento) {
              // Manejar fecha sin problemas de zona horaria
              const fechaNacimientoStr = ninoEnTabla.fecha_nacimiento;
              const fechaISO = formatearFechaISO(fechaNacimientoStr);
              const fechaNacimiento = crearFechaLocal(fechaNacimientoStr);

              const fechaFormateada = fechaNacimiento.toLocaleDateString('es-PE', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
              });

              const fechaNacimientoHeader = document.getElementById('fechaNacimientoValue');
              if (fechaNacimientoHeader) {
                fechaNacimientoHeader.textContent = fechaFormateada + ' (' + fechaISO + ')';
              }

              const fechaNacimientoControl = document.getElementById('fecha-nacimiento-control-recien-nacido');
              if (fechaNacimientoControl) {
                fechaNacimientoControl.textContent = fechaFormateada + ' (' + fechaISO + ')';
              }

              const fechaNacimientoCredMensual = document.getElementById('fecha-nacimiento-cred-mensual');
              if (fechaNacimientoCredMensual) {
                fechaNacimientoCredMensual.textContent = fechaFormateada + ' (' + fechaISO + ')';
              }

              const fechaNacimientoTamizaje = document.getElementById('fecha-nacimiento-tamizaje');
              if (fechaNacimientoTamizaje) {
                fechaNacimientoTamizaje.textContent = fechaFormateada + ' (' + fechaISO + ')';
              }

              const fechaNacimientoVisitas = document.getElementById('fecha-nacimiento-visitas');
              if (fechaNacimientoVisitas) {
                fechaNacimientoVisitas.textContent = fechaFormateada + ' (' + fechaISO + ')';
              }

              const fechaNacimientoVacunas = document.getElementById('fecha-nacimiento-vacunas');
              if (fechaNacimientoVacunas) {
                fechaNacimientoVacunas.textContent = fechaFormateada + ' (' + fechaISO + ')';
              }
            }
          }
        })
        .catch(error => {
          console.error('Error al obtener fecha de nacimiento:', error);
          // Intentar obtener desde la tabla de niños como respaldo
          const ninoEnTabla = todosLosNinos.find(n => n.id === ninoId);
          if (ninoEnTabla && ninoEnTabla.fecha_nacimiento) {
            // Manejar fecha sin problemas de zona horaria
            const fechaNacimientoStr = ninoEnTabla.fecha_nacimiento;
            let fechaISO = fechaNacimientoStr;
            if (fechaNacimientoStr.includes('T')) {
              fechaISO = fechaNacimientoStr.split('T')[0];
            }

            // Crear fecha local sin conversión de zona horaria
            const [year, month, day] = fechaISO.split('-').map(Number);
            const fechaNacimiento = new Date(year, month - 1, day);

            const fechaFormateada = fechaNacimiento.toLocaleDateString('es-PE', {
              year: 'numeric',
              month: 'long',
              day: 'numeric'
            });

            const fechaNacimientoHeader = document.getElementById('fechaNacimientoValue');
            if (fechaNacimientoHeader) {
              fechaNacimientoHeader.textContent = fechaFormateada + ' (' + fechaISO + ')';
            }

            const fechaNacimientoControl = document.getElementById('fecha-nacimiento-control-recien-nacido');
            if (fechaNacimientoControl) {
              fechaNacimientoControl.textContent = fechaFormateada + ' (' + fechaISO + ')';
            }

            const fechaNacimientoCredMensual = document.getElementById('fecha-nacimiento-cred-mensual');
            if (fechaNacimientoCredMensual) {
              fechaNacimientoCredMensual.textContent = fechaFormateada + ' (' + fechaISO + ')';
            }

            const fechaNacimientoTamizaje = document.getElementById('fecha-nacimiento-tamizaje');
            if (fechaNacimientoTamizaje) {
              fechaNacimientoTamizaje.textContent = fechaFormateada + ' (' + fechaISO + ')';
            }

            const fechaNacimientoVisitas = document.getElementById('fecha-nacimiento-visitas');
            if (fechaNacimientoVisitas) {
              fechaNacimientoVisitas.textContent = fechaFormateada + ' (' + fechaISO + ')';
            }

            const fechaNacimientoVacunas = document.getElementById('fecha-nacimiento-vacunas');
            if (fechaNacimientoVacunas) {
              fechaNacimientoVacunas.textContent = fechaFormateada + ' (' + fechaISO + ')';
            }
          }
        });
      }

      // Mostrar el modal primero
      modal.classList.remove('hidden');
      modal.style.display = 'flex';

      // Asegurar que el tab "Control Recién Nacido" esté activo por defecto
      setTimeout(() => {
        const recienNacidoTabButton = document.querySelector('[data-testid="tab-recien-nacido"]');
        if (recienNacidoTabButton) {
          cambiarTab('recien-nacido', recienNacidoTabButton);
        } else {
          // Si no encuentra el botón, activar el tab directamente
          const activeTab = document.querySelector('.tab-content.active');
          if (activeTab) {
            activeTab.style.display = 'block';
            activeTab.style.visibility = 'visible';
            activeTab.style.opacity = '1';
          }
        }
      }, 100);

      // Cargar controles existentes si hay
      if (ninoId) {
        console.log('Abriendo modal con ninoId:', ninoId);
        // Esperar a que el modal esté completamente visible antes de cargar datos
        setTimeout(() => {
          console.log('Cargando todos los controles para ninoId:', ninoId);

          // SIEMPRE intentar cargar datos reales desde la base de datos
          if (typeof cargarDatosControles === 'function') {
            cargarDatosControles(ninoId)
              .then(() => {
                console.log('✅ Datos reales cargados desde la base de datos');
                // Validar rangos después de cargar datos reales
                if (typeof validarRangosYHabilitarBotones === 'function') {
                  setTimeout(() => validarRangosYHabilitarBotones(ninoId), 500);
                }
              })
              .catch(error => {
                console.error('❌ Error al cargar datos reales:', error);
                // Si hay error, las tablas quedarán vacías (comportamiento esperado si no hay datos)
              });
          } else {
            console.error('❌ La función cargarDatosControles no está disponible');
            // Si la función no existe, intentar funciones individuales como último recurso
            console.log('ℹ️ Intentando cargar datos con funciones individuales...');
            let datosCargados = false;

            if (typeof cargarControlesRecienNacido === 'function') {
              cargarControlesRecienNacido(ninoId);
              datosCargados = true;
            }
            if (typeof cargarControlesCredMensual === 'function') {
              cargarControlesCredMensual(ninoId);
              datosCargados = true;
            }
            if (typeof cargarTamizaje === 'function') {
              cargarTamizaje(ninoId);
              datosCargados = true;
            }
            if (typeof cargarVisitas === 'function') {
              cargarVisitas(ninoId);
              datosCargados = true;
            }
            if (typeof cargarVacunas === 'function') {
              cargarVacunas(ninoId);
              datosCargados = true;
            }
            if (typeof cargarCNV === 'function') {
              cargarCNV(ninoId);
              datosCargados = true;
            }

            // NO usar datos simulados - si no hay datos en la BD, las tablas estarán vacías
            if (!datosCargados) {
              console.log('ℹ️ No se encontraron funciones para cargar datos. Las tablas mostrarán datos vacíos si el niño no tiene controles registrados.');
            }
          }

          // Evaluar alertas si la función existe
          if (typeof evaluarAlertas === 'function') {
            evaluarAlertas(ninoId, nombre, dni, eess);
          }

          // Validar y habilitar/deshabilitar botones según rangos
          if (typeof validarRangosYHabilitarBotones === 'function') {
            setTimeout(() => validarRangosYHabilitarBotones(ninoId), 800);
          }
        }, 500);
      }
    }

    // ========== FUNCIÓN AUXILIAR PARA CREAR FECHA SIN PROBLEMAS DE ZONA HORARIA ==========
    function crearFechaLocal(fechaStr) {
      // Si la fecha viene como string ISO (YYYY-MM-DD o YYYY-MM-DDTHH:mm:ss)
      let fechaISO = fechaStr;
      if (fechaStr.includes('T')) {
        fechaISO = fechaStr.split('T')[0];
      }

      // Crear fecha local sin conversión de zona horaria
      const [year, month, day] = fechaISO.split('-').map(Number);
      return new Date(year, month - 1, day);
    }

    // ========== FUNCIÓN AUXILIAR PARA CALCULAR EDAD EN DÍAS ==========
    function calcularEdadDias(fechaNacimiento, fechaControl) {
      const nacimiento = crearFechaLocal(fechaNacimiento);
      const control = crearFechaLocal(fechaControl);
      const diffTime = control - nacimiento;
      const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
      return diffDays;
    }

    // ========== FUNCIÓN AUXILIAR PARA FORMATEAR FECHA ISO ==========
    function formatearFechaISO(fechaStr) {
      let fechaISO = fechaStr;
      if (fechaStr.includes('T')) {
        fechaISO = fechaStr.split('T')[0];
      }
      return fechaISO;
    }

    // ========== FUNCIÓN PARA VALIDAR RANGOS Y HABILITAR/DESHABILITAR BOTONES ==========
    // Debounce para prevenir ejecuciones múltiples
    let validacionTimeout = null;
    let validacionEnProceso = false;

    function validarRangosYHabilitarBotones(ninoId) {
      // Cancelar timeout anterior si existe
      if (validacionTimeout) {
        clearTimeout(validacionTimeout);
        validacionTimeout = null;
      }

      // Si ya está en proceso, esperar
      if (validacionEnProceso) {
        validacionTimeout = setTimeout(() => validarRangosYHabilitarBotones(ninoId), 200);
        return;
      }

      validacionEnProceso = true;
      console.log('🔍 Validando rangos y habilitando botones para ninoId:', ninoId);

      // Cancelar todos los timeouts pendientes del ModalManager
      ModalManager.cancelarTimeouts();

      // Obtener fecha de nacimiento (intentar desde header o desde sección de visitas)
      let fechaNacimientoText = document.getElementById('fechaNacimientoValue')?.textContent;
      if (!fechaNacimientoText || fechaNacimientoText === '-') {
        // Intentar obtener desde la sección de visitas domiciliarias
        fechaNacimientoText = document.getElementById('fecha-nacimiento-visitas')?.textContent;
      }
      if (!fechaNacimientoText || fechaNacimientoText === '-') {
        console.warn('⚠️ No se encontró fecha de nacimiento');
        validacionEnProceso = false;
        return;
      }

      // Extraer fecha ISO del texto (formato: "DD de MES de YYYY (YYYY-MM-DD)")
      const fechaMatch = fechaNacimientoText.match(/\((\d{4}-\d{2}-\d{2})\)/);
      if (!fechaMatch) {
        // Intentar obtener directamente si es formato ISO
        const fechaDirecta = fechaNacimientoText.match(/(\d{4}-\d{2}-\d{2})/);
        if (!fechaDirecta) {
          console.warn('⚠️ No se pudo extraer la fecha ISO');
          validacionEnProceso = false;
          return;
        }
        var fechaNacimientoISO = fechaDirecta[1];
      } else {
        var fechaNacimientoISO = fechaMatch[1];
      }

      const fechaNacimiento = crearFechaLocal(fechaNacimientoISO);
      const hoy = new Date();
      hoy.setHours(0, 0, 0, 0);

      // Calcular edad actual en días usando fecha local
      const hoyISO = hoy.getFullYear() + '-' +
                     String(hoy.getMonth() + 1).padStart(2, '0') + '-' +
                     String(hoy.getDate()).padStart(2, '0');
      const edadDiasActual = calcularEdadDias(fechaNacimientoISO, hoyISO);
      console.log('📅 Fecha de nacimiento:', fechaNacimientoISO);
      console.log('📅 Edad actual del niño:', edadDiasActual, 'días');

      // ========== RANGOS PARA CONTROLES RECIÉN NACIDO ==========
      const rangosRecienNacido = {
        1: { min: 2, max: 6 },
        2: { min: 7, max: 13 },
        3: { min: 14, max: 20 },
        4: { min: 21, max: 28 }
      };

      // Validar botones de controles recién nacido
      for (let numControl = 1; numControl <= 4; numControl++) {
        const rango = rangosRecienNacido[numControl];
        const botones = document.querySelectorAll(`button[onclick*="abrirModalRegistro(${numControl}"]`);

        // Obtener información del control registrado
        const fechaControlEl = document.getElementById(`control-${numControl}-fecha`);
        const edadControlEl = document.getElementById(`control-${numControl}-edad`);
        const estadoBadge = document.getElementById(`control-${numControl}-estado`);

        const tieneRegistro = estadoBadge && !estadoBadge.textContent.includes('PENDIENTE') && !estadoBadge.textContent.includes('SEGUIMIENTO');
        let edadDiasControl = null;
        let cumpleRango = false;

        // Si hay control registrado, validar según su fecha
        if (tieneRegistro && fechaControlEl && fechaControlEl.textContent !== '-') {
          // Intentar extraer fecha del texto (formato: DD/MM/YYYY)
          const fechaTexto = fechaControlEl.textContent.trim();
          const fechaMatch = fechaTexto.match(/(\d{2})\/(\d{2})\/(\d{4})/);
          if (fechaMatch) {
            const fechaControlISO = `${fechaMatch[3]}-${fechaMatch[2]}-${fechaMatch[1]}`;
            edadDiasControl = calcularEdadDias(fechaNacimientoISO, fechaControlISO);
            cumpleRango = edadDiasControl >= rango.min && edadDiasControl <= rango.max;

            // Actualizar estado visual si no cumple
            if (estadoBadge) {
              if (cumpleRango) {
                estadoBadge.className = 'estado-badge cumple';
                estadoBadge.textContent = 'CUMPLE';
              } else {
                estadoBadge.className = 'estado-badge no-cumple';
                estadoBadge.textContent = 'NO CUMPLE';
              }
            }
          }
        } else if (!tieneRegistro && edadDiasActual > rango.max) {
          // NO hay control registrado y YA PASÓ el rango - marcar como NO CUMPLE
          if (estadoBadge) {
            estadoBadge.className = 'estado-badge no-cumple';
            estadoBadge.textContent = 'NO CUMPLE';
          }
        }

        botones.forEach(btn => {
          // Remover estilos inline y clases de estado previas
          btn.style.background = '';
          btn.style.opacity = '';
          btn.style.cursor = '';
          btn.classList.remove('btn-registrar-cumple', 'btn-registrar-no-cumple', 'btn-registrar-pendiente', 'btn-registrar-rango-pasado');

          // Limpiar handlers anteriores completamente
          btn.removeAttribute('onclick');
          btn.onclick = null;

          // Guardar onclick original si no está guardado
          const onclickOriginal = btn.getAttribute('data-onclick-original') || '';
          if (!btn.hasAttribute('data-onclick-original') && onclickOriginal) {
            btn.setAttribute('data-onclick-original', onclickOriginal);
          }

          if (tieneRegistro && edadDiasControl !== null) {
            // Control ya registrado - validar según su fecha
            if (cumpleRango) {
              // Si cumple y ya pasó el tiempo límite, deshabilitar
              if (edadDiasActual > rango.max) {
                btn.disabled = false;
                btn.classList.add('btn-registrar-cumple');
                btn.onclick = function(e) {
                  e.preventDefault();
                  e.stopPropagation();
                  const contenido = `<div class="space-y-3">
                      <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                        <div class="flex items-center gap-2 mb-2">
                          <span class="text-green-600 font-bold text-base">✅ CUMPLE</span>
                        </div>
                        <div class="space-y-1.5 text-sm">
                          <div class="flex justify-between">
                            <span class="text-slate-600 font-medium">Fecha del control:</span>
                            <span class="text-slate-800 font-semibold">${fechaControlEl ? fechaControlEl.textContent : '-'}</span>
                          </div>
                          <div class="flex justify-between">
                            <span class="text-slate-600 font-medium">Edad al momento:</span>
                            <span class="text-slate-800 font-semibold">${edadDiasControl} días</span>
                          </div>
                          <div class="flex justify-between">
                            <span class="text-slate-600 font-medium">Rango válido:</span>
                            <span class="text-slate-800 font-semibold">${rango.min} - ${rango.max} días</span>
                          </div>
                        </div>
                      </div>
                      <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                        <p class="text-xs text-amber-800 leading-relaxed">
                          <strong>⚠️ Nota:</strong> Este control ya está registrado y cumple con el rango establecido. El tiempo límite para este control ya pasó (${edadDiasActual} días > ${rango.max} días), por lo que no se pueden agregar más datos manualmente.
                        </p>
                      </div>
                    </div>`;
                  mostrarModalInfoRecienNacido(contenido, numControl.toString());
                };
                btn.title = `✅ Control registrado y CUMPLE. Tiempo límite pasado (${rango.max} días). No se pueden agregar más datos.`;
              } else {
                // Cumple pero aún está en rango, permitir editar
                btn.disabled = false;
                btn.classList.add('btn-registrar-cumple');
                btn.onclick = function(e) {
                  e.preventDefault();
                  e.stopPropagation();
                  mostrarModalInfo(
                    `Control ${numControl} - Registrado`,
                    `<div class="space-y-3">
                      <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                        <div class="flex items-center gap-2 mb-2">
                          <span class="text-green-600 font-bold text-base">✅ CUMPLE</span>
                        </div>
                        <div class="space-y-1.5 text-sm">
                          <div class="flex justify-between">
                            <span class="text-slate-600 font-medium">Fecha del control:</span>
                            <span class="text-slate-800 font-semibold">${fechaControlEl ? fechaControlEl.textContent : '-'}</span>
                          </div>
                          <div class="flex justify-between">
                            <span class="text-slate-600 font-medium">Edad al momento:</span>
                            <span class="text-slate-800 font-semibold">${edadDiasControl} días</span>
                          </div>
                          <div class="flex justify-between">
                            <span class="text-slate-600 font-medium">Rango válido:</span>
                            <span class="text-slate-800 font-semibold">${rango.min} - ${rango.max} días</span>
                          </div>
                        </div>
                      </div>
                      <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <p class="text-xs text-blue-800 leading-relaxed">
                          Puede editar este control haciendo clic en "Editar" o continuar con el registro.
                        </p>
                      </div>
                    </div>`,
                    'cumple'
                  );
                  // También ejecutar la función original para abrir el modal de edición
                  if (onclickOriginal && onclickOriginal.includes('abrirModalRegistro')) {
                    const match = onclickOriginal.match(/abrirModalRegistro\((\d+),\s*(\d+),\s*(\d+)\)/);
                    if (match) {
                      const timeoutId = setTimeout(() => {
                        abrirModalRegistro(parseInt(match[1]), parseInt(match[2]), parseInt(match[3]));
                      }, 300);
                      ModalManager.registrarTimeout(timeoutId);
                    }
                  }
                };
                btn.title = `✅ Control registrado y CUMPLE con el rango (${rango.min}-${rango.max} días). Fue realizado a los ${edadDiasControl} días.`;
              }
            } else {
              // No cumple, permitir editar
              btn.disabled = false;
              btn.classList.add('btn-registrar-no-cumple');
              btn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                mostrarModalInfo(
                  `Control ${numControl} - No Cumple`,
                  `<div class="space-y-3">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                      <div class="flex items-center gap-2 mb-2">
                        <span class="text-red-600 font-bold text-base">❌ NO CUMPLE</span>
                      </div>
                      <div class="space-y-1.5 text-sm">
                        <div class="flex justify-between">
                          <span class="text-slate-600 font-medium">Fecha del control:</span>
                          <span class="text-slate-800 font-semibold">${fechaControlEl ? fechaControlEl.textContent : '-'}</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-600 font-medium">Edad al momento:</span>
                          <span class="text-slate-800 font-semibold">${edadDiasControl} días</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-600 font-medium">Rango válido:</span>
                          <span class="text-slate-800 font-semibold">${rango.min} - ${rango.max} días</span>
                        </div>
                      </div>
                    </div>
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                      <p class="text-xs text-orange-800 leading-relaxed">
                        Este control fue realizado fuera del rango establecido. Puede editar los datos.
                      </p>
                    </div>
                  </div>`,
                  'no-cumple'
                );
                if (onclickOriginal && onclickOriginal.includes('abrirModalRegistro')) {
                  const match = onclickOriginal.match(/abrirModalRegistro\((\d+),\s*(\d+),\s*(\d+)\)/);
                  if (match) {
                    const timeoutId = setTimeout(() => {
                      abrirModalRegistro(parseInt(match[1]), parseInt(match[2]), parseInt(match[3]));
                    }, 300);
                    ModalManager.registrarTimeout(timeoutId);
                  }
                }
              };
              btn.title = `❌ Control registrado pero NO CUMPLE con el rango (${rango.min}-${rango.max} días). Fue realizado a los ${edadDiasControl} días.`;
            }
          } else if (edadDiasActual < rango.min) {
            // Aún no está en el rango
            btn.disabled = false;
            btn.onclick = function(e) {
              e.preventDefault();
              e.stopPropagation();
              mostrarModalInfo(
                `Control ${numControl} - No Disponible`,
                `<div class="space-y-3">
                  <div class="bg-slate-50 border border-slate-200 rounded-lg p-3">
                    <div class="flex items-center gap-2 mb-2">
                      <span class="text-slate-600 font-bold text-base">⏳ PENDIENTE</span>
                    </div>
                    <div class="space-y-1.5 text-sm">
                      <div class="flex justify-between">
                        <span class="text-slate-600 font-medium">Edad actual del niño:</span>
                        <span class="text-slate-800 font-semibold">${edadDiasActual} días</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-slate-600 font-medium">Rango válido:</span>
                        <span class="text-slate-800 font-semibold">${rango.min} - ${rango.max} días</span>
                      </div>
                    </div>
                  </div>
                  <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <p class="text-xs text-blue-800 leading-relaxed">
                      Este control aún no puede realizarse. El niño debe tener al menos <strong>${rango.min} días</strong> de vida.
                    </p>
                  </div>
                </div>`,
                'pendiente'
              );
            };
            btn.title = `Este control debe realizarse entre los días ${rango.min}-${rango.max}. El niño tiene ${edadDiasActual} días.`;
          } else if (edadDiasActual >= rango.min && edadDiasActual <= rango.max) {
            // Está dentro del rango
            btn.disabled = false;
            btn.classList.add('btn-registrar-pendiente');
            btn.onclick = function(e) {
              e.preventDefault();
              e.stopPropagation();
              mostrarModalInfo(
                `Control ${numControl} - Disponible`,
                `<div class="space-y-3">
                  <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <div class="flex items-center gap-2 mb-2">
                      <span class="text-blue-600 font-bold text-base">✅ DISPONIBLE</span>
                    </div>
                    <div class="space-y-1.5 text-sm">
                      <div class="flex justify-between">
                        <span class="text-slate-600 font-medium">Edad actual del niño:</span>
                        <span class="text-slate-800 font-semibold">${edadDiasActual} días</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-slate-600 font-medium">Rango válido:</span>
                        <span class="text-slate-800 font-semibold">${rango.min} - ${rango.max} días</span>
                      </div>
                    </div>
                  </div>
                  <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                    <p class="text-xs text-green-800 leading-relaxed">
                      El niño está dentro del rango establecido. Puede proceder a registrar este control.
                    </p>
                  </div>
                </div>`,
                'disponible',
                onclickOriginal && onclickOriginal.includes('abrirModalRegistro') ? (() => {
                  const match = onclickOriginal.match(/abrirModalRegistro\((\d+),\s*(\d+),\s*(\d+)\)/);
                  if (match) {
                    return () => {
                      abrirModalRegistro(parseInt(match[1]), parseInt(match[2]), parseInt(match[3]));
                    };
                  }
                  return null;
                })() : null
              );
            };
            btn.title = `✅ Dentro del rango (${rango.min}-${rango.max} días). El niño tiene ${edadDiasActual} días.`;
          } else if (edadDiasActual > rango.max && !tieneRegistro) {
            // Ya pasó el rango pero no está registrado - NO CUMPLE
            btn.disabled = false;
            btn.classList.add('btn-registrar-no-cumple');
            btn.onclick = function(e) {
              e.preventDefault();
              e.stopPropagation();
              if (onclickOriginal && onclickOriginal.includes('abrirModalRegistro')) {
                const match = onclickOriginal.match(/abrirModalRegistro\((\d+),\s*(\d+),\s*(\d+)\)/);
                if (match) {
                  mostrarModalInfo(
                    `Control ${numControl} - No Cumple`,
                    `<div class="space-y-3">
                      <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <div class="flex items-center gap-2 mb-2">
                          <span class="text-red-600 font-bold text-base">❌ NO CUMPLE</span>
                        </div>
                        <div class="space-y-1.5 text-sm">
                          <div class="flex justify-between">
                            <span class="text-slate-600 font-medium">Edad actual del niño:</span>
                            <span class="text-slate-800 font-semibold">${edadDiasActual} días</span>
                          </div>
                          <div class="flex justify-between">
                            <span class="text-slate-600 font-medium">Rango válido:</span>
                            <span class="text-slate-800 font-semibold">${rango.min} - ${rango.max} días</span>
                          </div>
                        </div>
                      </div>
                      <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <p class="text-xs text-red-800 leading-relaxed">
                          El control no se realizó dentro del rango establecido. El niño ya tiene ${edadDiasActual} días y el rango máximo era de ${rango.max} días.
                        </p>
                      </div>
                    </div>`,
                    'no-cumple',
                    () => {
                      abrirModalRegistro(parseInt(match[1]), parseInt(match[2]), parseInt(match[3]));
                    }
                  );
                }
              } else {
                mostrarModalInfo(
                  `Control ${numControl} - No Cumple`,
                  `<div class="space-y-3">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                      <div class="flex items-center gap-2 mb-2">
                        <span class="text-red-600 font-bold text-base">❌ NO CUMPLE</span>
                      </div>
                      <div class="space-y-1.5 text-sm">
                        <div class="flex justify-between">
                          <span class="text-slate-600 font-medium">Edad actual del niño:</span>
                          <span class="text-slate-800 font-semibold">${edadDiasActual} días</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-600 font-medium">Rango válido:</span>
                          <span class="text-slate-800 font-semibold">${rango.min} - ${rango.max} días</span>
                        </div>
                      </div>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                      <p class="text-xs text-red-800 leading-relaxed">
                        El control no se realizó dentro del rango establecido. El niño ya tiene ${edadDiasActual} días y el rango máximo era de ${rango.max} días.
                      </p>
                    </div>
                  </div>`,
                  'no-cumple'
                );
              }
            };
            btn.title = `❌ NO CUMPLE - Rango pasado (${rango.min}-${rango.max} días). El niño tiene ${edadDiasActual} días.`;
          }
        });
      }

      // ========== RANGOS PARA CRED MENSUAL ==========
      const rangosCredMensual = {
        1: { min: 29, max: 59 },
        2: { min: 60, max: 89 },
        3: { min: 90, max: 119 },
        4: { min: 120, max: 149 },
        5: { min: 150, max: 179 },
        6: { min: 180, max: 209 },
        7: { min: 210, max: 239 },
        8: { min: 240, max: 269 },
        9: { min: 270, max: 299 },
        10: { min: 300, max: 329 },
        11: { min: 330, max: 359 }
      };

      // Validar botones de CRED mensual
      for (let mes = 1; mes <= 11; mes++) {
        const rango = rangosCredMensual[mes];
        const botones = document.querySelectorAll(`button[onclick*="abrirModalCredMensual(${mes})"]`);

        // Obtener información del control registrado
        const fechaControlEl = document.getElementById(`fo_cred_${mes}`);
        const edadControlEl = document.getElementById(`edad_cred_${mes}`);
        const estadoBadge = document.getElementById(`estado_cred_${mes}`);

        const tieneRegistro = estadoBadge && !estadoBadge.textContent.includes('PENDIENTE') && !estadoBadge.textContent.includes('SEGUIMIENTO');
        let edadDiasControl = null;
        let cumpleRango = false;

        // Si hay control registrado, validar según su fecha
        if (tieneRegistro && fechaControlEl && fechaControlEl.textContent !== '-') {
          // Intentar extraer fecha del texto (formato: DD/MM/YYYY)
          const fechaTexto = fechaControlEl.textContent.trim();
          const fechaMatch = fechaTexto.match(/(\d{2})\/(\d{2})\/(\d{4})/);
          if (fechaMatch) {
            const fechaControlISO = `${fechaMatch[3]}-${fechaMatch[2]}-${fechaMatch[1]}`;
            edadDiasControl = calcularEdadDias(fechaNacimientoISO, fechaControlISO);
            cumpleRango = edadDiasControl >= rango.min && edadDiasControl <= rango.max;

            // Actualizar estado visual si no cumple
            if (estadoBadge) {
              if (cumpleRango) {
                estadoBadge.className = 'estado-badge cumple';
                estadoBadge.textContent = 'CUMPLE';
              } else {
                estadoBadge.className = 'estado-badge no-cumple';
                estadoBadge.textContent = 'NO CUMPLE';
              }
            }
          }
        } else if (!tieneRegistro && edadDiasActual > rango.max) {
          // NO hay control registrado y YA PASÓ el rango - marcar como NO CUMPLE
          if (estadoBadge) {
            estadoBadge.className = 'estado-badge no-cumple';
            estadoBadge.textContent = 'NO CUMPLE';
          }
        }

        botones.forEach(btn => {
          // Remover estilos inline y clases de estado previas
          btn.style.background = '';
          btn.style.opacity = '';
          btn.style.cursor = '';
          btn.classList.remove('btn-registrar-cumple', 'btn-registrar-no-cumple', 'btn-registrar-pendiente', 'btn-registrar-rango-pasado');

          // Limpiar handlers anteriores completamente
          btn.removeAttribute('onclick');
          btn.onclick = null;

          // Guardar onclick original si no está guardado
          const onclickOriginal = btn.getAttribute('data-onclick-original') || '';
          if (!btn.hasAttribute('data-onclick-original') && onclickOriginal) {
            btn.setAttribute('data-onclick-original', onclickOriginal);
          }

          if (tieneRegistro && edadDiasControl !== null) {
            // Control ya registrado - validar según su fecha
            if (cumpleRango) {
              // Si cumple y ya pasó el tiempo límite, deshabilitar
              if (edadDiasActual > rango.max) {
                btn.disabled = false;
                btn.classList.add('btn-registrar-cumple');
                btn.onclick = function(e) {
                  e.preventDefault();
                  e.stopPropagation();
                  mostrarModalInfo(
                    `CRED Mes ${mes} - Completado`,
                    `<div class="space-y-2">
                      <p><strong>Estado:</strong> <span class="text-green-600 font-semibold">✅ CUMPLE</span></p>
                      <p><strong>Fecha del control:</strong> ${fechaControlEl ? fechaControlEl.textContent : '-'}</p>
                      <p><strong>Edad al momento del control:</strong> ${edadDiasControl} días</p>
                      <p><strong>Rango válido:</strong> ${rango.min} - ${rango.max} días</p>
                      <p class="text-xs text-slate-500 mt-3 pt-3 border-t border-slate-200">
                        <strong>⚠️ Nota:</strong> Este control ya está registrado y cumple con el rango establecido. El tiempo límite para este control ya pasó (${edadDiasActual} días > ${rango.max} días), por lo que no se pueden agregar más datos manualmente.
                      </p>
                    </div>`
                  );
                };
                btn.title = `✅ Control registrado y CUMPLE. Tiempo límite pasado (${rango.max} días). No se pueden agregar más datos.`;
              } else {
                // Cumple pero aún está en rango, permitir editar
                btn.disabled = false;
                btn.classList.add('btn-registrar-cumple');
                btn.onclick = function(e) {
                  e.preventDefault();
                  e.stopPropagation();
                  mostrarModalInfo(
                    `CRED Mes ${mes} - Registrado`,
                    `<div class="space-y-2">
                      <p><strong>Estado:</strong> <span class="text-green-600 font-semibold">✅ CUMPLE</span></p>
                      <p><strong>Fecha del control:</strong> ${fechaControlEl ? fechaControlEl.textContent : '-'}</p>
                      <p><strong>Edad al momento del control:</strong> ${edadDiasControl} días</p>
                      <p><strong>Rango válido:</strong> ${rango.min} - ${rango.max} días</p>
                      <p class="text-xs text-slate-500 mt-3 pt-3 border-t border-slate-200">
                        Puede editar este control haciendo clic en "Editar" o continuar con el registro.
                      </p>
                    </div>`
                  );
                  if (onclickOriginal && onclickOriginal.includes('abrirModalCredMensual')) {
                    const match = onclickOriginal.match(/abrirModalCredMensual\((\d+)\)/);
                    if (match) {
                      const timeoutId = setTimeout(() => abrirModalCredMensual(parseInt(match[1])), 300);
              ModalManager.registrarTimeout(timeoutId);
                    }
                  }
                };
                btn.title = `✅ Control registrado y CUMPLE con el rango (${rango.min}-${rango.max} días). Fue realizado a los ${edadDiasControl} días.`;
              }
            } else {
              // No cumple, permitir editar
              btn.disabled = false;
              btn.classList.add('btn-registrar-no-cumple');
              btn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                mostrarModalInfo(
                  `CRED Mes ${mes} - No Cumple`,
                  `<div class="space-y-2">
                    <p><strong>Estado:</strong> <span class="text-red-600 font-semibold">❌ NO CUMPLE</span></p>
                    <p><strong>Fecha del control:</strong> ${fechaControlEl ? fechaControlEl.textContent : '-'}</p>
                    <p><strong>Edad al momento del control:</strong> ${edadDiasControl} días</p>
                    <p><strong>Rango válido:</strong> ${rango.min} - ${rango.max} días</p>
                    <p class="text-xs text-red-600 mt-3 pt-3 border-t border-red-200">
                      Este control fue realizado fuera del rango establecido. Puede editar los datos.
                    </p>
                  </div>`
                );
                if (onclickOriginal && onclickOriginal.includes('abrirModalCredMensual')) {
                  const match = onclickOriginal.match(/abrirModalCredMensual\((\d+)\)/);
                  if (match) {
                    const timeoutId = setTimeout(() => abrirModalCredMensual(parseInt(match[1])), 300);
              ModalManager.registrarTimeout(timeoutId);
                  }
                }
              };
              btn.title = `❌ Control registrado pero NO CUMPLE con el rango (${rango.min}-${rango.max} días). Fue realizado a los ${edadDiasControl} días.`;
            }
          } else if (edadDiasActual < rango.min) {
            // Aún no está en el rango
            btn.disabled = false;
            btn.onclick = function(e) {
              e.preventDefault();
              e.stopPropagation();
              mostrarModalInfo(
                `CRED Mes ${mes} - No Disponible`,
                `<div class="space-y-2">
                  <p><strong>Estado:</strong> <span class="text-slate-500 font-semibold">⏳ PENDIENTE</span></p>
                  <p><strong>Edad actual del niño:</strong> ${edadDiasActual} días</p>
                  <p><strong>Rango válido:</strong> ${rango.min} - ${rango.max} días</p>
                  <p class="text-xs text-slate-500 mt-3 pt-3 border-t border-slate-200">
                    Este control aún no puede realizarse. El niño debe tener al menos ${rango.min} días de vida.
                  </p>
                </div>`
              );
            };
            btn.title = `Este control debe realizarse entre los días ${rango.min}-${rango.max}. El niño tiene ${edadDiasActual} días.`;
          } else if (edadDiasActual >= rango.min && edadDiasActual <= rango.max) {
            // Está dentro del rango
            btn.disabled = false;
            btn.classList.add('btn-registrar-pendiente');
            btn.onclick = function(e) {
              e.preventDefault();
              e.stopPropagation();
              const fechaControlEl = document.getElementById(`fo_cred_${mes}`);
              const fechaControl = fechaControlEl && fechaControlEl.textContent !== '-' ? fechaControlEl.textContent : null;
              const tieneControl = fechaControl !== null;
              const contenido = generarContenidoCredMensual(mes, edadDiasActual, rango.min, rango.max, tieneControl, fechaControl);
              if (onclickOriginal && onclickOriginal.includes('abrirModalCredMensual')) {
                const match = onclickOriginal.match(/abrirModalCredMensual\((\d+)\)/);
                if (match) {
                  mostrarModalInfoCredMensual(
                    contenido,
                    mes.toString(),
                    () => {
                      abrirModalCredMensual(parseInt(match[1]));
                    }
                  );
                }
              } else {
                mostrarModalInfoCredMensual(contenido, mes.toString());
              }
            };
            btn.title = `✅ Dentro del rango (${rango.min}-${rango.max} días). El niño tiene ${edadDiasActual} días.`;
          } else if (edadDiasActual > rango.max && !tieneRegistro) {
            // Ya pasó el rango pero no está registrado - NO CUMPLE
            btn.disabled = false;
            btn.classList.add('btn-registrar-no-cumple');
            btn.onclick = function(e) {
              e.preventDefault();
              e.stopPropagation();
              mostrarModalInfo(
                `CRED Mes ${mes} - No Cumple`,
                `<div class="space-y-2">
                  <p><strong>Estado:</strong> <span class="text-red-600 font-semibold">❌ NO CUMPLE</span></p>
                  <p><strong>Edad actual del niño:</strong> ${edadDiasActual} días</p>
                  <p><strong>Rango válido:</strong> ${rango.min} - ${rango.max} días</p>
                  <p class="text-xs text-red-600 mt-3 pt-3 border-t border-red-200">
                    El control no se realizó dentro del rango establecido. El niño ya tiene ${edadDiasActual} días y el rango máximo era de ${rango.max} días.
                  </p>
                </div>`
              );
              if (onclickOriginal && onclickOriginal.includes('abrirModalCredMensual')) {
                const match = onclickOriginal.match(/abrirModalCredMensual\((\d+)\)/);
                if (match) {
                  const timeoutId = setTimeout(() => abrirModalCredMensual(parseInt(match[1])), 300);
              ModalManager.registrarTimeout(timeoutId);
                }
              }
            };
            btn.title = `❌ NO CUMPLE - Rango pasado (${rango.min}-${rango.max} días). El niño tiene ${edadDiasActual} días.`;
          }
        });
      }

      // ========== RANGO PARA TAMIZAJE NEONATAL (1-29 días) ==========
      const botonesTamizaje = document.querySelectorAll('button[onclick*="abrirModalTamizaje()"]');
      const estadoTamizaje = document.getElementById('cumple-tamizaje');
      const fechaTamizajeEl = document.getElementById('fecha-tamizaje-1');
      const tieneTamizaje = estadoTamizaje && !estadoTamizaje.textContent.toUpperCase().includes('SEGUIMIENTO');

      let edadDiasTamizaje = null;
      let cumpleTamizaje = false;

      // Si hay tamizaje registrado, validar según su fecha
      if (tieneTamizaje && fechaTamizajeEl && fechaTamizajeEl.textContent !== '-') {
        const fechaTexto = fechaTamizajeEl.textContent.trim();
        const fechaMatch = fechaTexto.match(/(\d{2})\/(\d{2})\/(\d{4})/);
        if (fechaMatch) {
          const fechaTamizajeISO = `${fechaMatch[3]}-${fechaMatch[2]}-${fechaMatch[1]}`;
          edadDiasTamizaje = calcularEdadDias(fechaNacimientoISO, fechaTamizajeISO);
          cumpleTamizaje = edadDiasTamizaje >= 1 && edadDiasTamizaje <= 29;

          // Actualizar estado visual
          if (estadoTamizaje) {
            if (cumpleTamizaje) {
              estadoTamizaje.className = 'estado-badge cumple';
              estadoTamizaje.textContent = 'CUMPLE';
            } else {
              estadoTamizaje.className = 'estado-badge no-cumple';
              estadoTamizaje.textContent = 'NO CUMPLE';
            }
          }
        }
      }

      botonesTamizaje.forEach(btn => {
        // Remover estilos inline y clases de estado previas
        btn.style.background = '';
        btn.style.opacity = '';
        btn.style.cursor = '';
        btn.classList.remove('btn-registrar-cumple', 'btn-registrar-no-cumple', 'btn-registrar-pendiente', 'btn-registrar-rango-pasado');

        // Guardar onclick original
        const onclickOriginal = btn.getAttribute('data-onclick-original') || btn.getAttribute('onclick') || '';
        if (!btn.hasAttribute('data-onclick-original') && onclickOriginal) {
          btn.setAttribute('data-onclick-original', onclickOriginal);
        }

        if (tieneTamizaje && edadDiasTamizaje !== null) {
          // Tamizaje ya registrado - validar según su fecha
          if (cumpleTamizaje) {
            // Si cumple y ya pasó el tiempo límite, deshabilitar
            if (edadDiasActual > 29) {
              btn.disabled = false;
              btn.classList.add('btn-registrar-cumple');
              btn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                mostrarModalInfo(
                  'Tamizaje Neonatal - Completado',
                  `<div class="space-y-2">
                    <p><strong>Estado:</strong> <span class="text-green-600 font-semibold">✅ CUMPLE</span></p>
                    <p><strong>Fecha del tamizaje:</strong> ${fechaTamizajeEl ? fechaTamizajeEl.textContent : '-'}</p>
                    <p><strong>Edad al momento del tamizaje:</strong> ${edadDiasTamizaje} días</p>
                    <p><strong>Rango válido:</strong> 1 - 29 días</p>
                    <p class="text-xs text-slate-500 mt-3 pt-3 border-t border-slate-200">
                      <strong>⚠️ Nota:</strong> Este tamizaje ya está registrado y cumple con el rango establecido. El tiempo límite ya pasó (${edadDiasActual} días > 29 días), por lo que no se pueden agregar más datos manualmente.
                    </p>
                  </div>`
                );
              };
              btn.title = `✅ Tamizaje registrado y CUMPLE. Tiempo límite pasado (29 días). No se pueden agregar más datos.`;
            } else {
              btn.disabled = false;
              btn.classList.add('btn-registrar-cumple');
              btn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                mostrarModalInfo(
                  'Tamizaje Neonatal - Registrado',
                  `<div class="space-y-2">
                    <p><strong>Estado:</strong> <span class="text-green-600 font-semibold">✅ CUMPLE</span></p>
                    <p><strong>Fecha del tamizaje:</strong> ${fechaTamizajeEl ? fechaTamizajeEl.textContent : '-'}</p>
                    <p><strong>Edad al momento del tamizaje:</strong> ${edadDiasTamizaje} días</p>
                    <p><strong>Rango válido:</strong> 1 - 29 días</p>
                    <p class="text-xs text-slate-500 mt-3 pt-3 border-t border-slate-200">
                      Puede editar este tamizaje.
                    </p>
                  </div>`
                );
                if (onclickOriginal && onclickOriginal.includes('abrirModalTamizaje')) {
                  const timeoutId = setTimeout(() => abrirModalTamizaje(), 300);
                  ModalManager.registrarTimeout(timeoutId);
                }
              };
              btn.title = `✅ Tamizaje registrado y CUMPLE con el rango (1-29 días). Fue realizado a los ${edadDiasTamizaje} días.`;
            }
          } else {
            btn.disabled = false;
            btn.classList.add('btn-registrar-no-cumple');
            btn.onclick = function(e) {
              e.preventDefault();
              e.stopPropagation();
              mostrarModalInfo(
                'Tamizaje Neonatal - No Cumple',
                `<div class="space-y-2">
                  <p><strong>Estado:</strong> <span class="text-red-600 font-semibold">❌ NO CUMPLE</span></p>
                  <p><strong>Fecha del tamizaje:</strong> ${fechaTamizajeEl ? fechaTamizajeEl.textContent : '-'}</p>
                  <p><strong>Edad al momento del tamizaje:</strong> ${edadDiasTamizaje} días</p>
                  <p><strong>Rango válido:</strong> 1 - 29 días</p>
                  <p class="text-xs text-red-600 mt-3 pt-3 border-t border-red-200">
                    Este tamizaje fue realizado fuera del rango establecido. Puede editar los datos.
                  </p>
                </div>`
              );
              if (onclickOriginal && onclickOriginal.includes('abrirModalTamizaje')) {
                const timeoutId = setTimeout(() => abrirModalTamizaje(), 300);
              ModalManager.registrarTimeout(timeoutId);
              }
            };
            btn.title = `❌ Tamizaje registrado pero NO CUMPLE con el rango (1-29 días). Fue realizado a los ${edadDiasTamizaje} días.`;
          }
        } else if (edadDiasActual < 1) {
          btn.disabled = false;
          btn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            mostrarModalInfo(
              'Tamizaje Neonatal - No Disponible',
              `<div class="space-y-2">
                <p><strong>Estado:</strong> <span class="text-slate-500 font-semibold">⏳ PENDIENTE</span></p>
                <p><strong>Edad actual del niño:</strong> ${edadDiasActual} días</p>
                <p><strong>Rango válido:</strong> 1 - 29 días</p>
                <p class="text-xs text-slate-500 mt-3 pt-3 border-t border-slate-200">
                  Este tamizaje aún no puede realizarse. El niño debe tener al menos 1 día de vida.
                </p>
              </div>`
            );
          };
          btn.title = `El tamizaje debe realizarse entre los días 1-29. El niño tiene ${edadDiasActual} días.`;
        } else if (edadDiasActual >= 1 && edadDiasActual <= 29) {
          btn.disabled = false;
          btn.classList.add('btn-registrar-pendiente');
          btn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            const contenido = generarContenidoTamizaje(edadDiasActual, false, null, null, false);
            if (onclickOriginal && onclickOriginal.includes('abrirModalTamizaje')) {
              mostrarModalInfoTamizaje(
                contenido,
                () => {
                  abrirModalTamizaje();
                }
              );
            } else {
              mostrarModalInfoTamizaje(contenido);
            }
          };
          btn.title = `✅ Dentro del rango (1-29 días). El niño tiene ${edadDiasActual} días.`;
        } else if (edadDiasActual > 29 && !tieneTamizaje) {
          btn.disabled = false;
          btn.classList.add('btn-registrar-rango-pasado');
          btn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            mostrarModalInfo(
              'Tamizaje Neonatal - Rango Pasado',
              `<div class="space-y-2">
                <p><strong>Estado:</strong> <span class="text-orange-600 font-semibold">⚠️ RANGO PASADO</span></p>
                <p><strong>Edad actual del niño:</strong> ${edadDiasActual} días</p>
                <p><strong>Rango válido:</strong> 1 - 29 días</p>
                <p class="text-xs text-orange-600 mt-3 pt-3 border-t border-orange-200">
                  El rango óptimo para este tamizaje ya pasó. Aún puede registrarlo, pero se recomienda hacerlo lo antes posible.
                </p>
              </div>`
            );
            if (onclickOriginal && onclickOriginal.includes('abrirModalTamizaje')) {
              const timeoutId = setTimeout(() => abrirModalTamizaje(), 300);
              ModalManager.registrarTimeout(timeoutId);
            }
          };
          btn.title = `⚠️ Rango pasado (1-29 días). El niño tiene ${edadDiasActual} días. Se puede registrar con advertencia.`;
        }
      });

      // ========== RANGO PARA VACUNAS RN (0-2 días) ==========
      const botonesVacunaBCG = document.querySelectorAll('button[onclick*="abrirModalVacuna(\'BCG\')"]');
      const botonesVacunaHVB = document.querySelectorAll('button[onclick*="abrirModalVacuna(\'HVB\')"]');
      const estadoBCG = document.getElementById('estado-bcg');
      const estadoHVB = document.getElementById('estado-hvb');
      const fechaBCGEl = document.getElementById('fecha-bcg');
      const fechaHVBEl = document.getElementById('fecha-hvb');
      const tieneBCG = estadoBCG && estadoBCG.textContent.includes('APLICADA');
      const tieneHVB = estadoHVB && estadoHVB.textContent.includes('APLICADA');

      // Validar BCG
      let edadDiasBCG = null;
      let cumpleBCG = false;
      if (tieneBCG && fechaBCGEl && fechaBCGEl.textContent !== '-') {
        const fechaTexto = fechaBCGEl.textContent.trim();
        const fechaMatch = fechaTexto.match(/(\d{2})\/(\d{2})\/(\d{4})/);
        if (fechaMatch) {
          const fechaBCGISO = `${fechaMatch[3]}-${fechaMatch[2]}-${fechaMatch[1]}`;
          edadDiasBCG = calcularEdadDias(fechaNacimientoISO, fechaBCGISO);
          cumpleBCG = edadDiasBCG >= 0 && edadDiasBCG <= 2;
        }
      }

      // Validar HVB
      let edadDiasHVB = null;
      let cumpleHVB = false;
      if (tieneHVB && fechaHVBEl && fechaHVBEl.textContent !== '-') {
        const fechaTexto = fechaHVBEl.textContent.trim();
        const fechaMatch = fechaTexto.match(/(\d{2})\/(\d{2})\/(\d{4})/);
        if (fechaMatch) {
          const fechaHVBISO = `${fechaMatch[3]}-${fechaMatch[2]}-${fechaMatch[1]}`;
          edadDiasHVB = calcularEdadDias(fechaNacimientoISO, fechaHVBISO);
          cumpleHVB = edadDiasHVB >= 0 && edadDiasHVB <= 2;
        }
      }

      // BCG
      botonesVacunaBCG.forEach(btn => {
        // Remover estilos inline y clases de estado previas
        btn.style.background = '';
        btn.style.opacity = '';
        btn.style.cursor = '';
        btn.classList.remove('btn-registrar-cumple', 'btn-registrar-no-cumple', 'btn-registrar-pendiente', 'btn-registrar-rango-pasado');

        // Guardar onclick original
        const onclickOriginal = btn.getAttribute('data-onclick-original') || btn.getAttribute('onclick') || '';
        if (!btn.hasAttribute('data-onclick-original') && onclickOriginal) {
          btn.setAttribute('data-onclick-original', onclickOriginal);
        }

        if (tieneBCG && edadDiasBCG !== null) {
          if (cumpleBCG) {
            // Si cumple y ya pasó el tiempo límite, deshabilitar
            if (edadDiasActual > 2) {
              btn.disabled = false;
              btn.classList.add('btn-registrar-cumple');
              btn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                mostrarModalInfo(
                  'Vacuna BCG - Completada',
                  `<div class="space-y-2">
                    <p><strong>Estado:</strong> <span class="text-green-600 font-semibold">✅ CUMPLE</span></p>
                    <p><strong>Fecha de aplicación:</strong> ${fechaBCGEl ? fechaBCGEl.textContent : '-'}</p>
                    <p><strong>Edad al momento de aplicación:</strong> ${edadDiasBCG} días</p>
                    <p><strong>Rango válido:</strong> 0 - 2 días</p>
                    <p class="text-xs text-slate-500 mt-3 pt-3 border-t border-slate-200">
                      <strong>⚠️ Nota:</strong> Esta vacuna ya está registrada y cumple con el rango establecido. El tiempo límite ya pasó (${edadDiasActual} días > 2 días), por lo que no se pueden agregar más datos manualmente.
                    </p>
                  </div>`
                );
              };
              btn.title = `✅ Vacuna BCG aplicada y CUMPLE. Tiempo límite pasado (30 días). No se pueden agregar más datos.`;
            } else {
              btn.disabled = false;
              btn.classList.add('btn-registrar-cumple');
              btn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                mostrarModalInfo(
                  'Vacuna BCG - Registrada',
                  `<div class="space-y-2">
                    <p><strong>Estado:</strong> <span class="text-green-600 font-semibold">✅ CUMPLE</span></p>
                    <p><strong>Fecha de aplicación:</strong> ${fechaBCGEl ? fechaBCGEl.textContent : '-'}</p>
                    <p><strong>Edad al momento de aplicación:</strong> ${edadDiasBCG} días</p>
                    <p><strong>Rango válido:</strong> 0 - 2 días</p>
                    <p class="text-xs text-slate-500 mt-3 pt-3 border-t border-slate-200">
                      Puede editar esta vacuna.
                    </p>
                  </div>`
                );
                if (onclickOriginal && onclickOriginal.includes('abrirModalVacuna')) {
                  const timeoutId = setTimeout(() => abrirModalVacuna('BCG'), 300);
                  ModalManager.registrarTimeout(timeoutId);
                }
              };
              btn.title = `✅ Vacuna BCG aplicada y CUMPLE con el rango (0-2 días). Fue aplicada a los ${edadDiasBCG} días.`;
            }
          } else {
            btn.disabled = false;
            btn.classList.add('btn-registrar-no-cumple');
            btn.onclick = function(e) {
              e.preventDefault();
              e.stopPropagation();
              mostrarModalInfo(
                'Vacuna BCG - No Cumple',
                `<div class="space-y-2">
                  <p><strong>Estado:</strong> <span class="text-red-600 font-semibold">❌ NO CUMPLE</span></p>
                  <p><strong>Fecha de aplicación:</strong> ${fechaBCGEl ? fechaBCGEl.textContent : '-'}</p>
                  <p><strong>Edad al momento de aplicación:</strong> ${edadDiasBCG} días</p>
                  <p><strong>Rango válido:</strong> 0 - 2 días</p>
                  <p class="text-xs text-red-600 mt-3 pt-3 border-t border-red-200">
                    Esta vacuna fue aplicada fuera del rango establecido. Puede editar los datos.
                  </p>
                </div>`
              );
              if (onclickOriginal && onclickOriginal.includes('abrirModalVacuna')) {
                const timeoutId = setTimeout(() => abrirModalVacuna('BCG'), 300);
                ModalManager.registrarTimeout(timeoutId);
              }
            };
            btn.title = `❌ Vacuna BCG aplicada pero NO CUMPLE con el rango (0-2 días). Fue aplicada a los ${edadDiasBCG} días.`;
          }
        } else if (edadDiasActual <= 2) {
          btn.disabled = false;
          btn.classList.add('btn-registrar-pendiente');
          btn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            const contenido = generarContenidoVacuna('BCG', edadDiasActual, tieneBCG, fechaBCGEl ? fechaBCGEl.textContent : null);
            if (onclickOriginal && onclickOriginal.includes('abrirModalVacuna')) {
              mostrarModalInfoVacuna(
                contenido,
                'BCG',
                () => {
                  abrirModalVacuna('BCG');
                }
              );
            } else {
              mostrarModalInfoVacuna(contenido, 'BCG');
            }
          };
          btn.title = `✅ Dentro del rango (0-2 días). El niño tiene ${edadDiasActual} días.`;
        } else if (!tieneBCG) {
          btn.disabled = false;
          btn.classList.add('btn-registrar-rango-pasado');
          btn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            mostrarModalInfo(
              'Vacuna BCG - Rango Pasado',
              `<div class="space-y-2">
                <p><strong>Estado:</strong> <span class="text-orange-600 font-semibold">⚠️ RANGO PASADO</span></p>
                <p><strong>Edad actual del niño:</strong> ${edadDiasActual} días</p>
                <p><strong>Rango válido:</strong> 0 - 2 días</p>
                <p class="text-xs text-orange-600 mt-3 pt-3 border-t border-orange-200">
                  El rango óptimo para esta vacuna ya pasó. Aún puede registrarla, pero se recomienda hacerlo lo antes posible.
                </p>
              </div>`
            );
            if (onclickOriginal && onclickOriginal.includes('abrirModalVacuna')) {
              const timeoutId = setTimeout(() => abrirModalVacuna('BCG'), 300);
              ModalManager.registrarTimeout(timeoutId);
            }
          };
          btn.title = `⚠️ Rango pasado (0-2 días). El niño tiene ${edadDiasActual} días. Se puede registrar con advertencia.`;
        }
      });

      // HVB
      botonesVacunaHVB.forEach(btn => {
        // Remover estilos inline y clases de estado previas
        btn.style.background = '';
        btn.style.opacity = '';
        btn.style.cursor = '';
        btn.classList.remove('btn-registrar-cumple', 'btn-registrar-no-cumple', 'btn-registrar-pendiente', 'btn-registrar-rango-pasado');

        // Guardar onclick original
        const onclickOriginal = btn.getAttribute('data-onclick-original') || btn.getAttribute('onclick') || '';
        if (!btn.hasAttribute('data-onclick-original') && onclickOriginal) {
          btn.setAttribute('data-onclick-original', onclickOriginal);
        }

        if (tieneHVB && edadDiasHVB !== null) {
          if (cumpleHVB) {
            // Si cumple y ya pasó el tiempo límite, deshabilitar
            if (edadDiasActual > 2) {
              btn.disabled = false;
              btn.classList.add('btn-registrar-cumple');
              btn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                mostrarModalInfo(
                  'Vacuna HVB - Completada',
                  `<div class="space-y-2">
                    <p><strong>Estado:</strong> <span class="text-green-600 font-semibold">✅ CUMPLE</span></p>
                    <p><strong>Fecha de aplicación:</strong> ${fechaHVBEl ? fechaHVBEl.textContent : '-'}</p>
                    <p><strong>Edad al momento de aplicación:</strong> ${edadDiasHVB} días</p>
                    <p><strong>Rango válido:</strong> 0 - 2 días</p>
                    <p class="text-xs text-slate-500 mt-3 pt-3 border-t border-slate-200">
                      <strong>⚠️ Nota:</strong> Esta vacuna ya está registrada y cumple con el rango establecido. El tiempo límite ya pasó (${edadDiasActual} días > 2 días), por lo que no se pueden agregar más datos manualmente.
                    </p>
                  </div>`
                );
              };
              btn.title = `✅ Vacuna HVB aplicada y CUMPLE. Tiempo límite pasado (30 días). No se pueden agregar más datos.`;
            } else {
              btn.disabled = false;
              btn.classList.add('btn-registrar-cumple');
              btn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                mostrarModalInfo(
                  'Vacuna HVB - Registrada',
                  `<div class="space-y-2">
                    <p><strong>Estado:</strong> <span class="text-green-600 font-semibold">✅ CUMPLE</span></p>
                    <p><strong>Fecha de aplicación:</strong> ${fechaHVBEl ? fechaHVBEl.textContent : '-'}</p>
                    <p><strong>Edad al momento de aplicación:</strong> ${edadDiasHVB} días</p>
                    <p><strong>Rango válido:</strong> 0 - 2 días</p>
                    <p class="text-xs text-slate-500 mt-3 pt-3 border-t border-slate-200">
                      Puede editar esta vacuna.
                    </p>
                  </div>`
                );
                if (onclickOriginal && onclickOriginal.includes('abrirModalVacuna')) {
                  const timeoutId = setTimeout(() => abrirModalVacuna('HVB'), 300);
                  ModalManager.registrarTimeout(timeoutId);
                }
              };
              btn.title = `✅ Vacuna HVB aplicada y CUMPLE con el rango (0-2 días). Fue aplicada a los ${edadDiasHVB} días.`;
            }
          } else {
            btn.disabled = false;
            btn.classList.add('btn-registrar-no-cumple');
            btn.onclick = function(e) {
              e.preventDefault();
              e.stopPropagation();
              mostrarModalInfo(
                'Vacuna HVB - No Cumple',
                `<div class="space-y-2">
                  <p><strong>Estado:</strong> <span class="text-red-600 font-semibold">❌ NO CUMPLE</span></p>
                  <p><strong>Fecha de aplicación:</strong> ${fechaHVBEl ? fechaHVBEl.textContent : '-'}</p>
                  <p><strong>Edad al momento de aplicación:</strong> ${edadDiasHVB} días</p>
                  <p><strong>Rango válido:</strong> 0 - 2 días</p>
                  <p class="text-xs text-red-600 mt-3 pt-3 border-t border-red-200">
                    Esta vacuna fue aplicada fuera del rango establecido. Puede editar los datos.
                  </p>
                </div>`
              );
              if (onclickOriginal && onclickOriginal.includes('abrirModalVacuna')) {
                const timeoutId = setTimeout(() => abrirModalVacuna('HVB'), 300);
                ModalManager.registrarTimeout(timeoutId);
              }
            };
            btn.title = `❌ Vacuna HVB aplicada pero NO CUMPLE con el rango (0-2 días). Fue aplicada a los ${edadDiasHVB} días.`;
          }
        } else if (edadDiasActual <= 2) {
          btn.disabled = false;
          btn.classList.add('btn-registrar-pendiente');
          btn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            const contenido = generarContenidoVacuna('HVB', edadDiasActual, tieneHVB, fechaHVBEl ? fechaHVBEl.textContent : null);
            if (onclickOriginal && onclickOriginal.includes('abrirModalVacuna')) {
              mostrarModalInfoVacuna(
                contenido,
                'HVB',
                () => {
                  abrirModalVacuna('HVB');
                }
              );
            } else {
              mostrarModalInfoVacuna(contenido, 'HVB');
            }
          };
          btn.title = `✅ Dentro del rango (0-2 días). El niño tiene ${edadDiasActual} días.`;
        } else if (!tieneHVB) {
          btn.disabled = false;
          btn.classList.add('btn-registrar-rango-pasado');
          btn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            mostrarModalInfo(
              'Vacuna HVB - Rango Pasado',
              `<div class="space-y-2">
                <p><strong>Estado:</strong> <span class="text-orange-600 font-semibold">⚠️ RANGO PASADO</span></p>
                <p><strong>Edad actual del niño:</strong> ${edadDiasActual} días</p>
                <p><strong>Rango válido:</strong> 0 - 2 días</p>
                <p class="text-xs text-orange-600 mt-3 pt-3 border-t border-orange-200">
                  El rango óptimo para esta vacuna ya pasó. Aún puede registrarla, pero se recomienda hacerlo lo antes posible.
                </p>
              </div>`
            );
            if (onclickOriginal && onclickOriginal.includes('abrirModalVacuna')) {
              const timeoutId = setTimeout(() => abrirModalVacuna('HVB'), 300);
              ModalManager.registrarTimeout(timeoutId);
            }
          };
          btn.title = `⚠️ Rango pasado (0-2 días). El niño tiene ${edadDiasActual} días. Se puede registrar con advertencia.`;
        }
      });

      // ========== CNV (sin rango específico, pero solo para recién nacidos) ==========
      const botonesCNV = document.querySelectorAll('button[onclick*="abrirModalCNV()"]');
      // Buscar el elemento que muestra el estado del CNV (el span con "PENDIENTE" o la clasificación)
      const infoCardCNV = document.querySelector('#tab-cnv .info-card');
      const clasificacionCNV = infoCardCNV ? infoCardCNV.querySelector('.estado-badge') : null;
      const pesoCNV = infoCardCNV ? infoCardCNV.querySelector('.info-row:nth-child(1) span') : null;
      const tieneCNV = clasificacionCNV && pesoCNV && !clasificacionCNV.textContent.includes('PENDIENTE') && pesoCNV.textContent !== 'No registrado';

      botonesCNV.forEach(btn => {
        // Remover estilos inline y clases de estado previas
        btn.style.background = '';
        btn.style.opacity = '';
        btn.style.cursor = '';
        btn.classList.remove('btn-registrar-cumple', 'btn-registrar-no-cumple', 'btn-registrar-pendiente', 'btn-registrar-rango-pasado');

        // Guardar onclick original
        const onclickOriginal = btn.getAttribute('data-onclick-original') || btn.getAttribute('onclick') || '';
        if (!btn.hasAttribute('data-onclick-original') && onclickOriginal) {
          btn.setAttribute('data-onclick-original', onclickOriginal);
        }

        if (tieneCNV && edadDiasActual > 28) {
          // CNV ya registrado y pasó el tiempo límite, deshabilitar
          btn.disabled = false;
          btn.classList.add('btn-registrar-cumple');
          btn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            mostrarModalInfo(
              'CNV - Completado',
              `<div class="space-y-2">
                <p><strong>Estado:</strong> <span class="text-green-600 font-semibold">✅ REGISTRADO</span></p>
                <p><strong>Peso al nacer:</strong> ${pesoCNV ? pesoCNV.textContent : '-'}</p>
                <p><strong>Clasificación:</strong> ${clasificacionCNV ? clasificacionCNV.textContent : '-'}</p>
                <p><strong>Edad actual del niño:</strong> ${edadDiasActual} días</p>
                <p><strong>Rango recomendado:</strong> 0 - 28 días (recién nacido)</p>
                <p class="text-xs text-slate-500 mt-3 pt-3 border-t border-slate-200">
                  <strong>⚠️ Nota:</strong> El CNV ya está registrado. El tiempo límite para este registro ya pasó (${edadDiasActual} días > 28 días), por lo que no se pueden agregar más datos manualmente.
                </p>
              </div>`
            );
          };
          btn.title = `CNV registrado. Tiempo límite pasado (28 días). No se pueden agregar más datos.`;
        } else if (tieneCNV && edadDiasActual <= 28) {
          // CNV registrado pero aún en rango, permitir editar
          btn.disabled = false;
          btn.classList.add('btn-registrar-cumple');
          btn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            mostrarModalInfo(
              'CNV - Registrado',
              `<div class="space-y-2">
                <p><strong>Estado:</strong> <span class="text-green-600 font-semibold">✅ REGISTRADO</span></p>
                <p><strong>Peso al nacer:</strong> ${pesoCNV ? pesoCNV.textContent : '-'}</p>
                <p><strong>Clasificación:</strong> ${clasificacionCNV ? clasificacionCNV.textContent : '-'}</p>
                <p><strong>Edad actual del niño:</strong> ${edadDiasActual} días</p>
                <p><strong>Rango recomendado:</strong> 0 - 28 días (recién nacido)</p>
                <p class="text-xs text-slate-500 mt-3 pt-3 border-t border-slate-200">
                  Puede editar el CNV.
                </p>
              </div>`
            );
            if (onclickOriginal && onclickOriginal.includes('abrirModalCNV')) {
              const timeoutId = setTimeout(() => abrirModalCNV(), 300);
              ModalManager.registrarTimeout(timeoutId);
            }
          };
          btn.title = `✅ CNV registrado. El niño tiene ${edadDiasActual} días.`;
        } else if (edadDiasActual <= 28) {
          btn.disabled = false;
          btn.classList.add('btn-registrar-pendiente');
          btn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            const contenido = generarContenidoCNV(edadDiasActual, tieneCNV);
            if (onclickOriginal && onclickOriginal.includes('abrirModalCNV')) {
              mostrarModalInfoCNV(
                contenido,
                () => {
                  abrirModalCNV();
                }
              );
            } else {
              mostrarModalInfoCNV(contenido);
            }
          };
          btn.title = `✅ Apto para registro CNV (recién nacido, 0-28 días). El niño tiene ${edadDiasActual} días.`;
        } else {
          btn.disabled = false;
          btn.classList.add('btn-registrar-rango-pasado');
          btn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            mostrarModalInfo(
              'CNV - Rango Pasado',
              `<div class="space-y-2">
                <p><strong>Estado:</strong> <span class="text-orange-600 font-semibold">⚠️ RANGO PASADO</span></p>
                <p><strong>Edad actual del niño:</strong> ${edadDiasActual} días</p>
                <p><strong>Rango recomendado:</strong> 0 - 28 días (recién nacido)</p>
                <p class="text-xs text-orange-600 mt-3 pt-3 border-t border-orange-200">
                  El rango recomendado para el CNV ya pasó. Aún puede registrarlo, pero se recomienda hacerlo lo antes posible.
                </p>
              </div>`
            );
            if (onclickOriginal && onclickOriginal.includes('abrirModalCNV')) {
              const timeoutId = setTimeout(() => abrirModalCNV(), 300);
              ModalManager.registrarTimeout(timeoutId);
            }
          };
          btn.title = `El niño tiene ${edadDiasActual} días. CNV es para recién nacidos (0-28 días).`;
        }
      });

      // ========== VISITAS DOMICILIARIAS (con validación de rango) ==========
      const rangosVisitas = {
        1: { min: 28, max: 30 },
        2: { min: 60, max: 150 },
        3: { min: 180, max: 240 },
        4: { min: 270, max: 330 }
      };

      console.log('🔍 Validando visitas domiciliarias. Edad actual:', edadDiasActual, 'días');
      console.log('📅 Fecha de nacimiento ISO:', fechaNacimientoISO);
      
      // Primero, buscar todos los items de visita directamente
      const visitaItems = document.querySelectorAll('#tab-visitas .visita-item');
      console.log('📋 Items de visita encontrados:', visitaItems.length);
      
      if (visitaItems.length === 0) {
        console.warn('⚠️ No se encontraron items de visita en #tab-visitas');
      }
      
      // Validar cada item de visita
      visitaItems.forEach(item => {
        const controlNumero = parseInt(item.getAttribute('data-control-visita')) || parseInt(item.querySelector('.visita-control-numero')?.textContent.trim());
        const estadoBadge = item.querySelector('.estado-badge');
        const button = item.querySelector('button[onclick*="abrirModalVisita"]');
        
        if (!controlNumero || !estadoBadge) {
          console.warn('⚠️ Item de visita sin número de control o badge:', item);
          return;
        }
        
        const rango = rangosVisitas[controlNumero];
        
        if (!rango) {
          console.warn('⚠️ No se encontró rango para control de visita:', controlNumero);
          return;
        }
        
        const rangoMin = rango.min;
        const rangoMax = rango.max;
        
        console.log(`📝 Validando visita Control ${controlNumero}: edad actual=${edadDiasActual}, rango=${rangoMin}-${rangoMax}, badge="${estadoBadge.textContent}"`);
        
        // Verificar si tiene visita registrada (fecha en formato DD/MM/YYYY)
        const textoBadge = estadoBadge.textContent.trim();
        const textoBadgeUpper = textoBadge.toUpperCase();
        const tieneFecha = /^\d{2}\/\d{2}\/\d{4}$/.test(textoBadge);
        const esNoCumple = textoBadgeUpper.includes('NO CUMPLE');
        const esCumple = textoBadgeUpper.includes('CUMPLE');
        const esSeguimiento = textoBadgeUpper.includes('SEGUIMIENTO') || textoBadgeUpper.includes('PENDIENTE') || textoBadge === '-' || textoBadge === '';
        
        // Tiene visita si:
        // 1. Tiene fecha válida en formato DD/MM/YYYY
        // 2. Dice CUMPLE y tiene data-fecha (visita registrada que cumple)
        // 3. Dice NO CUMPLE y tiene data-fecha (visita registrada pero fuera de rango)
        const tieneVisitaRegistrada = tieneFecha || (esCumple && estadoBadge.getAttribute('data-fecha')) || (esNoCumple && estadoBadge.getAttribute('data-fecha'));
        
        console.log(`  - Badge texto: "${textoBadge}"`);
        console.log(`  - Tiene fecha: ${tieneFecha}`);
        console.log(`  - Es NO CUMPLE: ${esNoCumple}`);
        console.log(`  - Es SEGUIMIENTO/PENDIENTE: ${esSeguimiento}`);
        console.log(`  - Tiene visita registrada: ${tieneVisitaRegistrada}`);
        console.log(`  - Edad actual: ${edadDiasActual} días, Rango max: ${rangoMax} días`);
        
        // PRIORIDAD 1: Si tiene visita registrada y CUMPLE, mantener el estado CUMPLE
        if (esCumple && tieneVisitaRegistrada) {
          // Ya está marcado como CUMPLE y tiene visita registrada - mantenerlo así
          console.log(`  → Badge ya está marcado como CUMPLE con visita registrada, manteniendo estado`);
        }
        // PRIORIDAD 2: Si NO tiene visita registrada y ya pasó el rango, SIEMPRE actualizar badge a NO CUMPLE
        // Esto debe ejecutarse SIEMPRE, incluso si el badge ya dice SEGUIMIENTO o PENDIENTE
        else if (!tieneVisitaRegistrada && edadDiasActual > rangoMax) {
          console.log(`  → Actualizando badge a NO CUMPLE (no tiene visita registrada y edad ${edadDiasActual} > ${rangoMax})`);
          estadoBadge.className = 'estado-badge no-cumple';
          estadoBadge.textContent = 'NO CUMPLE';
          estadoBadge.removeAttribute('data-fecha');
          console.log(`✅ Badge actualizado a NO CUMPLE para visita ${periodo} (edad: ${edadDiasActual} días > rango max: ${rangoMax} días, badge anterior: "${textoBadge}")`);
        } else if (esNoCumple && tieneVisitaRegistrada) {
          // Ya está marcado como NO CUMPLE y tiene visita registrada - mantenerlo así
          console.log(`  → Badge ya está marcado como NO CUMPLE con visita registrada, manteniendo estado`);
        } else if (!tieneVisitaRegistrada && edadDiasActual <= rangoMax) {
          // No tiene visita pero aún está dentro del rango - mantener SEGUIMIENTO/PENDIENTE
          console.log(`  → No tiene visita pero aún está dentro del rango, manteniendo estado actual`);
        } else {
          console.log(`  → No se actualiza badge (tieneVisitaRegistrada: ${tieneVisitaRegistrada}, edad: ${edadDiasActual}, rangoMax: ${rangoMax})`);
        }
        
        // Configurar el botón
        if (button) {
          // Remover estilos inline y clases previas
          button.style.background = '';
          button.style.opacity = '';
          button.style.cursor = '';
          button.classList.remove('btn-registrar-cumple', 'btn-registrar-no-cumple', 'btn-registrar-pendiente', 'btn-registrar-rango-pasado');

          // Guardar onclick original
          const onclickOriginal = button.getAttribute('data-onclick-original') || button.getAttribute('onclick') || '';
          if (!button.hasAttribute('data-onclick-original') && onclickOriginal) {
            button.setAttribute('data-onclick-original', onclickOriginal);
          }
          
          // Si tiene visita, verificar si la fecha cumple con el rango
          let cumpleRangoVisita = false;
          let edadDiasVisita = null;
          let fechaVisitaISO = null;
          
          // Si el badge dice NO CUMPLE, significa que hay visita pero fuera de rango
          if (esNoCumple) {
            cumpleRangoVisita = false;
            // Intentar obtener la fecha desde el atributo data-fecha si existe
            const fechaData = estadoBadge.getAttribute('data-fecha');
            if (fechaData) {
              fechaVisitaISO = fechaData;
              edadDiasVisita = calcularEdadDias(fechaNacimientoISO, fechaVisitaISO);
            }
          } else if (tieneFecha && estadoBadge) {
            // Intentar obtener la fecha de la visita desde el badge
            const fechaTexto = estadoBadge.textContent.trim();
            const fechaMatch = fechaTexto.match(/(\d{2})\/(\d{2})\/(\d{4})/);
            if (fechaMatch) {
              fechaVisitaISO = `${fechaMatch[3]}-${fechaMatch[2]}-${fechaMatch[1]}`;
              edadDiasVisita = calcularEdadDias(fechaNacimientoISO, fechaVisitaISO);
              cumpleRangoVisita = edadDiasVisita >= rangoMin && edadDiasVisita <= rangoMax;
            }
          }

          // Configurar el botón según el estado
          if (tieneVisita) {
            if (cumpleRangoVisita) {
              // Visita registrada y CUMPLE con el rango
              button.disabled = false;
              button.classList.add('btn-registrar-cumple');
              button.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                mostrarModalInfo(
                  `Visita ${periodo} - Registrada`,
                  `<div class="space-y-2">
                    <p><strong>Estado:</strong> <span class="text-green-600 font-semibold">✅ CUMPLE</span></p>
                    <p><strong>Período:</strong> ${periodo}</p>
                    <p><strong>Fecha de visita:</strong> ${estadoBadge ? estadoBadge.textContent : '-'}</p>
                    <p><strong>Edad al momento de la visita:</strong> ${edadDiasVisita !== null ? edadDiasVisita + ' días' : '-'}</p>
                    <p><strong>Rango válido:</strong> ${rangoMin} - ${rangoMax} días</p>
                  </div>`
                );
              };
              button.title = `✅ Visita ${periodo} registrada y CUMPLE con el rango.`;
            } else {
              // Visita registrada pero NO CUMPLE con el rango
              button.disabled = false;
              button.classList.add('btn-registrar-no-cumple');
              
              // Asegurar que el badge muestre NO CUMPLE
              if (estadoBadge && estadoBadge.textContent !== 'NO CUMPLE') {
                estadoBadge.className = 'estado-badge no-cumple';
                estadoBadge.textContent = 'NO CUMPLE';
              }
              
              button.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                mostrarModalInfo(
                  `Visita ${periodo} - No Cumple`,
                  `<div class="space-y-2">
                    <p><strong>Estado:</strong> <span class="text-red-600 font-semibold">❌ NO CUMPLE</span></p>
                    <p><strong>Período:</strong> ${periodo}</p>
                    <p><strong>Fecha de visita:</strong> ${estadoBadge && estadoBadge.textContent !== 'NO CUMPLE' ? estadoBadge.textContent : '-'}</p>
                    <p><strong>Edad al momento de la visita:</strong> ${edadDiasVisita !== null ? edadDiasVisita + ' días' : '-'}</p>
                    <p><strong>Rango válido:</strong> ${rangoMin} - ${rangoMax} días</p>
                    <p class="text-xs text-red-600 mt-3">La visita fue realizada fuera del rango establecido.</p>
                  </div>`
                );
                if (onclickOriginal && onclickOriginal.includes('abrirModalVisita')) {
                  const match = onclickOriginal.match(/abrirModalVisita\(['"]?([^'"]+)['"]?\)/);
                  if (match) {
                    setTimeout(() => abrirModalVisita(match[1]), 300);
                  }
                }
              };
              button.title = `❌ Visita ${periodo} registrada pero NO CUMPLE con el rango (${rangoMin}-${rangoMax} días).`;
            }
          } else if (edadDiasActual < rangoMin) {
            // Aún no llega al rango - Pendiente
            button.disabled = true;
            button.classList.add('btn-registrar-pendiente');
            button.style.opacity = '0.5';
            button.onclick = function(e) {
              e.preventDefault();
              e.stopPropagation();
              mostrarModalInfo(
                `Visita ${periodo} - Pendiente`,
                `<div class="space-y-2">
                  <p><strong>Estado:</strong> <span class="text-slate-500 font-semibold">⏳ PENDIENTE</span></p>
                  <p><strong>Edad actual del niño:</strong> ${edadDiasActual} días</p>
                  <p><strong>Rango válido:</strong> ${rangoMin} - ${rangoMax} días</p>
                  <p class="text-xs text-slate-500 mt-3">Esta visita aún no puede realizarse. El niño debe tener al menos ${rangoMin} días.</p>
                </div>`
              );
            };
            button.title = `Esta visita debe realizarse entre los días ${rangoMin}-${rangoMax}. El niño tiene ${edadDiasActual} días.`;
          } else if (edadDiasActual >= rangoMin && edadDiasActual <= rangoMax) {
            // Dentro del rango - Puede registrar
            button.disabled = false;
            button.classList.add('btn-registrar-pendiente');
            button.onclick = function(e) {
              e.preventDefault();
              e.stopPropagation();
              const contenido = generarContenidoVisita(periodo, edadDiasActual, rangoMin, rangoMax, false, null);
              if (onclickOriginal && onclickOriginal.includes('abrirModalVisita')) {
                const match = onclickOriginal.match(/abrirModalVisita\(['"]?([^'"]+)['"]?\)/);
                if (match) {
                  mostrarModalInfoVisita(contenido, periodo, () => abrirModalVisita(match[1]));
                }
              }
            };
            button.title = `✅ Dentro del rango (${rangoMin}-${rangoMax} días). El niño tiene ${edadDiasActual} días.`;
          } else if (edadDiasActual > rangoMax) {
            // Ya pasó el rango - NO CUMPLE (sin visita)
            button.disabled = false;
            button.classList.add('btn-registrar-no-cumple');
            
            // SIEMPRE actualizar badge de estado a NO CUMPLE si no tiene visita registrada
            if (estadoBadge && !tieneVisita) {
              estadoBadge.className = 'estado-badge no-cumple';
              estadoBadge.textContent = 'NO CUMPLE';
              console.log(`✅ Badge actualizado a NO CUMPLE para visita Control ${controlNumero} (edad: ${edadDiasActual} días, rango max: ${rangoMax} días)`);
            }
            
            button.onclick = function(e) {
              e.preventDefault();
              e.stopPropagation();
              mostrarModalInfo(
                `Visita ${periodo} - No Cumple`,
                `<div class="space-y-2">
                  <p><strong>Estado:</strong> <span class="text-red-600 font-semibold">❌ NO CUMPLE</span></p>
                  <p><strong>Edad actual del niño:</strong> ${edadDiasActual} días</p>
                  <p><strong>Rango válido:</strong> ${rangoMin} - ${rangoMax} días</p>
                  <p class="text-xs text-red-600 mt-3">La visita no se realizó dentro del rango establecido. El niño ya tiene ${edadDiasActual} días y el rango máximo era de ${rangoMax} días.</p>
                </div>`
              );
              if (onclickOriginal && onclickOriginal.includes('abrirModalVisita')) {
                const match = onclickOriginal.match(/abrirModalVisita\(['"]?([^'"]+)['"]?\)/);
                if (match) {
                  setTimeout(() => abrirModalVisita(match[1]), 300);
                }
              }
            };
            button.title = `❌ NO CUMPLE - Rango pasado (${rangoMin}-${rangoMax} días). El niño tiene ${edadDiasActual} días.`;
          }
        }
      });

      console.log('✅ Validación de rangos completada');
      validacionEnProceso = false;
    }

    // ========== FUNCIONES PARA MODAL INFORMATIVO ==========
    // Funciones helper para generar contenido específico de cada control

    // Generar contenido informativo para Tamizaje Neonatal
    function generarContenidoTamizaje(edadDiasActual, tieneTamizaje, fechaTamizaje, edadDiasTamizaje, cumpleTamizaje) {
      const rangoMin = 1;
      const rangoMax = 29;

      let contenido = `<div class="space-y-3">`;

      // Información del estado
      if (tieneTamizaje && edadDiasTamizaje !== null) {
        contenido += `
          <div class="bg-${cumpleTamizaje ? 'green' : 'red'}-50 border border-${cumpleTamizaje ? 'green' : 'red'}-200 rounded-lg p-3">
            <div class="flex items-center gap-2 mb-2">
              <span class="text-${cumpleTamizaje ? 'green' : 'red'}-600 font-bold text-base">${cumpleTamizaje ? '✅ CUMPLE' : '❌ NO CUMPLE'}</span>
            </div>
            <div class="space-y-1.5 text-sm">
              <div class="flex justify-between">
                <span class="text-slate-600 font-medium">Fecha del tamizaje:</span>
                <span class="text-slate-800 font-semibold">${fechaTamizaje || '-'}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-600 font-medium">Edad al momento:</span>
                <span class="text-slate-800 font-semibold">${edadDiasTamizaje} días</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-600 font-medium">Rango válido:</span>
                <span class="text-slate-800 font-semibold">${rangoMin} - ${rangoMax} días</span>
              </div>
            </div>
          </div>`;
      } else {
        contenido += `
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
            <div class="flex items-center gap-2 mb-2">
              <span class="text-blue-600 font-bold text-base">✅ DISPONIBLE</span>
            </div>
            <div class="space-y-1.5 text-sm">
              <div class="flex justify-between">
                <span class="text-slate-600 font-medium">Edad actual del niño:</span>
                <span class="text-slate-800 font-semibold">${edadDiasActual} días</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-600 font-medium">Rango válido:</span>
                <span class="text-slate-800 font-semibold">${rangoMin} - ${rangoMax} días</span>
              </div>
            </div>
          </div>`;
      }

      // Información sobre qué se registra
      contenido += `
        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-3">
          <h4 class="font-semibold text-indigo-900 mb-2 text-sm">📋 Datos que se registran:</h4>
          <ul class="text-xs text-indigo-800 space-y-1 list-disc list-inside">
            <li><strong>Tamizaje Metabólico:</strong> Fecha, resultado, enfermedades detectadas</li>
            <li><strong>Tamizaje Auditivo (Galen):</strong> Fecha del tamizaje Galen</li>
            <li><strong>Observaciones:</strong> Notas adicionales sobre el tamizaje</li>
          </ul>
          <p class="text-xs text-indigo-700 mt-2 pt-2 border-t border-indigo-200">
            <strong>💡 Importante:</strong> El tamizaje neonatal permite detectar tempranamente enfermedades metabólicas y problemas auditivos.
          </p>
        </div>`;

      contenido += `</div>`;
      return contenido;
    }

    // Generar contenido informativo para CNV
    function generarContenidoCNV(edadDiasActual, tieneCNV) {
      let contenido = `<div class="space-y-3">`;

      if (tieneCNV) {
        contenido += `
          <div class="bg-green-50 border border-green-200 rounded-lg p-3">
            <div class="flex items-center gap-2 mb-2">
              <span class="text-green-600 font-bold text-base">✅ REGISTRADO</span>
            </div>
            <p class="text-sm text-slate-700">El Control Neonatal ya está registrado.</p>
          </div>`;
      } else {
        contenido += `
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
            <div class="flex items-center gap-2 mb-2">
              <span class="text-blue-600 font-bold text-base">📝 DISPONIBLE</span>
            </div>
            <p class="text-sm text-slate-700">Puede registrar el Control Neonatal.</p>
          </div>`;
      }

      contenido += `
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-3">
          <h4 class="font-semibold text-purple-900 mb-2 text-sm">📋 Datos que se registran:</h4>
          <ul class="text-xs text-purple-800 space-y-1 list-disc list-inside">
            <li><strong>Peso al nacer:</strong> En gramos</li>
            <li><strong>Edad gestacional:</strong> En semanas</li>
            <li><strong>Clasificación:</strong> Normal o Bajo peso/Prematuro</li>
            <li><strong>Talla:</strong> En centímetros</li>
            <li><strong>Observaciones:</strong> Notas adicionales</li>
          </ul>
          <p class="text-xs text-purple-700 mt-2 pt-2 border-t border-purple-200">
            <strong>💡 Importante:</strong> Este control se registra al momento del nacimiento y permite clasificar al recién nacido.
          </p>
        </div>`;

      contenido += `</div>`;
      return contenido;
    }

    // Generar contenido informativo para Visitas Domiciliarias
    function generarContenidoVisita(periodo, edadDiasActual, rangoMin, rangoMax, tieneVisita, fechaVisita) {
      let contenido = `<div class="space-y-3">`;

      if (tieneVisita && fechaVisita) {
        contenido += `
          <div class="bg-green-50 border border-green-200 rounded-lg p-3">
            <div class="flex items-center gap-2 mb-2">
              <span class="text-green-600 font-bold text-base">✅ REGISTRADA</span>
            </div>
            <div class="space-y-1.5 text-sm">
              <div class="flex justify-between">
                <span class="text-slate-600 font-medium">Período:</span>
                <span class="text-slate-800 font-semibold">${periodo}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-600 font-medium">Fecha de visita:</span>
                <span class="text-slate-800 font-semibold">${fechaVisita}</span>
              </div>
            </div>
          </div>`;
      } else {
        contenido += `
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
            <div class="flex items-center gap-2 mb-2">
              <span class="text-blue-600 font-bold text-base">✅ DISPONIBLE</span>
            </div>
            <div class="space-y-1.5 text-sm">
              <div class="flex justify-between">
                <span class="text-slate-600 font-medium">Período:</span>
                <span class="text-slate-800 font-semibold">${periodo}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-600 font-medium">Edad actual:</span>
                <span class="text-slate-800 font-semibold">${edadDiasActual} días</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-600 font-medium">Rango válido:</span>
                <span class="text-slate-800 font-semibold">${rangoMin} - ${rangoMax} días</span>
              </div>
            </div>
          </div>`;
      }

      contenido += `
        <div class="bg-teal-50 border border-teal-200 rounded-lg p-3">
          <h4 class="font-semibold text-teal-900 mb-2 text-sm">📋 Datos que se registran:</h4>
          <ul class="text-xs text-teal-800 space-y-1 list-disc list-inside">
            <li><strong>Fecha de visita:</strong> Fecha en que se realizó la visita</li>
            <li><strong>Tipo de visita:</strong> Tipo de visita domiciliaria</li>
            <li><strong>Actividades realizadas:</strong> Lista de actividades ejecutadas</li>
            <li><strong>Observaciones:</strong> Notas sobre la visita</li>
          </ul>
          <p class="text-xs text-teal-700 mt-2 pt-2 border-t border-teal-200">
            <strong>💡 Importante:</strong> Las visitas domiciliarias permiten monitorear el desarrollo del niño en su entorno familiar.
          </p>
        </div>`;

      contenido += `</div>`;
      return contenido;
    }

    // Generar contenido informativo para Vacunas RN
    function generarContenidoVacuna(nombreVacuna, edadDiasActual, tieneVacuna, fechaAplicacion) {
      let contenido = `<div class="space-y-3">`;

      if (tieneVacuna && fechaAplicacion) {
        contenido += `
          <div class="bg-green-50 border border-green-200 rounded-lg p-3">
            <div class="flex items-center gap-2 mb-2">
              <span class="text-green-600 font-bold text-base">✅ APLICADA</span>
            </div>
            <div class="space-y-1.5 text-sm">
              <div class="flex justify-between">
                <span class="text-slate-600 font-medium">Vacuna:</span>
                <span class="text-slate-800 font-semibold">${nombreVacuna}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-600 font-medium">Fecha de aplicación:</span>
                <span class="text-slate-800 font-semibold">${fechaAplicacion}</span>
              </div>
            </div>
          </div>`;
      } else {
        contenido += `
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
            <div class="flex items-center gap-2 mb-2">
              <span class="text-blue-600 font-bold text-base">💉 DISPONIBLE</span>
            </div>
            <div class="space-y-1.5 text-sm">
              <div class="flex justify-between">
                <span class="text-slate-600 font-medium">Vacuna:</span>
                <span class="text-slate-800 font-semibold">${nombreVacuna}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-600 font-medium">Edad actual:</span>
                <span class="text-slate-800 font-semibold">${edadDiasActual} días</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-600 font-medium">Rango:</span>
                <span class="text-slate-800 font-semibold">Al nacer (0 días)</span>
              </div>
            </div>
          </div>`;
      }

      contenido += `
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
          <h4 class="font-semibold text-amber-900 mb-2 text-sm">📋 Datos que se registran:</h4>
          <ul class="text-xs text-amber-800 space-y-1 list-disc list-inside">
            <li><strong>Fecha de aplicación:</strong> Fecha en que se aplicó la vacuna</li>
            <li><strong>Lote:</strong> Número de lote de la vacuna</li>
            <li><strong>Dosis:</strong> Dosis aplicada</li>
            <li><strong>Lugar:</strong> Lugar donde se aplicó</li>
            <li><strong>Observaciones:</strong> Notas adicionales</li>
          </ul>
          <p class="text-xs text-amber-700 mt-2 pt-2 border-t border-amber-200">
            <strong>💡 Importante:</strong> ${nombreVacuna === 'BCG' ? 'La BCG protege contra la tuberculosis.' : 'La HVB protege contra la hepatitis B.'} Se aplica al nacer o el día siguiente.
          </p>
        </div>`;

      contenido += `</div>`;
      return contenido;
    }

    // Generar contenido informativo para CRED Mensual
    function generarContenidoCredMensual(mes, edadDiasActual, rangoMin, rangoMax, tieneControl, fechaControl) {
      let contenido = `<div class="space-y-3">`;

      if (tieneControl && fechaControl) {
        contenido += `
          <div class="bg-green-50 border border-green-200 rounded-lg p-3">
            <div class="flex items-center gap-2 mb-2">
              <span class="text-green-600 font-bold text-base">✅ REGISTRADO</span>
            </div>
            <div class="space-y-1.5 text-sm">
              <div class="flex justify-between">
                <span class="text-slate-600 font-medium">Mes:</span>
                <span class="text-slate-800 font-semibold">Mes ${mes}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-600 font-medium">Fecha del control:</span>
                <span class="text-slate-800 font-semibold">${fechaControl}</span>
              </div>
            </div>
          </div>`;
      } else {
        contenido += `
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
            <div class="flex items-center gap-2 mb-2">
              <span class="text-blue-600 font-bold text-base">✅ DISPONIBLE</span>
            </div>
            <div class="space-y-1.5 text-sm">
              <div class="flex justify-between">
                <span class="text-slate-600 font-medium">Mes:</span>
                <span class="text-slate-800 font-semibold">Mes ${mes}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-600 font-medium">Edad actual:</span>
                <span class="text-slate-800 font-semibold">${edadDiasActual} días</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-600 font-medium">Rango válido:</span>
                <span class="text-slate-800 font-semibold">${rangoMin} - ${rangoMax} días</span>
              </div>
            </div>
          </div>`;
      }

      contenido += `
        <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-3">
          <h4 class="font-semibold text-cyan-900 mb-2 text-sm">📋 Datos que se registran:</h4>
          <ul class="text-xs text-cyan-800 space-y-1 list-disc list-inside">
            <li><strong>Fecha del control:</strong> Fecha en que se realizó el control</li>
            <li><strong>Número de control:</strong> Mes del control (1-11)</li>
            <li><strong>Estado:</strong> Se calcula automáticamente según el rango de edad</li>
          </ul>
          <p class="text-xs text-cyan-700 mt-2 pt-2 border-t border-cyan-200">
            <strong>💡 Importante:</strong> Los controles mensuales CRED permiten monitorear el crecimiento y desarrollo del niño durante el primer año de vida.
          </p>
        </div>`;

      contenido += `</div>`;
      return contenido;
    }

    // Función auxiliar para formatear contenido del modal
    function formatearContenidoModal(datos) {
      const { estado, estadoTexto, fecha, edadControl, edadActual, rango, nota, tipoNota = 'info' } = datos;

      let estadoColor = 'blue';
      let estadoBg = 'bg-blue-50';
      let estadoBorder = 'border-blue-200';
      let estadoText = 'text-blue-600';

      if (estado === 'cumple') {
        estadoColor = 'green';
        estadoBg = 'bg-green-50';
        estadoBorder = 'border-green-200';
        estadoText = 'text-green-600';
      } else if (estado === 'no-cumple') {
        estadoColor = 'red';
        estadoBg = 'bg-red-50';
        estadoBorder = 'border-red-200';
        estadoText = 'text-red-600';
      } else if (estado === 'pendiente') {
        estadoColor = 'slate';
        estadoBg = 'bg-slate-50';
        estadoBorder = 'border-slate-200';
        estadoText = 'text-slate-600';
      } else if (estado === 'rango-pasado') {
        estadoColor = 'orange';
        estadoBg = 'bg-orange-50';
        estadoBorder = 'border-orange-200';
        estadoText = 'text-orange-600';
      }

      let notaBg = 'bg-blue-50';
      let notaBorder = 'border-blue-200';
      let notaText = 'text-blue-800';

      if (tipoNota === 'warning') {
        notaBg = 'bg-amber-50';
        notaBorder = 'border-amber-200';
        notaText = 'text-amber-800';
      } else if (tipoNota === 'error') {
        notaBg = 'bg-orange-50';
        notaBorder = 'border-orange-200';
        notaText = 'text-orange-800';
      } else if (tipoNota === 'success') {
        notaBg = 'bg-green-50';
        notaBorder = 'border-green-200';
        notaText = 'text-green-800';
      }

      let contenidoHTML = `<div class="space-y-3">`;

      // Sección de estado principal
      contenidoHTML += `<div class="${estadoBg} border ${estadoBorder} rounded-lg p-3">`;
      contenidoHTML += `<div class="flex items-center gap-2 mb-2">`;
      contenidoHTML += `<span class="${estadoText} font-bold text-base">${estadoTexto}</span>`;
      contenidoHTML += `</div>`;
      contenidoHTML += `<div class="space-y-1.5 text-sm">`;

      if (fecha) {
        contenidoHTML += `<div class="flex justify-between">`;
        contenidoHTML += `<span class="text-slate-600 font-medium">Fecha del control:</span>`;
        contenidoHTML += `<span class="text-slate-800 font-semibold">${fecha}</span>`;
        contenidoHTML += `</div>`;
      }

      if (edadControl !== undefined && edadControl !== null) {
        contenidoHTML += `<div class="flex justify-between">`;
        contenidoHTML += `<span class="text-slate-600 font-medium">Edad al momento:</span>`;
        contenidoHTML += `<span class="text-slate-800 font-semibold">${edadControl} días</span>`;
        contenidoHTML += `</div>`;
      }

      if (edadActual !== undefined && edadActual !== null) {
        contenidoHTML += `<div class="flex justify-between">`;
        contenidoHTML += `<span class="text-slate-600 font-medium">Edad actual del niño:</span>`;
        contenidoHTML += `<span class="text-slate-800 font-semibold">${edadActual} días</span>`;
        contenidoHTML += `</div>`;
      }

      if (rango) {
        contenidoHTML += `<div class="flex justify-between">`;
        contenidoHTML += `<span class="text-slate-600 font-medium">Rango válido:</span>`;
        contenidoHTML += `<span class="text-slate-800 font-semibold">${rango.min} - ${rango.max} días</span>`;
        contenidoHTML += `</div>`;
      }

      contenidoHTML += `</div>`;
      contenidoHTML += `</div>`;

      // Sección de nota si existe
      if (nota) {
        contenidoHTML += `<div class="${notaBg} border ${notaBorder} rounded-lg p-3">`;
        contenidoHTML += `<p class="text-xs ${notaText} leading-relaxed">${nota}</p>`;
        contenidoHTML += `</div>`;
      }

      contenidoHTML += `</div>`;

      return contenidoHTML;
    }

    // ========== FUNCIONES PARA MODALES INFORMATIVOS ESPECÍFICOS ==========

    // Función para abrir modal informativo de Control Recién Nacido
    window.mostrarModalInfoRecienNacido = function mostrarModalInfoRecienNacido(contenido, numControl = '', callbackDespues = null) {
      ModalManager.cerrarTodos();
      const modal = document.getElementById('modalInfoRecienNacido');
      const contenidoEl = document.getElementById('modalInfoRecienNacidoContenido');
      const tituloEl = document.getElementById('modalInfoRecienNacidoTitulo');
      const subtituloEl = document.getElementById('modalInfoRecienNacidoSubtitulo');

      if (modal && contenidoEl) {
        contenidoEl.innerHTML = contenido;
        if (tituloEl && numControl) {
          tituloEl.textContent = `Control Recién Nacido ${numControl === '0' ? '- Nacimiento' : numControl}`;
        }
        if (subtituloEl) {
          subtituloEl.textContent = numControl ? `Control ${numControl} del primer mes` : 'Monitoreo del primer mes de vida';
        }

        if (callbackDespues) {
          modal._callbackDespues = callbackDespues;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        ModalManager.activeModal = 'modalInfoRecienNacido';
      }
    };

    window.closeModalInfoRecienNacido = function closeModalInfoRecienNacido(event) {
      if (event && event.target !== event.currentTarget && !event.target.closest('button')) return;
      const modal = document.getElementById('modalInfoRecienNacido');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        ModalManager.activeModal = null;

        if (modal._callbackDespues && typeof modal._callbackDespues === 'function') {
          const callback = modal._callbackDespues;
          modal._callbackDespues = null;
          setTimeout(() => callback(), 200);
        }
      }
    };

    // Función para abrir modal informativo de Tamizaje
    window.mostrarModalInfoTamizaje = function mostrarModalInfoTamizaje(contenido, callbackDespues = null) {
      ModalManager.cerrarTodos();
      const modal = document.getElementById('modalInfoTamizaje');
      const contenidoEl = document.getElementById('modalInfoTamizajeContenido');
      const subtituloEl = document.getElementById('modalInfoTamizajeSubtitulo');

      if (modal && contenidoEl) {
        contenidoEl.innerHTML = contenido;

        if (callbackDespues) {
          modal._callbackDespues = callbackDespues;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        ModalManager.activeModal = 'modalInfoTamizaje';
      }
    };

    window.closeModalInfoTamizaje = function closeModalInfoTamizaje(event) {
      if (event && event.target !== event.currentTarget && !event.target.closest('button')) return;
      const modal = document.getElementById('modalInfoTamizaje');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        ModalManager.activeModal = null;

        if (modal._callbackDespues && typeof modal._callbackDespues === 'function') {
          const callback = modal._callbackDespues;
          modal._callbackDespues = null;
          setTimeout(() => callback(), 200);
        }
      }
    };

    // Función para abrir modal informativo de CNV
    window.mostrarModalInfoCNV = function mostrarModalInfoCNV(contenido, callbackDespues = null) {
      ModalManager.cerrarTodos();
      const modal = document.getElementById('modalInfoCNV');
      const contenidoEl = document.getElementById('modalInfoCNVContenido');

      if (modal && contenidoEl) {
        contenidoEl.innerHTML = contenido;

        if (callbackDespues) {
          modal._callbackDespues = callbackDespues;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        ModalManager.activeModal = 'modalInfoCNV';
      }
    };

    window.closeModalInfoCNV = function closeModalInfoCNV(event) {
      if (event && event.target !== event.currentTarget && !event.target.closest('button')) return;
      const modal = document.getElementById('modalInfoCNV');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        ModalManager.activeModal = null;

        if (modal._callbackDespues && typeof modal._callbackDespues === 'function') {
          const callback = modal._callbackDespues;
          modal._callbackDespues = null;
          setTimeout(() => callback(), 200);
        }
      }
    };

    // Función para abrir modal informativo de Visitas
    window.mostrarModalInfoVisita = function mostrarModalInfoVisita(contenido, periodo = '', callbackDespues = null) {
      ModalManager.cerrarTodos();
      const modal = document.getElementById('modalInfoVisita');
      const contenidoEl = document.getElementById('modalInfoVisitaContenido');
      const subtituloEl = document.getElementById('modalInfoVisitaSubtitulo');

      if (modal && contenidoEl) {
        contenidoEl.innerHTML = contenido;
        if (subtituloEl && periodo) {
          subtituloEl.textContent = `Período: ${periodo}`;
        }

        if (callbackDespues) {
          modal._callbackDespues = callbackDespues;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        ModalManager.activeModal = 'modalInfoVisita';
      }
    };

    window.closeModalInfoVisita = function closeModalInfoVisita(event) {
      if (event && event.target !== event.currentTarget && !event.target.closest('button')) return;
      const modal = document.getElementById('modalInfoVisita');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        ModalManager.activeModal = null;

        if (modal._callbackDespues && typeof modal._callbackDespues === 'function') {
          const callback = modal._callbackDespues;
          modal._callbackDespues = null;
          setTimeout(() => callback(), 200);
        }
      }
    };

    // Función para abrir modal informativo de Vacunas
    window.mostrarModalInfoVacuna = function mostrarModalInfoVacuna(contenido, nombreVacuna = '', callbackDespues = null) {
      ModalManager.cerrarTodos();
      const modal = document.getElementById('modalInfoVacuna');
      const contenidoEl = document.getElementById('modalInfoVacunaContenido');
      const tituloEl = document.getElementById('modalInfoVacunaTitulo');
      const subtituloEl = document.getElementById('modalInfoVacunaSubtitulo');

      if (modal && contenidoEl) {
        contenidoEl.innerHTML = contenido;
        if (tituloEl && nombreVacuna) {
          tituloEl.textContent = `Vacuna ${nombreVacuna}`;
        }
        if (subtituloEl) {
          subtituloEl.textContent = nombreVacuna === 'BCG' ? 'Protección contra tuberculosis' : 'Protección contra hepatitis B';
        }

        if (callbackDespues) {
          modal._callbackDespues = callbackDespues;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        ModalManager.activeModal = 'modalInfoVacuna';
      }
    };

    window.closeModalInfoVacuna = function closeModalInfoVacuna(event) {
      if (event && event.target !== event.currentTarget && !event.target.closest('button')) return;
      const modal = document.getElementById('modalInfoVacuna');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        ModalManager.activeModal = null;

        if (modal._callbackDespues && typeof modal._callbackDespues === 'function') {
          const callback = modal._callbackDespues;
          modal._callbackDespues = null;
          setTimeout(() => callback(), 200);
        }
      }
    };

    // Función para abrir modal informativo de CRED Mensual
    window.mostrarModalInfoCredMensual = function mostrarModalInfoCredMensual(contenido, mes = '', callbackDespues = null) {
      ModalManager.cerrarTodos();
      const modal = document.getElementById('modalInfoCredMensual');
      const contenidoEl = document.getElementById('modalInfoCredMensualContenido');
      const tituloEl = document.getElementById('modalInfoCredMensualTitulo');
      const subtituloEl = document.getElementById('modalInfoCredMensualSubtitulo');

      if (modal && contenidoEl) {
        contenidoEl.innerHTML = contenido;
        if (tituloEl && mes) {
          tituloEl.textContent = `Control CRED Mensual - Mes ${mes}`;
        }
        if (subtituloEl) {
          subtituloEl.textContent = mes ? `Mes ${mes} de vida` : 'Monitoreo de crecimiento y desarrollo';
        }

        if (callbackDespues) {
          modal._callbackDespues = callbackDespues;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        ModalManager.activeModal = 'modalInfoCredMensual';
      }
    };

    window.closeModalInfoCredMensual = function closeModalInfoCredMensual(event) {
      if (event && event.target !== event.currentTarget && !event.target.closest('button')) return;
      const modal = document.getElementById('modalInfoCredMensual');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        ModalManager.activeModal = null;

        if (modal._callbackDespues && typeof modal._callbackDespues === 'function') {
          const callback = modal._callbackDespues;
          modal._callbackDespues = null;
          setTimeout(() => callback(), 200);
        }
      }
    };

    // Función genérica para mantener compatibilidad (deprecated - usar funciones específicas)
    window.mostrarModalInfo = function mostrarModalInfo(titulo, contenido, tipoEstado = 'info', callbackDespues = null) {
      // Por defecto usar modal de tamizaje si no se especifica otro
      mostrarModalInfoTamizaje(contenido, callbackDespues);
    };

    window.closeModalInfo = function closeModalInfo(event) {
      // Cerrar cualquier modal informativo abierto
      closeModalInfoRecienNacido(event);
      closeModalInfoTamizaje(event);
      closeModalInfoCNV(event);
      closeModalInfoVisita(event);
      closeModalInfoVacuna(event);
      closeModalInfoCredMensual(event);
    };

    function closeVerControlesModal(event) {
      if (event && event.target !== event.currentTarget && !event.target.closest('.modal-controles')) return;
      const modal = document.getElementById('verControlesModal');
      if (!modal) return;
      modal.classList.add('hidden');
      modal.style.display = 'none';
    }

    function cambiarTab(tabName, element) {
      // Ocultar todos los tabs
      document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
        tab.style.display = 'none';
        tab.style.visibility = 'hidden';
        tab.style.opacity = '0';
      });

      // Mostrar el tab seleccionado
      const activeTab = document.getElementById('tab-' + tabName);
      if (activeTab) {
        activeTab.classList.add('active');
        activeTab.style.display = 'block';
        activeTab.style.visibility = 'visible';
        activeTab.style.opacity = '1';
        activeTab.style.height = 'auto';
        activeTab.style.width = '100%';
        activeTab.style.overflow = 'visible';

        // Asegurar que el contenido interno sea visible
        const controlSection = activeTab.querySelector('.control-section');
        if (controlSection) {
          controlSection.style.display = 'block';
          controlSection.style.visibility = 'visible';
          controlSection.style.opacity = '1';
        }

        // Asegurar que todos los elementos hijos sean visibles
        const allChildren = activeTab.querySelectorAll('.control-section, .controles-grid, .info-card, .visitas-list, .vacunas-grid, .control-card, .visita-item, .vacuna-card, .info-row, .section-header, .vacuna-header, .vacuna-footer, .btn-registrar-full, .btn-registrar-visita, .control-card-body, .control-info-item');
        allChildren.forEach(child => {
          child.style.display = '';
          child.style.visibility = 'visible';
          child.style.opacity = '1';
          // Remover cualquier estilo inline que pueda estar ocultando
          if (child.style.display === 'none') {
            child.style.display = '';
          }
        });

        // Asegurar que control-card-body muestre sus elementos
        const controlCardBodies = activeTab.querySelectorAll('.control-card-body');
        controlCardBodies.forEach(body => {
          body.style.display = 'block';
          body.style.visibility = 'visible';
          body.style.opacity = '1';

          // Asegurar que todos los elementos <p> dentro sean visibles
          const paragraphs = body.querySelectorAll('p');
          paragraphs.forEach(p => {
            p.style.display = 'block';
            p.style.visibility = 'visible';
            p.style.opacity = '1';
          });

          // Asegurar que todos los spans dentro de los <p> sean visibles
          const spans = body.querySelectorAll('p span');
          spans.forEach(span => {
            span.style.display = 'inline';
            span.style.visibility = 'visible';
            span.style.opacity = '1';
          });

          // Asegurar que todos los control-info-item dentro sean visibles
          const infoItems = body.querySelectorAll('.control-info-item');
          infoItems.forEach(item => {
            item.style.display = 'flex';
            item.style.visibility = 'visible';
            item.style.opacity = '1';
          });
        });

        // Forzar visibilidad de todos los elementos dentro del tab activo
        const allElements = activeTab.querySelectorAll('*');
        allElements.forEach(el => {
          if (el.style.visibility === 'hidden') {
            el.style.visibility = 'visible';
          }
          if (el.style.opacity === '0') {
            el.style.opacity = '1';
          }
          // Si es un control-info-item, asegurar display flex
          if (el.classList.contains('control-info-item')) {
            el.style.display = 'flex';
          }
        });
      }

      // Actualizar botones de navegación
      document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
      });
      if (element) {
        element.classList.add('active');
      }

      // Cargar datos cuando se cambia de tab (solo si hay ninoIdActual y el modal está abierto)
      if (ninoIdActual) {
        const modal = document.getElementById('verControlesModal');
        if (modal && !modal.classList.contains('hidden')) {
          setTimeout(() => {
            console.log(`📊 Cambiando a tab: ${tabName}, cargando datos...`);
            switch(tabName) {
              case 'cred':
                if (typeof cargarControlesCredMensual === 'function') {
                  cargarControlesCredMensual(ninoIdActual);
                }
                break;
              case 'recien-nacido':
                if (typeof cargarControlesRecienNacido === 'function') {
                  cargarControlesRecienNacido(ninoIdActual);
                }
                break;
              case 'tamizaje':
                if (typeof cargarTamizaje === 'function') {
                  cargarTamizaje(ninoIdActual);
                }
                break;
              case 'cnv':
                if (typeof cargarCNV === 'function') {
                  cargarCNV(ninoIdActual);
                }
                break;
              case 'visitas':
                if (typeof cargarVisitas === 'function') {
                  cargarVisitas(ninoIdActual).then(() => {
                    // Validar rangos después de cargar visitas
                    if (typeof validarRangosYHabilitarBotones === 'function') {
                      setTimeout(() => validarRangosYHabilitarBotones(ninoIdActual), 500);
                    }
                  }).catch(() => {
                    // Si hay error, aún así intentar validar
                    if (typeof validarRangosYHabilitarBotones === 'function') {
                      setTimeout(() => validarRangosYHabilitarBotones(ninoIdActual), 500);
                    }
                  });
                } else {
                  // Si no hay función cargarVisitas, validar directamente
                  if (typeof validarRangosYHabilitarBotones === 'function') {
                    setTimeout(() => validarRangosYHabilitarBotones(ninoIdActual), 500);
                  }
                }
                break;
              case 'vacunas':
                if (typeof cargarVacunas === 'function') {
                  cargarVacunas(ninoIdActual);
                }
                break;
            }
          }, 300);
        }
      }
    }

    function scrollTabs(direction) {
      const tabsContainer = document.querySelector('.tabs-container');
      if (!tabsContainer) return;

      const scrollAmount = 200;
      const currentScroll = tabsContainer.scrollLeft;

      if (direction === 'left') {
        tabsContainer.scrollTo({
          left: currentScroll - scrollAmount,
          behavior: 'smooth'
        });
      } else {
        tabsContainer.scrollTo({
          left: currentScroll + scrollAmount,
          behavior: 'smooth'
        });
      }
    }

    // Cerrar modales con Escape
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        closeAgregarNinoModal();
        closeVerControlesModal();
      }
    });

    // ========== VALIDACIÓN Y ENVÍO DEL FORMULARIO AGREGAR NIÑO ==========
    // Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
      // Inicializar selects del modal
      initModalSelects();

      // Agregar onclick a todos los botones "Ver Controles" que no lo tengan
      document.querySelectorAll('button.btn-cred-secondary').forEach(button => {
        // Verificar si el botón no tiene onclick y contiene "Ver Controles"
        if (!button.hasAttribute('onclick') && button.textContent.includes('Ver Controles')) {
          // Buscar la fila padre (tr)
          const row = button.closest('tr');
          if (row) {
            // Obtener las celdas de la fila
            const cells = row.querySelectorAll('td');
            if (cells.length >= 4) {
              // Establecimiento (cells[0]), Tipo Doc. (cells[1]), N° Documento (cells[2]), Nombre (cells[3])
              const establecimiento = cells[0].textContent.trim();
              const dni = cells[2].textContent.trim(); // N° Documento ahora está en cells[2]
              const nombre = cells[3].textContent.trim(); // Nombre ahora está en cells[3]

              // Agregar el onclick con los datos de la fila
              // Nota: necesitamos el nino.id, pero no está disponible aquí directamente
              // El onclick ya está definido en renderizarTabla, así que este código es un fallback
              button.setAttribute('onclick', `openVerControlesModal(0, '${nombre}', '${dni}', '${establecimiento}')`);
            }
          }
        }
      });

      // Configurar envío del formulario de agregar niño
      const agregarNinoForm = document.getElementById('agregarNinoForm');
      if (agregarNinoForm) {
        agregarNinoForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          // Declarar variable para el botón de enviar en el scope de la función
          let submitButton = null;

          // Validar campos requeridos
          const requiredFields = this.querySelectorAll('[required]');
          let isValid = true;
          let missingFields = [];
          let missingFieldsDetails = [];
          const fieldLabels = {
            'Id_Tipo_Documento': 'Tipo de Documento',
            'Numero_Documento': 'Número de Documento',
            'Apellidos_Nombres': 'Apellidos y Nombres',
            'Fecha_Nacimiento': 'Fecha de Nacimiento',
            'Genero': 'Género',
            'Codigo_Red': 'Red',
            'Codigo_Microred': 'MicroRed',
            'Id_Establecimiento': 'Establecimiento'
          };

          // Limpiar clases de error previas
          requiredFields.forEach(field => {
            field.classList.remove('border-red-500');
          });

          requiredFields.forEach(field => {
            const fieldName = field.name;
            let fieldValue = field.value;
            let fieldLabel = fieldLabels[fieldName] || field.closest('div')?.querySelector('label')?.textContent?.replace(/\*/g, '').trim() || fieldName.replace(/_/g, ' ');
            
            // Validar campos específicos
            if (fieldName === 'Numero_Documento') {
              fieldValue = fieldValue.replace(/[^0-9]/g, '');
              if (!fieldValue || fieldValue.length !== 8) {
                isValid = false;
                missingFields.push('Número de Documento');
                missingFieldsDetails.push({
                  campo: 'Número de Documento',
                  problema: fieldValue.length === 0 ? 'Campo vacío' : `Debe tener exactamente 8 dígitos (tiene ${fieldValue.length})`,
                  elemento: field
                });
                field.classList.add('border-red-500');
              } else {
                field.classList.remove('border-red-500');
              }
            } else if (fieldName === 'Fecha_Nacimiento') {
              if (!fieldValue || fieldValue.trim() === '') {
                isValid = false;
                missingFields.push('Fecha de Nacimiento');
                missingFieldsDetails.push({
                  campo: 'Fecha de Nacimiento',
                  problema: 'No se ha seleccionado una fecha',
                  elemento: field
                });
                field.classList.add('border-red-500');
              } else {
                field.classList.remove('border-red-500');
              }
            } else if (!fieldValue.trim()) {
              isValid = false;
              missingFields.push(fieldLabel);
              missingFieldsDetails.push({
                campo: fieldLabel,
                problema: 'Campo vacío',
                elemento: field
              });
              field.classList.add('border-red-500');
            } else {
              field.classList.remove('border-red-500');
            }
          });

          if (!isValid) {
            // Construir mensaje detallado
            let mensajeError = 'Para registrar al niño, debe completar TODOS los campos requeridos.\n\n';
            mensajeError += `Faltan ${missingFields.length} campo(s) por completar:\n\n`;
            
            missingFieldsDetails.forEach((detalle, index) => {
              mensajeError += `${index + 1}. ${detalle.campo}\n   └─ ${detalle.problema}\n\n`;
            });
            
            mensajeError += 'Por favor, complete todos los campos marcados con (*) antes de continuar.';
            
            mostrarAdvertenciaAgregarNino(
              'error',
              `Faltan ${missingFields.length} Campo(s) Requerido(s)`,
              mensajeError,
              missingFieldsDetails.map(d => `${d.campo}: ${d.problema}`)
            );
            
            // Hacer scroll al primer campo con error
            if (missingFieldsDetails.length > 0 && missingFieldsDetails[0].elemento) {
              setTimeout(() => {
                missingFieldsDetails[0].elemento.scrollIntoView({ behavior: 'smooth', block: 'center' });
                missingFieldsDetails[0].elemento.focus();
              }, 300);
            }
            
            return;
          }

          // Validar formato de DNI si está presente
          const numeroDoc = document.getElementById('numeroDocumentoInput');
          if (numeroDoc && numeroDoc.value) {
            const dniValue = numeroDoc.value.replace(/[^0-9]/g, '');
            if (dniValue.length !== 8) {
              mostrarAdvertenciaAgregarNino(
                'error',
                'Error en Número de Documento',
                'El número de documento debe tener exactamente 8 dígitos numéricos.',
                ['Número de Documento']
              );
              numeroDoc.focus();
              numeroDoc.classList.add('border-red-500');
              return;
            }
          }

          // Validar formato de DNI de la madre si está presente
          const dniMadre = document.getElementById('dniMadreInput');
          if (dniMadre && dniMadre.value) {
            const dniMadreValue = dniMadre.value.replace(/[^0-9]/g, '');
            if (dniMadreValue.length !== 8 && dniMadreValue.length > 0) {
              mostrarAdvertenciaAgregarNino(
                'error',
                'Error en DNI de la Madre',
                'El DNI de la madre debe tener exactamente 8 dígitos numéricos.',
                ['DNI de la Madre']
              );
              dniMadre.focus();
              dniMadre.classList.add('border-red-500');
              return;
            }
          }

          // Validar formato de celular si está presente
          const celular = document.getElementById('celularMadreInput');
          if (celular && celular.value) {
            const celularValue = celular.value.replace(/[^0-9]/g, '');
            if (celularValue.length !== 9 && celularValue.length > 0) {
              mostrarAdvertenciaAgregarNino(
                'error',
                'Error en Número de Celular',
                'El número de celular debe tener exactamente 9 dígitos numéricos.',
                ['Celular']
              );
              celular.focus();
              celular.classList.add('border-red-500');
              return;
            }
          }

          // Proceder directamente con el registro (sin modal de confirmación)
          
          // Asegurar que el nombre del establecimiento esté actualizado antes de enviar
          const eessSelect = document.getElementById('modalIdEstablecimiento');
          const nombreEstablecimientoInput = document.querySelector('input[name="Nombre_Establecimiento"]');
          
          if (eessSelect && eessSelect.value && nombreEstablecimientoInput) {
            const selectedOption = eessSelect.options[eessSelect.selectedIndex];
            if (selectedOption && selectedOption.textContent) {
              nombreEstablecimientoInput.value = selectedOption.textContent;
            }
          }

          // Deshabilitar el botón de enviar para evitar doble envío
          submitButton = agregarNinoForm.querySelector('button[type="submit"]');
          if (submitButton) {
            submitButton.disabled = true;
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = `
              <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Registrando...
            `;
          }

          // Crear FormData
          const formData = new FormData(agregarNinoForm);

          // Enviar formulario
          fetch(agregarNinoForm.action, {
            method: 'POST',
            body: formData,
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
          })
          .then(response => {
            // Verificar si la respuesta es JSON
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.includes("application/json")) {
              return response.json().then(data => {
                return { ok: response.ok, status: response.status, data: data };
              });
            } else {
              // Si no es JSON, intentar leer como texto
              return response.text().then(text => {
                console.error('Respuesta no JSON recibida:', text);
                return { 
                  ok: false, 
                  status: response.status, 
                  data: { 
                    success: false, 
                    message: 'Error inesperado del servidor. Por favor, verifique la consola para más detalles.' 
                  } 
                };
              });
            }
          })
          .then(result => {
            console.log('Resultado del registro:', result);
            
            // Restaurar botón de enviar
            if (submitButton) {
              submitButton.disabled = false;
              submitButton.innerHTML = 'Registrar Niño';
            }
            
            if (result.ok && result.data && result.data.success) {
              // Cerrar el modal de agregar niño primero
              closeAgregarNinoModal();
              
              // Mostrar mensaje de éxito que se cerrará automáticamente
              mostrarAdvertenciaAgregarNino(
                'success',
                '¡Registro Exitoso!',
                'El niño ha sido registrado exitosamente en el sistema.',
                null,
                null, // Sin callback de confirmación
                true  // Auto-cerrar con contador
              );
              
              // Iniciar contador automático y recargar tabla
              iniciarContadorModalExito();
            } else {
              // Manejar errores de validación u otros errores
              let errorMessage = 'No se pudo registrar el niño';
              let errorFields = [];
              
              if (result.data) {
                if (result.data.message) {
                  errorMessage = result.data.message;
                }
                
                if (result.data.errors) {
                  errorFields = Object.entries(result.data.errors)
                    .map(([field, messages]) => {
                      const fieldName = field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                      return fieldName;
                    });
                  
                  const errorList = Object.entries(result.data.errors)
                    .map(([field, messages]) => {
                      const fieldName = field.replace(/_/g, ' ');
                      return `${fieldName}: ${Array.isArray(messages) ? messages.join(', ') : messages}`;
                    })
                    .join('\n');
                  errorMessage = errorMessage + '\n\n' + errorList;
                }
              } else {
                errorMessage = `Error HTTP ${result.status || 'desconocido'}. Por favor, intente nuevamente.`;
              }
              
              console.error('Error al registrar:', result);
              mostrarAdvertenciaAgregarNino(
                'error',
                'Error al Registrar',
                errorMessage,
                errorFields.length > 0 ? errorFields : null
              );
            }
          })
          .catch(error => {
            console.error('Error en la petición:', error);
            
            // Restaurar botón de enviar
            if (submitButton) {
              submitButton.disabled = false;
              submitButton.innerHTML = 'Registrar Niño';
            }
            
            mostrarAdvertenciaAgregarNino(
              'error',
              'Error de Conexión',
              'No se pudo conectar con el servidor. Por favor, verifique su conexión a internet e intente nuevamente.\n\nSi el problema persiste, verifique la consola del navegador (F12) para más detalles.'
            );
          });
        });
      }
      
      // Variable para almacenar la función de confirmación (scope global)
      window.confirmacionCallbackAgregarNino = null;
      window.contadorModalTimeout = null;
      
      // Función para iniciar el contador automático del modal de éxito
      function iniciarContadorModalExito() {
        // Limpiar cualquier contador anterior
        if (window.contadorModalTimeout) {
          clearInterval(window.contadorModalTimeout);
          clearTimeout(window.contadorModalTimeout);
        }
        
        const contadorEl = document.getElementById('modalAdvertenciaContador');
        const segundosEl = document.getElementById('contadorSegundos');
        const barraEl = document.getElementById('contadorBarra');
        
        if (!contadorEl || !segundosEl || !barraEl) return;
        
        // Mostrar contador
        contadorEl.style.display = 'block';
        
        let segundos = 3;
        segundosEl.textContent = segundos;
        barraEl.style.width = '100%';
        
        // Función para cerrar y recargar
        const cerrarYRecargar = () => {
          closeModalAdvertenciaAgregarNino();
          // Recargar la tabla sin recargar toda la página
          if (typeof window.cargarNinos === 'function') {
            try {
              window.cargarNinos(1);
            } catch (error) {
              console.error('Error al recargar tabla:', error);
              window.location.reload();
            }
          } else {
            window.location.reload();
          }
        };
        
        // Actualizar contador cada segundo
        window.contadorModalTimeout = setInterval(() => {
          segundos--;
          segundosEl.textContent = segundos;
          const porcentaje = (segundos / 3) * 100;
          barraEl.style.width = porcentaje + '%';
          
          if (segundos <= 0) {
            clearInterval(window.contadorModalTimeout);
            cerrarYRecargar();
          }
        }, 1000);
        
        // También actualizar la barra cada 100ms para animación suave
        let tiempoTranscurrido = 0;
        const intervaloBarra = setInterval(() => {
          tiempoTranscurrido += 100;
          const porcentaje = ((3000 - tiempoTranscurrido) / 3000) * 100;
          if (porcentaje > 0) {
            barraEl.style.width = porcentaje + '%';
          } else {
            clearInterval(intervaloBarra);
          }
        }, 100);
      }
      
      // Función para mostrar advertencias/errores con modal personalizado
      function mostrarAdvertenciaAgregarNino(tipo, titulo, mensaje, camposFaltantes = null, callbackConfirmacion = null, autoCerrar = false) {
        const modal = document.getElementById('modalAdvertenciaAgregarNino');
        const header = modal.querySelector('.modal-advertencia-nino-header');
        const iconLarge = document.getElementById('modalAdvertenciaIconLarge');
        const iconHeader = document.getElementById('modalAdvertenciaIcon');
        const tituloEl = document.getElementById('modalAdvertenciaTitulo');
        const subtituloEl = document.getElementById('modalAdvertenciaSubtitulo');
        const mensajeEl = document.getElementById('modalAdvertenciaMensaje');
        const listaEl = document.getElementById('modalAdvertenciaLista');
        const btnConfirmar = document.getElementById('btnConfirmarAdvertenciaAgregarNino');
        const btnCancelar = modal.querySelector('.modal-advertencia-nino-btn-cancel');
        
        if (!modal) return;
        
        // Remover clases anteriores
        header.classList.remove('advertencia', 'error', 'success');
        iconLarge.classList.remove('advertencia', 'error', 'success');
        mensajeEl.classList.remove('advertencia', 'error', 'success');
        
        // Aplicar clases según el tipo
        header.classList.add(tipo);
        iconLarge.classList.add(tipo);
        mensajeEl.classList.add(tipo);
        
        // Configurar iconos según el tipo
        let iconSVG = '';
        if (tipo === 'error') {
          iconSVG = `
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
          `;
        } else if (tipo === 'success') {
          iconSVG = `
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
              <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
          `;
        } else {
          iconSVG = `
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
              <path d="M12 9v4"></path>
              <path d="M12 17h.01"></path>
            </svg>
          `;
        }
        
        // Actualizar contenido
        if (tituloEl) tituloEl.textContent = titulo;
        if (subtituloEl) {
          if (tipo === 'error') {
            subtituloEl.textContent = 'Revise los errores antes de continuar';
          } else if (tipo === 'success') {
            subtituloEl.textContent = 'Operación completada exitosamente';
          } else {
            subtituloEl.textContent = 'Revise la información antes de continuar';
          }
        }
        if (iconLarge) iconLarge.innerHTML = iconSVG;
        if (iconHeader) iconHeader.innerHTML = iconSVG.replace('width="64" height="64"', 'width="28" height="28"');
        
        // Formatear mensaje (convertir \n a <br> y mantener estructura)
        let mensajeFormateado = mensaje.replace(/\n/g, '<br>');
        // Mejorar formato de lista en el mensaje
        mensajeFormateado = mensajeFormateado.replace(/(\d+)\.\s/g, '<strong class="text-indigo-700">$1.</strong> ');
        mensajeFormateado = mensajeFormateado.replace(/└─/g, '<span class="text-slate-500 ml-4">└─</span>');
        if (mensajeEl) mensajeEl.innerHTML = mensajeFormateado;
        
        // Mostrar lista de campos faltantes si aplica
        if (camposFaltantes && camposFaltantes.length > 0) {
          listaEl.innerHTML = '';
          camposFaltantes.forEach((campo, index) => {
            const li = document.createElement('li');
            li.className = 'flex items-start gap-2';
            li.innerHTML = `
              <span class="flex-shrink-0 w-6 h-6 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xs font-bold">${index + 1}</span>
              <span class="flex-1 text-sm">${campo}</span>
            `;
            listaEl.appendChild(li);
          });
          listaEl.style.display = 'block';
        } else {
          listaEl.style.display = 'none';
        }
        
        // Obtener elementos del contador
        const contadorEl = document.getElementById('modalAdvertenciaContador');
        
        // Ocultar contador por defecto
        if (contadorEl) {
          contadorEl.style.display = 'none';
        }
        
        // Configurar botones y contador según el tipo
        if (autoCerrar && tipo === 'success') {
          // Para auto-cierre: ocultar botones y mostrar contador
          window.confirmacionCallbackAgregarNino = null;
          if (btnConfirmar) {
            btnConfirmar.style.display = 'none';
            btnConfirmar.disabled = true;
          }
          if (btnCancelar) {
            btnCancelar.style.display = 'none';
          }
        } else if (callbackConfirmacion) {
          // Con callback: mostrar botón de confirmar
          window.confirmacionCallbackAgregarNino = callbackConfirmacion;
          
          if (btnConfirmar) {
            btnConfirmar.style.display = 'flex';
            btnConfirmar.disabled = false;
            btnConfirmar.style.pointerEvents = 'auto';
            btnConfirmar.style.cursor = 'pointer';
          }
          if (btnCancelar) {
            btnCancelar.style.display = 'flex';
            btnCancelar.textContent = 'Cancelar';
          }
        } else {
          // Sin callback: solo botón cerrar
          window.confirmacionCallbackAgregarNino = null;
          if (btnConfirmar) {
            btnConfirmar.style.display = 'none';
            btnConfirmar.disabled = true;
          }
          if (btnCancelar) {
            btnCancelar.style.display = 'flex';
            btnCancelar.textContent = 'Cerrar';
          }
        }
        
        // Mostrar modal
        modal.classList.add('show');
        
        // Si es auto-cierre, iniciar contador después de mostrar el modal
        if (autoCerrar && tipo === 'success') {
          setTimeout(() => {
            iniciarContadorModalExito();
          }, 100);
        }
      }
      
      // Función para cerrar el modal cuando se hace click en el overlay
      function closeModalAdvertenciaAgregarNinoOnOverlay(event) {
        // Solo cerrar si se hace click directamente en el overlay, no en el contenedor
        if (event && event.target === event.currentTarget) {
          closeModalAdvertenciaAgregarNino();
        }
      }
      
      // Función para cerrar el modal de advertencia
      function closeModalAdvertenciaAgregarNino(event) {
        // Si hay evento, prevenir propagación
        if (event) {
          event.preventDefault();
          event.stopPropagation();
        }
        
        // Limpiar contador si existe
        if (window.contadorModalTimeout) {
          clearInterval(window.contadorModalTimeout);
          clearTimeout(window.contadorModalTimeout);
          window.contadorModalTimeout = null;
        }
        
        // Ocultar contador
        const contadorEl = document.getElementById('modalAdvertenciaContador');
        if (contadorEl) {
          contadorEl.style.display = 'none';
        }
        
        const modal = document.getElementById('modalAdvertenciaAgregarNino');
        if (modal) {
          modal.classList.remove('show');
        }
        window.confirmacionCallbackAgregarNino = null;
      }
      
      // Función para confirmar la advertencia (solo para casos especiales)
      function confirmarAdvertenciaAgregarNino(event) {
        // Prevenir propagación y comportamiento por defecto
        if (event) {
          event.preventDefault();
          event.stopPropagation();
        }
        
        console.log('🔘 Botón Continuar presionado');
        console.log('📋 Callback disponible:', window.confirmacionCallbackAgregarNino);
        
        if (window.confirmacionCallbackAgregarNino && typeof window.confirmacionCallbackAgregarNino === 'function') {
          // Guardar el callback antes de cerrar el modal
          const callback = window.confirmacionCallbackAgregarNino;
          
          // Limpiar el callback global antes de ejecutarlo
          window.confirmacionCallbackAgregarNino = null;
          
          // Cerrar el modal primero
          closeModalAdvertenciaAgregarNino();
          
          // Ejecutar el callback después de un pequeño delay
          setTimeout(() => {
            try {
              console.log('✅ Ejecutando callback...');
              callback();
              console.log('✅ Callback ejecutado correctamente');
            } catch (error) {
              console.error('❌ Error al ejecutar callback:', error);
              mostrarAdvertenciaAgregarNino(
                'error',
                'Error',
                'Ocurrió un error al procesar la confirmación. Por favor, intente nuevamente.'
              );
            }
          }, 200);
        } else {
          console.warn('⚠️ No hay callback disponible, solo cerrando modal');
          // Cerrar el modal si no hay callback
          closeModalAdvertenciaAgregarNino();
        }
      }

      // Cargar los niños al cargar la página
      cargarNinos();


      // ========== VARIABLE GLOBAL PARA EL ID DEL NIÑO ACTUAL ==========
      let ninoIdActual = null;

      // Asegurar que ninoIdActual esté disponible globalmente
      if (typeof window !== 'undefined') {
        window.ninoIdActual = ninoIdActual;
      }

      // ========== FUNCIONES PARA MODAL DE CONTROL RECIÉN NACIDO ==========
      // Función para abrir modal de registro de control recién nacido
      window.abrirModalRegistro = function abrirModalRegistro(numeroControl, rangoMin, rangoMax) {
        // Obtener ninoIdActual (puede estar en window o en el scope local)
        const currentNinoId = typeof window !== 'undefined' && window.ninoIdActual ? window.ninoIdActual : ninoIdActual;
        if (!currentNinoId) {
          alert('Error: No se ha seleccionado un niño. Por favor, cierre el modal y vuelva a abrir los controles.');
          return;
        }

        // Redirigir a la página de registro en nueva pestaña
        const url = `{{ route('controles-cred.recien-nacido.form') }}?nino_id=${currentNinoId}&numero_control=${numeroControl}`;
        window.open(url, '_blank');
      }

      function closeModalRegistro(event) {
        if (event && event.target !== event.currentTarget) return;
        ModalManager.cerrar('modalRegistroControl');
      }

      // Función para registrar control recién nacido
      window.registrarControl = function registrarControl(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        const currentNinoId = typeof window !== 'undefined' && window.ninoIdActual ? window.ninoIdActual : ninoIdActual;
        if (!currentNinoId) {
          alert('Error: No se ha seleccionado un niño. Por favor, cierre el modal y vuelva a abrir los controles.');
          return;
        }
        formData.append('nino_id', currentNinoId);

        fetch('{{ route("api.controles-recien-nacido.registrar") }}', {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            closeModalRegistro();

            // Recargar todos los datos desde el controlador
            const verControlesModal = document.getElementById('verControlesModal');
            const currentNinoId = typeof window !== 'undefined' && window.ninoIdActual ? window.ninoIdActual : ninoIdActual;
            if (currentNinoId && verControlesModal && !verControlesModal.classList.contains('hidden')) {
              if (typeof cargarDatosControles === 'function') {
                cargarDatosControles(currentNinoId).then(() => {
                  // Validar rangos después de cargar datos
                  if (typeof validarRangosYHabilitarBotones === 'function') {
                    setTimeout(() => validarRangosYHabilitarBotones(currentNinoId), 500);
                  }
                });
              }
              if (typeof evaluarAlertas === 'function') {
                evaluarAlertas(currentNinoId, '', '', '');
              }
            }

            // Mostrar mensaje de éxito
            const successMessage = document.createElement('div');
            successMessage.className = 'mensaje-exito animate-slide-in';
            successMessage.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
            successMessage.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
              <div style="flex: 1;">
                <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Control registrado exitosamente</div>
                <div style="font-size: 0.75rem; opacity: 0.95;">${data.message || 'El control ha sido guardado correctamente.'}</div>
              </div>
            `;
            document.body.appendChild(successMessage);
            setTimeout(() => {
              successMessage.classList.add('animate-slide-out');
              setTimeout(() => successMessage.remove(), 300);
            }, 4000);
          } else {
            const errorMessage = data.message || 'No se pudo registrar el control';
            const errorDiv = document.createElement('div');
            errorDiv.className = 'mensaje-error animate-slide-in';
            errorDiv.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4), 0 4px 6px -2px rgba(239, 68, 68, 0.3); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
            errorDiv.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
              </svg>
              <div style="flex: 1;">
                <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Error al registrar control</div>
                <div style="font-size: 0.75rem; opacity: 0.95;">${errorMessage}</div>
              </div>
            `;
            document.body.appendChild(errorDiv);
            setTimeout(() => {
              errorDiv.classList.add('animate-slide-out');
              setTimeout(() => errorDiv.remove(), 300);
            }, 5000);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error al registrar el control. Por favor, inténtelo nuevamente.');
        });
      }

      function actualizarControlRecienNacido(control) {
        if (!control) {
          console.error('❌ actualizarControlRecienNacido: control es null o undefined');
          return;
        }

        const numeroControl = control.numero_control;
        if (!numeroControl || numeroControl < 1 || numeroControl > 4) {
          console.error(`❌ actualizarControlRecienNacido: numero_control inválido: ${numeroControl}`);
          return;
        }

        console.log(`🔧 Actualizando control recién nacido ${numeroControl}:`, control);
        const fechaElement = document.getElementById(`control-${numeroControl}-fecha`);
        const edadElement = document.getElementById(`control-${numeroControl}-edad`);
        const estadoElement = document.getElementById(`control-${numeroControl}-estado`);

        if (!fechaElement || !edadElement || !estadoElement) {
          console.error(`❌ No se encontraron los elementos del control ${numeroControl}:`, {
            fechaElement: !!fechaElement,
            edadElement: !!edadElement,
            estadoElement: !!estadoElement
          });
          return;
        }

        // Formatear fecha
        if (fechaElement && (control.fecha_control || control.fecha)) {
          try {
            const fechaStr = control.fecha_control || control.fecha;
            const fecha = crearFechaLocal(fechaStr);
            const fechaFormateada = fecha.toLocaleDateString('es-PE', {
              year: 'numeric',
              month: '2-digit',
              day: '2-digit'
            });
            fechaElement.textContent = fechaFormateada;
            fechaElement.style.color = '#1e293b';
            fechaElement.style.fontWeight = '500';
          } catch (e) {
            fechaElement.textContent = control.fecha_control || control.fecha || '-';
          }
        } else if (fechaElement) {
          fechaElement.textContent = '-';
        }

        if (edadElement) {
          edadElement.textContent = control.edad_dias || control.edad || '-';
          if (control.edad_dias || control.edad) {
            edadElement.style.color = '#1e293b';
            edadElement.style.fontWeight = '500';
          }
        }

        if (estadoElement) {
          if (control.estado === 'cumple') {
            estadoElement.className = 'estado-badge cumple';
            estadoElement.textContent = 'CUMPLE';
          } else if (control.estado === 'no_cumple' || control.estado === 'no cumple') {
            estadoElement.className = 'estado-badge no-cumple';
            estadoElement.textContent = 'NO CUMPLE';
          } else {
            // Estado por defecto: SEGUIMIENTO
            estadoElement.className = 'estado-badge estado-seguimiento';
            estadoElement.textContent = 'SEGUIMIENTO';
          }
        }

        console.log(`✅ Control ${numeroControl} actualizado en tabla`);
      }

      // ========== FUNCIONES PARA EDITAR Y ELIMINAR CONTROLES ==========
      let controlIdActual = null;

      function editarControlRecienNacido(controlId, numeroControl, rangoMin, rangoMax) {
        controlIdActual = controlId;
        // Cargar datos del control y abrir modal en modo edición
        fetch(`{{ route("api.controles-recien-nacido") }}?control_id=${controlId}`, {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success && data.data.control) {
            const control = data.data.control;
            // Llenar el formulario con los datos del control
            document.getElementById('controlNumero').value = numeroControl;
            document.getElementById('controlNinoId').value = ninoIdActual;
            document.getElementById('controlRango').textContent = rangoMin + ' - ' + rangoMax;
            document.getElementById('controlFecha').value = control.fecha_control;

            // Abrir modal en modo edición
            abrirModalRegistro(numeroControl, rangoMin, rangoMax);

            // Cambiar el título del modal
            const modalTitle = document.querySelector('#modalRegistroControl h3');
            if (modalTitle) {
              modalTitle.textContent = `Editar Control ${numeroControl}`;
            }

            // Cambiar el botón de submit
            const submitBtn = document.querySelector('#formRegistroControl button[type="submit"]');
            if (submitBtn) {
              submitBtn.textContent = 'Actualizar Control';
              submitBtn.onclick = (e) => {
                e.preventDefault();
                actualizarControl(controlId);
              };
            }
          }
        })
        .catch(error => {
          console.error('Error al cargar control:', error);
          alert('Error al cargar los datos del control');
        });
      }

      function actualizarControl(controlId) {
        const formData = new FormData(document.getElementById('formRegistroControl'));
        formData.append('_method', 'PUT');
        formData.append('control_id', controlId);

        fetch(`{{ route("api.controles-recien-nacido.update", ["id" => ":id"]) }}`.replace(':id', controlId), {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            closeModalRegistro({ target: document.getElementById('modalRegistroControl') });
            cargarControlesRecienNacido(ninoIdActual);
            evaluarAlertas(ninoIdActual, '', '', '');

            // Mostrar mensaje de éxito
            const successMessage = document.createElement('div');
            successMessage.className = 'mensaje-exito animate-slide-in';
            successMessage.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
            successMessage.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
              <div style="flex: 1;">
                <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Control actualizado exitosamente</div>
                <div style="font-size: 0.75rem; opacity: 0.95;">Los cambios del control han sido guardados correctamente.</div>
              </div>
            `;
            document.body.appendChild(successMessage);

            setTimeout(() => {
              successMessage.classList.add('animate-slide-out');
              setTimeout(() => successMessage.remove(), 300);
            }, 4000);
          } else {
            alert('Error: ' + (data.message || 'No se pudo actualizar el control'));
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error al actualizar el control. Por favor, inténtelo nuevamente.');
        });
      }

      function eliminarControlRecienNacido(controlId, numeroControl) {
        if (!confirm(`¿Está seguro de eliminar el Control ${numeroControl}? Esta acción no se puede deshacer.`)) {
          return;
        }

        fetch(`{{ route("api.controles-recien-nacido.delete", ["id" => ":id"]) }}`.replace(':id', controlId), {
          method: 'DELETE',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            cargarControlesRecienNacido(ninoIdActual);
            evaluarAlertas(ninoIdActual, '', '', '');

            // Mostrar mensaje de éxito
            const successMessage = document.createElement('div');
            successMessage.className = 'mensaje-exito animate-slide-in';
            successMessage.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
            successMessage.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
              <div style="flex: 1;">
                <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Control eliminado exitosamente</div>
                <div style="font-size: 0.75rem; opacity: 0.95;">El Control ${numeroControl} ha sido eliminado correctamente.</div>
              </div>
            `;
            document.body.appendChild(successMessage);

            setTimeout(() => {
              successMessage.classList.add('animate-slide-out');
              setTimeout(() => successMessage.remove(), 300);
            }, 4000);
          } else {
            alert('Error: ' + (data.message || 'No se pudo eliminar el control'));
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error al eliminar el control. Por favor, inténtelo nuevamente.');
        });
      }

      function eliminarControlCredMensual(controlId, numeroControl) {
        if (!confirm(`¿Está seguro de eliminar el Control CRED ${numeroControl}? Esta acción no se puede deshacer.`)) {
          return;
        }

        fetch(`{{ route("api.controles-cred-mensual.delete", ["id" => ":id"]) }}`.replace(':id', controlId), {
          method: 'DELETE',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Recargar controles CRED mensual
            if (typeof cargarControlesCredMensual === 'function') {
              cargarControlesCredMensual(ninoIdActual);
            }
            
            // Recargar alertas si existe la función
            if (typeof evaluarAlertas === 'function') {
              evaluarAlertas(ninoIdActual, '', '', '');
            }

            // Mostrar mensaje de éxito
            const successMessage = document.createElement('div');
            successMessage.className = 'mensaje-exito animate-slide-in';
            successMessage.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
            successMessage.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
              <div style="flex: 1;">
                <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Control CRED eliminado exitosamente</div>
                <div style="font-size: 0.75rem; opacity: 0.95;">El Control CRED ${numeroControl} ha sido eliminado correctamente.</div>
              </div>
            `;
            document.body.appendChild(successMessage);

            setTimeout(() => {
              successMessage.classList.add('animate-slide-out');
              setTimeout(() => successMessage.remove(), 300);
            }, 4000);
          } else {
            alert('Error: ' + (data.message || 'No se pudo eliminar el control CRED'));
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error al eliminar el control CRED. Por favor, inténtelo nuevamente.');
        });
      }

      // ========== FUNCIONES PARA MODAL DE TAMIZAJE ==========
      // Función para abrir modal de tamizaje
      window.abrirModalTamizaje = function abrirModalTamizaje() {
        const currentNinoId = typeof window !== 'undefined' && window.ninoIdActual ? window.ninoIdActual : ninoIdActual;
        if (!currentNinoId) {
          alert('Error: No se ha seleccionado un niño. Por favor, cierre el modal y vuelva a abrir los controles.');
          return;
        }

        // Redirigir a la página de registro
        const url = `{{ route('controles-cred.tamizaje.form') }}?nino_id=${currentNinoId}`;
        window.open(url, '_blank');
      }

      function closeModalTamizaje(event) {
        if (event && event.target !== event.currentTarget) return;
        ModalManager.cerrar('modalTamizaje');
      }

      // Función para registrar tamizaje
      window.registrarTamizaje = function registrarTamizaje(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        const currentNinoId = typeof window !== 'undefined' && window.ninoIdActual ? window.ninoIdActual : ninoIdActual;
        if (!currentNinoId) {
          alert('Error: No se ha seleccionado un niño. Por favor, cierre el modal y vuelva a abrir los controles.');
          return;
        }
        formData.append('nino_id', currentNinoId);

        const submitBtn = event.target.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn ? submitBtn.innerHTML : '';

        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Guardando...';
        }

        fetch('{{ route("api.tamizaje.registrar") }}', {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => response.json())
        .then(data => {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
          }

          if (data.success) {
            closeModalTamizaje();

            // Recargar todos los datos desde el controlador
            const verControlesModal = document.getElementById('verControlesModal');
            const currentNinoId = typeof window !== 'undefined' && window.ninoIdActual ? window.ninoIdActual : ninoIdActual;
            if (currentNinoId && verControlesModal && !verControlesModal.classList.contains('hidden')) {
              if (typeof cargarDatosControles === 'function') {
                cargarDatosControles(currentNinoId).then(() => {
                  // Validar rangos después de cargar datos
                  if (typeof validarRangosYHabilitarBotones === 'function') {
                    setTimeout(() => validarRangosYHabilitarBotones(currentNinoId), 500);
                  }
                });
              }
              if (typeof evaluarAlertas === 'function') {
                evaluarAlertas(currentNinoId, '', '', '');
              }
            }

            const successMessage = document.createElement('div');
            successMessage.className = 'mensaje-exito animate-slide-in';
            successMessage.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
            successMessage.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
              <div style="flex: 1;">
                <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Tamizaje Neonatal registrado exitosamente</div>
                <div style="font-size: 0.75rem; opacity: 0.95;">El tamizaje ha sido guardado correctamente.</div>
              </div>
            `;
            document.body.appendChild(successMessage);

            setTimeout(() => {
              successMessage.classList.add('animate-slide-out');
              setTimeout(() => successMessage.remove(), 300);
            }, 4000);
          } else {
            const errorMessage = data.message || 'No se pudo registrar el tamizaje';
            const errorDiv = document.createElement('div');
            errorDiv.className = 'mensaje-error animate-slide-in';
            errorDiv.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4), 0 4px 6px -2px rgba(239, 68, 68, 0.3); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
            errorDiv.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
              </svg>
              <div style="flex: 1;">
                <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Error al registrar tamizaje</div>
                <div style="font-size: 0.75rem; opacity: 0.95;">${errorMessage}</div>
              </div>
            `;
            document.body.appendChild(errorDiv);

            setTimeout(() => {
              errorDiv.classList.add('animate-slide-out');
              setTimeout(() => errorDiv.remove(), 300);
            }, 5000);
          }
        })
        .catch(error => {
          console.error('Error:', error);

          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
          }

          const errorDiv = document.createElement('div');
          errorDiv.className = 'mensaje-error animate-slide-in';
          errorDiv.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4), 0 4px 6px -2px rgba(239, 68, 68, 0.3); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
          errorDiv.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div style="flex: 1;">
              <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Error de conexión</div>
              <div style="font-size: 0.75rem; opacity: 0.95;">Error al registrar el tamizaje. Por favor, inténtelo nuevamente.</div>
            </div>
          `;
          document.body.appendChild(errorDiv);

          setTimeout(() => {
            errorDiv.classList.add('animate-slide-out');
            setTimeout(() => errorDiv.remove(), 300);
          }, 5000);
        });
      }

      // ========== FUNCIONES PARA MODAL DE CNV ==========
      // Función para abrir modal de CNV
      window.abrirModalCNV = function abrirModalCNV() {
        const currentNinoId = typeof window !== 'undefined' && window.ninoIdActual ? window.ninoIdActual : ninoIdActual;
        if (!currentNinoId) {
          alert('Error: No se ha seleccionado un niño. Por favor, cierre el modal y vuelva a abrir los controles.');
          return;
        }

        // Redirigir a la página de registro
        const url = `{{ route('controles-cred.cnv.form') }}?nino_id=${currentNinoId}`;
        window.open(url, '_blank');
      }

      function closeModalCNV(event) {
        if (event && event.target !== event.currentTarget) return;
        ModalManager.cerrar('modalCNV');
      }

      // Función para registrar CNV
      window.registrarCNV = function registrarCNV(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        const currentNinoId = typeof window !== 'undefined' && window.ninoIdActual ? window.ninoIdActual : ninoIdActual;
        if (!currentNinoId) {
          alert('Error: No se ha seleccionado un niño. Por favor, cierre el modal y vuelva a abrir los controles.');
          return;
        }
        formData.append('nino_id', currentNinoId);

        const submitBtn = event.target.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn ? submitBtn.innerHTML : '';

        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Guardando...';
        }

        fetch('{{ route("api.cnv.registrar") }}', {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => response.json())
        .then(data => {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
          }

          if (data.success) {
            closeModalCNV();

            // Recargar todos los datos desde el controlador
            const verControlesModal = document.getElementById('verControlesModal');
            const currentNinoId = typeof window !== 'undefined' && window.ninoIdActual ? window.ninoIdActual : ninoIdActual;
            if (currentNinoId && verControlesModal && !verControlesModal.classList.contains('hidden')) {
              if (typeof cargarDatosControles === 'function') {
                cargarDatosControles(currentNinoId).then(() => {
                  // Validar rangos después de cargar datos
                  if (typeof validarRangosYHabilitarBotones === 'function') {
                    setTimeout(() => validarRangosYHabilitarBotones(currentNinoId), 500);
                  }
                });
              }
              if (typeof evaluarAlertas === 'function') {
                evaluarAlertas(currentNinoId, '', '', '');
              }
            }

            const successMessage = document.createElement('div');
            successMessage.className = 'mensaje-exito animate-slide-in';
            successMessage.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
            successMessage.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
              <div style="flex: 1;">
                <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">CNV registrado exitosamente</div>
                <div style="font-size: 0.75rem; opacity: 0.95;">Los datos del recién nacido han sido guardados correctamente.</div>
              </div>
            `;
            document.body.appendChild(successMessage);

            setTimeout(() => {
              successMessage.classList.add('animate-slide-out');
              setTimeout(() => successMessage.remove(), 300);
            }, 4000);
          } else {
            const errorMessage = data.message || 'No se pudo registrar el CNV';
            const errorDiv = document.createElement('div');
            errorDiv.className = 'mensaje-error animate-slide-in';
            errorDiv.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4), 0 4px 6px -2px rgba(239, 68, 68, 0.3); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
            errorDiv.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
              </svg>
              <div style="flex: 1;">
                <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Error al registrar CNV</div>
                <div style="font-size: 0.75rem; opacity: 0.95;">${errorMessage}</div>
              </div>
            `;
            document.body.appendChild(errorDiv);

            setTimeout(() => {
              errorDiv.classList.add('animate-slide-out');
              setTimeout(() => errorDiv.remove(), 300);
            }, 5000);
          }
        })
        .catch(error => {
          console.error('Error:', error);

          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
          }

          const errorDiv = document.createElement('div');
          errorDiv.className = 'mensaje-error animate-slide-in';
          errorDiv.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4), 0 4px 6px -2px rgba(239, 68, 68, 0.3); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
          errorDiv.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div style="flex: 1;">
              <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Error de conexión</div>
              <div style="font-size: 0.75rem; opacity: 0.95;">Error al registrar el CNV. Por favor, inténtelo nuevamente.</div>
            </div>
          `;
          document.body.appendChild(errorDiv);

          setTimeout(() => {
            errorDiv.classList.add('animate-slide-out');
            setTimeout(() => errorDiv.remove(), 300);
          }, 5000);
        });
      }

      // ========== FUNCIONES PARA MODAL DE VISITA DOMICILIARIA ==========
      // Función para abrir modal de visita domiciliaria
      window.abrirModalVisita = function abrirModalVisita(periodo) {
        const currentNinoId = typeof window !== 'undefined' && window.ninoIdActual ? window.ninoIdActual : ninoIdActual;
        if (!currentNinoId) {
          alert('Error: No se ha seleccionado un niño. Por favor, cierre el modal y vuelva a abrir los controles.');
          return;
        }

        // Redirigir a la página de registro
        const url = `{{ route('controles-cred.visitas.form') }}?nino_id=${currentNinoId}&periodo=${periodo}`;
        window.open(url, '_blank');
      }

      function closeModalVisita(event) {
        if (event && event.target !== event.currentTarget) return;
        ModalManager.cerrar('modalVisita');
      }

      // Función para registrar visita domiciliaria
      window.registrarVisita = function registrarVisita(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        const currentNinoId = typeof window !== 'undefined' && window.ninoIdActual ? window.ninoIdActual : ninoIdActual;
        if (!currentNinoId) {
          alert('Error: No se ha seleccionado un niño. Por favor, cierre el modal y vuelva a abrir los controles.');
          return;
        }
        formData.append('nino_id', currentNinoId);

        const submitBtn = event.target.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn ? submitBtn.innerHTML : '';

        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Guardando...';
        }

        fetch('{{ route("api.visitas.registrar") }}', {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => response.json())
        .then(data => {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
          }

          if (data.success) {
            closeModalVisita();

            // Recargar todos los datos desde el controlador
            const verControlesModal = document.getElementById('verControlesModal');
            const currentNinoId = typeof window !== 'undefined' && window.ninoIdActual ? window.ninoIdActual : ninoIdActual;
            if (currentNinoId && verControlesModal && !verControlesModal.classList.contains('hidden')) {
              if (typeof cargarDatosControles === 'function') {
                cargarDatosControles(currentNinoId).then(() => {
                  // Validar rangos después de cargar datos
                  if (typeof validarRangosYHabilitarBotones === 'function') {
                    setTimeout(() => validarRangosYHabilitarBotones(currentNinoId), 500);
                  }
                });
              }
              if (typeof evaluarAlertas === 'function') {
                evaluarAlertas(currentNinoId, '', '', '');
              }
            }

            const successMessage = document.createElement('div');
            successMessage.className = 'mensaje-exito animate-slide-in';
            successMessage.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
            successMessage.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
              <div style="flex: 1;">
                <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Visita Domiciliaria registrada exitosamente</div>
                <div style="font-size: 0.75rem; opacity: 0.95;">La visita ha sido guardada correctamente.</div>
              </div>
            `;
            document.body.appendChild(successMessage);

            setTimeout(() => {
              successMessage.classList.add('animate-slide-out');
              setTimeout(() => successMessage.remove(), 300);
            }, 4000);
          } else {
            const errorMessage = data.message || 'No se pudo registrar la visita';
            const errorDiv = document.createElement('div');
            errorDiv.className = 'mensaje-error animate-slide-in';
            errorDiv.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4), 0 4px 6px -2px rgba(239, 68, 68, 0.3); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
            errorDiv.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
              </svg>
              <div style="flex: 1;">
                <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Error al registrar visita</div>
                <div style="font-size: 0.75rem; opacity: 0.95;">${errorMessage}</div>
              </div>
            `;
            document.body.appendChild(errorDiv);

            setTimeout(() => {
              errorDiv.classList.add('animate-slide-out');
              setTimeout(() => errorDiv.remove(), 300);
            }, 5000);
          }
        })
        .catch(error => {
          console.error('Error:', error);

          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
          }

          const errorDiv = document.createElement('div');
          errorDiv.className = 'mensaje-error animate-slide-in';
          errorDiv.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4), 0 4px 6px -2px rgba(239, 68, 68, 0.3); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
          errorDiv.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div style="flex: 1;">
              <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Error de conexión</div>
              <div style="font-size: 0.75rem; opacity: 0.95;">Error al registrar la visita. Por favor, inténtelo nuevamente.</div>
            </div>
          `;
          document.body.appendChild(errorDiv);

          setTimeout(() => {
            errorDiv.classList.add('animate-slide-out');
            setTimeout(() => errorDiv.remove(), 300);
          }, 5000);
        });
      }

      // ========== FUNCIONES PARA MODAL DE VACUNA ==========
      // Función para abrir modal de vacuna
      // Función para actualizar el nombre de vacuna desde el selector
      window.actualizarNombreVacuna = function actualizarNombreVacuna() {
        const select = document.getElementById('vacunaNombreSelect');
        const hiddenInput = document.getElementById('vacunaNombre');
        if (select && hiddenInput) {
          hiddenInput.value = select.value;
        }
      };

      // Función para calcular edad en días para vacunas
      window.calcularEdadDiasVacuna = function calcularEdadDiasVacuna(tipoVacuna) {
        // Obtener fecha de nacimiento del niño desde el modal
        const fechaNacimientoEl = document.getElementById('fechaNacimientoValue');
        if (!fechaNacimientoEl) {
          console.warn('No se encontró el elemento fechaNacimientoValue');
          return;
        }

        let fechaNacimientoText = fechaNacimientoEl.textContent.trim();
        if (!fechaNacimientoText || fechaNacimientoText === '-') {
          console.warn('Fecha de nacimiento no disponible');
          return;
        }

        // Extraer fecha ISO del texto (formato: "DD de MES de YYYY (YYYY-MM-DD)")
        let fechaNacimientoISO = null;
        const fechaMatch = fechaNacimientoText.match(/\((\d{4}-\d{2}-\d{2})\)/);
        if (fechaMatch) {
          fechaNacimientoISO = fechaMatch[1];
        } else {
          // Intentar obtener directamente si es formato ISO
          const fechaDirecta = fechaNacimientoText.match(/(\d{4}-\d{2}-\d{2})/);
          if (fechaDirecta) {
            fechaNacimientoISO = fechaDirecta[1];
          } else {
            console.warn('No se pudo extraer la fecha ISO de:', fechaNacimientoText);
            return;
          }
        }

        // Obtener fecha de la vacuna según el tipo
        let fechaVacuna = '';
        let edadInput = null;

        if (tipoVacuna === 'BCG') {
          fechaVacuna = document.getElementById('vacunaFechaBCG')?.value;
          edadInput = document.getElementById('vacunaEdadBCG');
        } else if (tipoVacuna === 'HVB') {
          fechaVacuna = document.getElementById('vacunaFechaHVB')?.value;
          edadInput = document.getElementById('vacunaEdadHVB');
        }

        if (!fechaVacuna || !edadInput) return;

        // Calcular edad en días usando la función existente
        const edadDias = calcularEdadDias(fechaNacimientoISO, fechaVacuna);
        edadInput.value = edadDias + ' días';
      };

      window.abrirModalVacuna = function abrirModalVacuna(nombreVacuna = null) {
        const currentNinoId = typeof window !== 'undefined' && window.ninoIdActual ? window.ninoIdActual : ninoIdActual;
        if (!currentNinoId) {
          alert('Error: No se ha seleccionado un niño. Por favor, cierre el modal y vuelva a abrir los controles.');
          return;
        }

        if (!nombreVacuna || !['BCG', 'HVB'].includes(nombreVacuna)) {
          alert('Error: Tipo de vacuna inválido.');
          return;
        }

        // Redirigir a la página de registro
        const url = `{{ route('controles-cred.vacunas.form') }}?nino_id=${currentNinoId}&tipo=${nombreVacuna}`;
        window.open(url, '_blank');
      }

      function closeModalVacuna(event) {
        if (event && event.target !== event.currentTarget) return;
        ModalManager.cerrar('modalVacuna');
      }

      // Función para registrar vacuna
      window.registrarVacuna = function registrarVacuna(event) {
        event.preventDefault();

        const currentNinoId = typeof window !== 'undefined' && window.ninoIdActual ? window.ninoIdActual : ninoIdActual;
        if (!currentNinoId) {
          alert('Error: No se ha seleccionado un niño. Por favor, cierre el modal y vuelva a abrir los controles.');
          return;
        }

        // Obtener fechas de BCG y HVB
        const fechaBCG = document.getElementById('vacunaFechaBCG')?.value;
        const fechaHVB = document.getElementById('vacunaFechaHVB')?.value;

        if (!fechaBCG && !fechaHVB) {
          alert('Por favor, ingrese al menos una fecha de vacunación (BCG o HVB).');
          return;
        }

        const submitBtn = event.target.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn ? submitBtn.innerHTML : '';

        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Guardando...';
        }

        // Array para almacenar las promesas de registro
        const promesas = [];

        // Registrar BCG si tiene fecha
        if (fechaBCG) {
          const formDataBCG = new FormData();
          formDataBCG.append('nino_id', currentNinoId);
          formDataBCG.append('nombre_vacuna', 'BCG');
          formDataBCG.append('fecha_aplicacion', fechaBCG);

          promesas.push(
            fetch('{{ route("api.vacunas.registrar") }}', {
              method: 'POST',
              body: formDataBCG,
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
              }
            })
          );
        }

        // Registrar HVB si tiene fecha
        if (fechaHVB) {
          const formDataHVB = new FormData();
          formDataHVB.append('nino_id', currentNinoId);
          formDataHVB.append('nombre_vacuna', 'Hepatitis B (HvB)');
          formDataHVB.append('fecha_aplicacion', fechaHVB);

          promesas.push(
            fetch('{{ route("api.vacunas.registrar") }}', {
              method: 'POST',
              body: formDataHVB,
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
              }
            })
          );
        }

        // Ejecutar todas las promesas
        Promise.all(promesas.map(p => p.then(r => r.json())))
        .then(responses => {
          const todasExitosas = responses.every(r => r.success);
          const algunasExitosas = responses.some(r => r.success);

          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
          }

          if (todasExitosas) {
            // Mostrar mensaje de éxito
            const mensaje = responses.length === 2
              ? 'Ambas vacunas (BCG y HVB) registradas correctamente.'
              : responses.length === 1 && fechaBCG
              ? 'Vacuna BCG registrada correctamente.'
              : 'Vacuna HVB registrada correctamente.';

            // Mostrar mensaje de éxito
            const successMessage = document.createElement('div');
            successMessage.className = 'mensaje-exito animate-slide-in';
            successMessage.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
            successMessage.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
              <span>${mensaje}</span>
            `;
            document.body.appendChild(successMessage);
            setTimeout(() => {
              successMessage.style.opacity = '0';
              successMessage.style.transform = 'translateX(100%)';
              setTimeout(() => successMessage.remove(), 300);
            }, 3000);

            closeModalVacuna();

            // Recargar todos los datos desde el controlador
            const verControlesModal = document.getElementById('verControlesModal');
            if (currentNinoId && verControlesModal && !verControlesModal.classList.contains('hidden')) {
              if (typeof cargarDatosControles === 'function') {
                cargarDatosControles(currentNinoId).then(() => {
                  // Validar rangos después de cargar datos
                  if (typeof validarRangosYHabilitarBotones === 'function') {
                    setTimeout(() => validarRangosYHabilitarBotones(currentNinoId), 500);
                  }
                });
              }
              if (typeof evaluarAlertas === 'function') {
                evaluarAlertas(currentNinoId, '', '', '');
              }
            }
          } else if (algunasExitosas) {
            // Algunas se guardaron, otras no
            const errores = responses.filter(r => !r.success);
            const errorMessage = document.createElement('div');
            errorMessage.className = 'mensaje-error animate-slide-in';
            errorMessage.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #f59e0b, #d97706); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
            errorMessage.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
              </svg>
              <span>Algunas vacunas se registraron correctamente, pero hubo errores en otras.</span>
            `;
            document.body.appendChild(errorMessage);
            setTimeout(() => {
              errorMessage.style.opacity = '0';
              errorMessage.style.transform = 'translateX(100%)';
              setTimeout(() => errorMessage.remove(), 300);
            }, 5000);
            console.error('Errores al registrar vacunas:', errores);
          } else {
            // Todas fallaron
            const errores = responses.map(r => r.message || 'Error desconocido').join(', ');
            const errorMessage = document.createElement('div');
            errorMessage.className = 'mensaje-error animate-slide-in';
            errorMessage.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
            errorMessage.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
              </svg>
              <span>Error al registrar las vacunas: ${errores}</span>
            `;
            document.body.appendChild(errorMessage);
            setTimeout(() => {
              errorMessage.style.opacity = '0';
              errorMessage.style.transform = 'translateX(100%)';
              setTimeout(() => errorMessage.remove(), 300);
            }, 5000);
          }
        })
        .catch(error => {
          console.error('Error al registrar vacunas:', error);
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
          }
          const errorMessage = document.createElement('div');
          errorMessage.className = 'mensaje-error animate-slide-in';
          errorMessage.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
          errorMessage.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span>Error al registrar las vacunas. Por favor, intente nuevamente.</span>
          `;
          document.body.appendChild(errorMessage);
          setTimeout(() => {
            errorMessage.style.opacity = '0';
            errorMessage.style.transform = 'translateX(100%)';
            setTimeout(() => errorMessage.remove(), 300);
          }, 5000);
        });
      }

      // ========== FUNCIÓN PARA REGISTRO CRED MENSUAL EN PÁGINA APARTE ==========
      // Ahora solo redirige a una página independiente con el formulario
      window.abrirModalCredMensual = function abrirModalCredMensual(mes, controlId = null) {
        // Usar siempre la variable global window.ninoIdActual para evitar errores de referencia
        const currentNinoId = typeof window !== 'undefined' ? window.ninoIdActual : null;
        if (!currentNinoId) {
          alert('Error: No se ha seleccionado un niño. Por favor, cierre el modal y vuelva a abrir los controles.');
          return;
        }

        const baseUrl = '{{ route("controles-cred.cred-mensual.form") }}';
        let url = baseUrl + '?nino_id=' + encodeURIComponent(currentNinoId) + '&mes=' + encodeURIComponent(mes);
        if (controlId) {
          url += '&control_id=' + encodeURIComponent(controlId);
        }
        window.location.href = url;
      }

      function closeModalCredMensual(event) {
        if (event && event.target !== event.currentTarget) return;
        ModalManager.cerrar('modalCredMensual');
      }

      // Función para registrar control CRED mensual
      window.registrarCredMensual = function registrarCredMensual(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        const currentNinoId = typeof window !== 'undefined' && window.ninoIdActual ? window.ninoIdActual : ninoIdActual;
        if (!currentNinoId) {
          alert('Error: No se ha seleccionado un niño. Por favor, cierre el modal y vuelva a abrir los controles.');
          return;
        }
        formData.append('nino_id', currentNinoId);

        const controlId = document.getElementById('credMensualControlId').value;
        const isUpdate = controlId && controlId !== '';

        const submitBtn = event.target.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn ? submitBtn.innerHTML : '';

        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ' + (isUpdate ? 'Actualizando...' : 'Guardando...');
        }

        // Si hay control_id, actualizar; si no, crear nuevo
        const url = isUpdate
          ? `{{ route("api.controles-cred-mensual.registrar") }}/${controlId}`
          : '{{ route("api.controles-cred-mensual.registrar") }}';
        const method = isUpdate ? 'PUT' : 'POST';

        if (isUpdate) {
          formData.append('_method', 'PUT');
        }

        fetch(url, {
          method: method,
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => response.json())
        .then(data => {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
          }

          if (data.success) {
            closeModalCredMensual();

            // Recargar todos los datos desde el controlador
            const verControlesModal = document.getElementById('verControlesModal');
            const currentNinoId = typeof window !== 'undefined' && window.ninoIdActual ? window.ninoIdActual : ninoIdActual;
            if (currentNinoId && verControlesModal && !verControlesModal.classList.contains('hidden')) {
              // Recargar controles CRED mensual para actualizar la tabla de análisis
              if (typeof cargarControlesCredMensual === 'function') {
                cargarControlesCredMensual(currentNinoId);
              }
              
              if (typeof cargarDatosControles === 'function') {
                cargarDatosControles(currentNinoId).then(() => {
                  // Validar rangos después de cargar datos
                  if (typeof validarRangosYHabilitarBotones === 'function') {
                    setTimeout(() => validarRangosYHabilitarBotones(currentNinoId), 500);
                  }
                });
              }
              if (typeof evaluarAlertas === 'function') {
                evaluarAlertas(currentNinoId, '', '', '');
              }
            }

            // Mostrar mensaje de éxito
            const successMessage = document.createElement('div');
            successMessage.className = 'mensaje-exito animate-slide-in';
            successMessage.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
            const mensajeTexto = isUpdate ? 'Control CRED Mensual actualizado exitosamente' : 'Control CRED Mensual registrado exitosamente';
            const mensajeDetalle = isUpdate ? 'Los cambios han sido guardados correctamente.' : 'El control ha sido guardado correctamente.';
            successMessage.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
              <div style="flex: 1;">
                <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">${mensajeTexto}</div>
                <div style="font-size: 0.75rem; opacity: 0.95;">${mensajeDetalle}</div>
              </div>
            `;
            document.body.appendChild(successMessage);

            // Limpiar el ID del control después de guardar
            document.getElementById('credMensualControlId').value = '';

            setTimeout(() => {
              successMessage.classList.add('animate-slide-out');
              setTimeout(() => successMessage.remove(), 300);
            }, 4000);
          } else {
            const errorMessage = data.message || 'No se pudo registrar el control CRED mensual';
            const errorDiv = document.createElement('div');
            errorDiv.className = 'mensaje-error animate-slide-in';
            errorDiv.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4), 0 4px 6px -2px rgba(239, 68, 68, 0.3); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
            errorDiv.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
              </svg>
              <div style="flex: 1;">
                <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Error al registrar control</div>
                <div style="font-size: 0.75rem; opacity: 0.95;">${errorMessage}</div>
              </div>
            `;
            document.body.appendChild(errorDiv);

            setTimeout(() => {
              errorDiv.classList.add('animate-slide-out');
              setTimeout(() => errorDiv.remove(), 300);
            }, 5000);
          }
        })
        .catch(error => {
          console.error('Error:', error);

          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
          }

          const errorDiv = document.createElement('div');
          errorDiv.className = 'mensaje-error animate-slide-in';
          errorDiv.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4), 0 4px 6px -2px rgba(239, 68, 68, 0.3); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
          errorDiv.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div style="flex: 1;">
              <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Error de conexión</div>
              <div style="font-size: 0.75rem; opacity: 0.95;">Error al registrar el control. Por favor, inténtelo nuevamente.</div>
            </div>
          `;
          document.body.appendChild(errorDiv);

          setTimeout(() => {
            errorDiv.classList.add('animate-slide-out');
            setTimeout(() => errorDiv.remove(), 300);
          }, 5000);
        });
      }


      function cargarControlesRecienNacido(ninoId) {
        console.log('🔄 Cargando controles recién nacido para ninoId:', ninoId);
        if (!ninoId) {
          console.error('❌ No se proporcionó ninoId');
          return;
        }

        fetch(`{{ route("api.controles-recien-nacido") }}?nino_id=${ninoId}`, {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => {
          console.log('📡 Respuesta controles recién nacido:', response.status);
          return response.json();
        })
        .then(data => {
          console.log('📦 Datos recibidos controles recién nacido:', data);
          if (data.success && data.data && data.data.controles) {
            console.log(`✅ Controles encontrados: ${data.data.controles.length}`);

            // Limpiar primero todos los controles
            for (let num = 1; num <= 4; num++) {
              const fechaEl = document.getElementById(`control-${num}-fecha`);
              const edadEl = document.getElementById(`control-${num}-edad`);
              const estadoEl = document.getElementById(`control-${num}-estado`);

              if (fechaEl) fechaEl.textContent = '-';
              if (edadEl) edadEl.textContent = '-';
              if (estadoEl) {
                estadoEl.className = 'estado-badge estado-seguimiento';
                estadoEl.textContent = 'SEGUIMIENTO';
              }
            }

            // Actualizar con los datos recibidos
            data.data.controles.forEach(control => {
              actualizarControlRecienNacido(control);
            });


            console.log('✅ Controles recién nacido cargados correctamente');
          } else {
            console.warn('⚠️ No se encontraron controles recién nacido');
            // Limpiar todos los controles
            for (let num = 1; num <= 4; num++) {
              const fechaEl = document.getElementById(`control-${num}-fecha`);
              const edadEl = document.getElementById(`control-${num}-edad`);
              const estadoEl = document.getElementById(`control-${num}-estado`);

              if (fechaEl) fechaEl.textContent = '-';
              if (edadEl) edadEl.textContent = '-';
              if (estadoEl) {
                estadoEl.className = 'estado-badge estado-seguimiento';
                estadoEl.textContent = 'SEGUIMIENTO';
              }
            }
          }
        })
        .catch(error => {
          console.error('❌ Error al cargar controles recién nacido:', error);
        });
      }

      // Función para cargar controles CRED mensual
      function cargarControlesCredMensual(ninoId) {
        console.log('🔄 Cargando controles CRED mensual para ninoId:', ninoId);
        if (!ninoId) {
          console.error('❌ No se proporcionó ninoId');
          return;
        }

        // Verificar que el modal esté visible
        const modal = document.getElementById('verControlesModal');
        if (!modal || modal.classList.contains('hidden')) {
          console.warn('⏳ El modal no está visible, esperando...');
          setTimeout(() => cargarControlesCredMensual(ninoId), 300);
          return;
        }

        // Asegurar que el tab CRED esté visible
        const tabCred = document.getElementById('tab-cred');
        if (!tabCred) {
          console.error('❌ No se encontró el tab CRED');
          return;
        }

        fetch(`{{ route("api.controles-cred-mensual") }}?nino_id=${ninoId}`, {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => {
          console.log('📡 Respuesta de API controles CRED mensual:', response.status);
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.json();
        })
        .then(data => {
          console.log('📦 Datos recibidos controles CRED mensual:', data);
          
          // Obtener fecha de nacimiento del API o del modal
          let fechaNacimientoISO = data.data?.fecha_nacimiento || null;
          const controles = data.data?.controles || [];
          
          // Si no viene del API, intentar obtenerla del elemento del modal
          if (!fechaNacimientoISO) {
            const fechaNacimientoHeader = document.getElementById('fechaNacimientoValue');
            if (fechaNacimientoHeader && fechaNacimientoHeader.textContent) {
              // Extraer fecha ISO del texto (formato: "24 de noviembre de 2025 (2025-11-24)")
              const fechaMatch = fechaNacimientoHeader.textContent.match(/\((\d{4}-\d{2}-\d{2})\)/);
              if (fechaMatch) {
                fechaNacimientoISO = fechaMatch[1];
                console.log('✅ Fecha de nacimiento obtenida del modal:', fechaNacimientoISO);
              }
            }
          }
          
          // Si aún no hay fecha, intentar obtenerla de la tabla de niños
          if (!fechaNacimientoISO) {
            const ninoEnTabla = typeof todosLosNinos !== 'undefined' && todosLosNinos ? 
              todosLosNinos.find(n => (n.id === ninoId || n.id_niño === ninoId)) : null;
            if (ninoEnTabla && ninoEnTabla.fecha_nacimiento) {
              const fechaStr = ninoEnTabla.fecha_nacimiento;
              fechaNacimientoISO = fechaStr.includes('T') ? fechaStr.split('T')[0] : fechaStr;
              console.log('✅ Fecha de nacimiento obtenida de la tabla:', fechaNacimientoISO);
            }
          }
          
          // Si aún no hay fecha, mostrar mensaje de error pero mostrar la tabla
          if (!fechaNacimientoISO) {
            console.warn('⚠️ No se encontró fecha de nacimiento, pero se mostrará la tabla');
            // Intentar usar fecha actual como fallback (aunque no sea ideal)
            const hoy = new Date();
            fechaNacimientoISO = hoy.toISOString().split('T')[0];
          }
          
          // Mostrar sección de análisis (siempre)
          const analisisSection = document.getElementById('analisis-cred-mensual');
          if (analisisSection) {
            analisisSection.style.display = 'block';
            analisisSection.style.visibility = 'visible';
            analisisSection.style.opacity = '1';
          }
          
          // Mostrar fecha de nacimiento
          const fechaNacDisplay = document.getElementById('fecha-nacimiento-cred-mensual-display');
          if (fechaNacDisplay && fechaNacimientoISO) {
            try {
              const fechaNac = crearFechaLocal(fechaNacimientoISO);
              const fechaFormateada = fechaNac.toLocaleDateString('es-PE', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
              });
              fechaNacDisplay.textContent = `${fechaFormateada} (${fechaNacimientoISO})`;
            } catch (e) {
              fechaNacDisplay.textContent = fechaNacimientoISO;
            }
          }

          // ========== NUEVA LÓGICA DE EVALUACIÓN CRED MENSUAL ==========
          // Definir rangos de los 11 controles CRED mensuales
          const rangosCredMensual = {
            1: { min: 29, max: 59 },   // Control 1: 29-59 días
            2: { min: 60, max: 89 },   // Control 2: 60-89 días
            3: { min: 90, max: 119 },  // Control 3: 90-119 días
            4: { min: 120, max: 149 }, // Control 4: 120-149 días
            5: { min: 150, max: 179 }, // Control 5: 150-179 días
            6: { min: 180, max: 209 }, // Control 6: 180-209 días
            7: { min: 210, max: 239 }, // Control 7: 210-239 días
            8: { min: 240, max: 269 }, // Control 8: 240-269 días
            9: { min: 270, max: 299 }, // Control 9: 270-299 días
            10: { min: 300, max: 329 }, // Control 10: 300-329 días
            11: { min: 330, max: 359 }  // Control 11: 330-359 días
          };

          // Calcular edad actual del niño en días
          let edadActualDias = 0;
          if (fechaNacimientoISO) {
            try {
              const fechaNac = crearFechaLocal(fechaNacimientoISO);
              const hoy = new Date();
              const diffTime = hoy - fechaNac;
              edadActualDias = Math.floor(diffTime / (1000 * 60 * 60 * 24));
              console.log(`📅 Edad actual del niño: ${edadActualDias} días`);
            } catch (e) {
              console.error('❌ Error al calcular edad actual:', e);
            }
          }

          // Obtener controles registrados del API
          const controlesRegistrados = data.success && data.data && Array.isArray(data.data.controles) 
            ? data.data.controles 
            : [];
          
          console.log(`📋 Controles registrados encontrados: ${controlesRegistrados.length}`);

          // Crear un mapa de controles por número para fácil acceso
          const controlesMap = {};
          controlesRegistrados.forEach(control => {
            const mes = control.numero_control || control.mes;
            if (mes >= 1 && mes <= 11) {
              controlesMap[mes] = control;
            }
          });

          // Variables para contar estados
          let totalCumple = 0;
          let totalNoCumple = 0;
          let totalSeguimiento = 0;

          // Evaluar cada control (1-11) según las 3 reglas
          for (let mes = 1; mes <= 11; mes++) {
            const rango = rangosCredMensual[mes];
            const control = controlesMap[mes];
            
            const fechaElement = document.getElementById(`fo_cred_${mes}`);
            const edadElement = document.getElementById(`edad_cred_${mes}`);
            const estadoElement = document.getElementById(`estado_cred_${mes}`);

            let estadoFinal = 'SEGUIMIENTO'; // Por defecto
            let fechaControlStr = '-';
            let edadDiasStr = '-';
            let edadDiasControl = null;

            // Si HAY control registrado (REGLA 2)
            if (control) {
              // Obtener fecha del control
              if (control.fecha) {
                const fechaControl = crearFechaLocal(control.fecha);
                fechaControlStr = fechaControl.toLocaleDateString('es-PE', {
                  year: 'numeric',
                  month: '2-digit',
                  day: '2-digit'
                });
                
                // Calcular edad en días al momento del control
                const diffTime = fechaControl - crearFechaLocal(fechaNacimientoISO);
                edadDiasControl = Math.floor(diffTime / (1000 * 60 * 60 * 24));
                edadDiasStr = edadDiasControl.toString();
              } else if (control.edad) {
                edadDiasControl = parseInt(control.edad) || null;
                edadDiasStr = control.edad.toString();
              }

              // Verificar si la edad del control está dentro del rango
              if (edadDiasControl !== null) {
                if (edadDiasControl >= rango.min && edadDiasControl <= rango.max) {
                  estadoFinal = 'CUMPLE';
                  totalCumple++;
                } else {
                  // Control registrado pero fuera del rango
                  estadoFinal = 'NO CUMPLE';
                  totalNoCumple++;
                  console.warn(`⚠️ Control ${mes} fuera de rango: ${edadDiasControl} días (rango: ${rango.min}-${rango.max})`);
                }
              } else {
                // Control registrado pero sin edad, marcar como SEGUIMIENTO (datos incompletos)
                estadoFinal = 'SEGUIMIENTO';
                totalSeguimiento++;
              }

            } else {
              // Si NO HAY control registrado (REGLA 3)
              // Verificar si ya pasó el límite máximo del rango
              if (edadActualDias > rango.max) {
                // Ya pasó el límite, NO CUMPLE (falta el control y ya venció)
                estadoFinal = 'NO CUMPLE';
                totalNoCumple++;
                console.warn(`⚠️ Control ${mes} no registrado y ya pasó el límite (máx: ${rango.max} días, actual: ${edadActualDias} días)`);
              } else {
                // Aún está dentro del rango o no ha llegado, EN SEGUIMIENTO
                estadoFinal = 'SEGUIMIENTO';
                totalSeguimiento++;
              }
            }

            // Actualizar elementos en la tabla
            if (fechaElement) fechaElement.textContent = fechaControlStr;
            if (edadElement) edadElement.textContent = edadDiasStr;
            
            if (estadoElement) {
              if (estadoFinal === 'CUMPLE') {
                estadoElement.className = 'estado-badge cumple';
                estadoElement.textContent = 'CUMPLE';
              } else if (estadoFinal === 'NO CUMPLE') {
                estadoElement.className = 'estado-badge no-cumple';
                estadoElement.textContent = 'NO CUMPLE';
              } else {
                estadoElement.className = 'estado-badge estado-seguimiento';
                estadoElement.textContent = 'SEGUIMIENTO';
              }
            }
          }

          // REGLA 1: Verificar si faltan controles
          const controlesRegistradosCount = Object.keys(controlesMap).length;
          if (controlesRegistradosCount < 11) {
            console.warn(`⚠️ REGLA 1: Faltan ${11 - controlesRegistradosCount} controles. Total registrados: ${controlesRegistradosCount}/11`);
            // Nota: Esto ya está contabilizado en totalNoCumple para los controles que faltan y vencieron
          }

          console.log(`✅ Evaluación CRED completada: CUMPLE: ${totalCumple}, NO CUMPLE: ${totalNoCumple}, SEGUIMIENTO: ${totalSeguimiento}`);
          console.log('✅ Controles CRED mensual cargados y tabla actualizada correctamente');
        })
        .catch(error => {
          console.error('❌ Error al cargar controles CRED mensual:', error);
        });
      }

      // Función para cargar tamizaje
      function cargarTamizaje(ninoId) {
        console.log('🔄 Cargando tamizaje para ninoId:', ninoId);
        if (!ninoId) {
          console.error('❌ No se proporcionó ninoId');
          return;
        }

        // Calcular y mostrar la fecha límite (29 días) según la fecha de nacimiento
        try {
          const fechaLimiteEl = document.getElementById('tamizaje-fecha-limite');

          if (fechaLimiteEl) {
            let fechaNacimientoISO = null;

            // 1) Intentar obtenerla del encabezado del modal (fechaNacimientoValue)
            const fechaNacimientoHeader = document.getElementById('fechaNacimientoValue');
            if (fechaNacimientoHeader && fechaNacimientoHeader.textContent) {
              const match = fechaNacimientoHeader.textContent.match(/\((\d{4}-\d{2}-\d{2})\)/);
              if (match) {
                fechaNacimientoISO = match[1];
              } else {
                const directa = fechaNacimientoHeader.textContent.match(/(\d{4}-\d{2}-\d{2})/);
                if (directa) {
                  fechaNacimientoISO = directa[1];
                }
              }
            }

            // 2) Si no, intentar obtenerla desde la lista de niños cargada en memoria
            if (!fechaNacimientoISO && typeof todosLosNinos !== 'undefined' && todosLosNinos) {
              const ninoEnTabla = todosLosNinos.find(n => (n.id === ninoId || n.id_niño === ninoId));
              if (ninoEnTabla && ninoEnTabla.fecha_nacimiento) {
                const fechaStr = ninoEnTabla.fecha_nacimiento;
                fechaNacimientoISO = fechaStr.includes('T') ? fechaStr.split('T')[0] : fechaStr;
              }
            }

            if (fechaNacimientoISO) {
              const fechaNac = crearFechaLocal(fechaNacimientoISO);
              const fechaLimite = new Date(fechaNac.getTime());
              fechaLimite.setDate(fechaLimite.getDate() + 29);

              const fechaFormateada = fechaLimite.toLocaleDateString('es-PE', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
              });

              fechaLimiteEl.textContent = fechaFormateada;
            } else {
              // Fallback si no hay fecha de nacimiento disponible
              fechaLimiteEl.textContent = '-';
            }
          }
        } catch (e) {
          console.warn('No se pudo calcular el rango de 29 días para tamizaje:', e);
        }

        fetch(`{{ route("api.tamizaje") }}?nino_id=${ninoId}`, {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => {
          console.log('📡 Respuesta tamizaje:', response.status);
          return response.json();
        })
        .then(data => {
          console.log('📦 Datos recibidos tamizaje:', data);

          // Limpiar primero
          const fechaTamizaje1 = document.getElementById('fecha-tamizaje-1');
          const edadTamizaje1 = document.getElementById('edad-tamizaje-1');
          const fechaGalen1 = document.getElementById('fecha-tamizaje-galen');
          const edadGalen1 = document.getElementById('edad-tamizaje-galen');
          const cumpleTN = document.getElementById('cumple-tamizaje');

          if (fechaTamizaje1) fechaTamizaje1.textContent = '-';
          if (edadTamizaje1) edadTamizaje1.textContent = '-';
          if (fechaGalen1) fechaGalen1.textContent = '-';
          if (edadGalen1) edadGalen1.textContent = '-';
          if (cumpleTN) {
            cumpleTN.className = 'estado-badge estado-seguimiento';
            cumpleTN.textContent = 'SEGUIMIENTO';
          }

          if (data.success && data.data && data.data.tamizaje) {
            const tamizaje = data.data.tamizaje;
            console.log('✅ Tamizaje encontrado:', tamizaje);

            const fechaTamizaje = tamizaje.fecha_tamizaje ? new Date(tamizaje.fecha_tamizaje + 'T00:00:00').toLocaleDateString('es-PE', {
              year: 'numeric',
              month: '2-digit',
              day: '2-digit'
            }) : '-';
            const edadDias = tamizaje.edad_dias || '-';
            const fechaGalen = tamizaje.fecha_tamizaje_galen ? new Date(tamizaje.fecha_tamizaje_galen + 'T00:00:00').toLocaleDateString('es-PE', {
              year: 'numeric',
              month: '2-digit',
              day: '2-digit'
            }) : '-';
            const edadGalen = tamizaje.edad_dias_galen || '-';
            const cumple = tamizaje.cumple !== null && tamizaje.cumple !== undefined ? tamizaje.cumple : null;

            if (fechaTamizaje1) {
              fechaTamizaje1.textContent = fechaTamizaje;
              fechaTamizaje1.style.color = '#1e293b';
              fechaTamizaje1.style.fontWeight = '500';
              console.log('✅ Fecha tamizaje actualizada:', fechaTamizaje);
            }
            if (edadTamizaje1) {
              edadTamizaje1.textContent = edadDias;
              edadTamizaje1.style.color = '#1e293b';
              edadTamizaje1.style.fontWeight = '500';
              console.log('✅ Edad tamizaje actualizada:', edadDias);
            }
            if (fechaGalen1) {
              fechaGalen1.textContent = fechaGalen;
              fechaGalen1.style.color = '#1e293b';
              fechaGalen1.style.fontWeight = '500';
              console.log('✅ Fecha Galen actualizada:', fechaGalen);
            }
            if (edadGalen1) {
              edadGalen1.textContent = edadGalen;
              edadGalen1.style.color = '#1e293b';
              edadGalen1.style.fontWeight = '500';
              console.log('✅ Edad Galen actualizada:', edadGalen);
            }
            if (cumpleTN) {
              // Determinar si cumple: debe tener fecha_tamizaje y estar en rango válido (1-29 días)
              if (tamizaje.fecha_tamizaje && tamizaje.edad_dias !== null && tamizaje.edad_dias !== undefined) {
                const edadDiasNum = parseInt(tamizaje.edad_dias);
                if (edadDiasNum >= 1 && edadDiasNum <= 29) {
                  cumpleTN.className = 'estado-badge cumple';
                  cumpleTN.textContent = 'CUMPLE';
                  console.log('✅ Cumple TN: CUMPLE (edad en rango válido)');
                } else {
                  cumpleTN.className = 'estado-badge no-cumple';
                  cumpleTN.textContent = 'NO CUMPLE';
                  console.log('⚠️ Cumple TN: NO CUMPLE (edad fuera de rango)');
                }
              } else if (cumple === true || cumple === 1 || cumple === 'si' || cumple === 'cumple') {
                cumpleTN.className = 'estado-badge cumple';
                cumpleTN.textContent = 'CUMPLE';
                console.log('✅ Cumple TN: CUMPLE (según campo cumple)');
              } else if (cumple === false || cumple === 0 || cumple === 'no' || cumple === 'no_cumple') {
                cumpleTN.className = 'estado-badge no-cumple';
                cumpleTN.textContent = 'NO CUMPLE';
                console.log('⚠️ Cumple TN: NO CUMPLE (según campo cumple)');
              } else {
                cumpleTN.className = 'estado-badge estado-seguimiento';
                cumpleTN.textContent = 'SEGUIMIENTO';
                console.log('ℹ️ Cumple TN: SEGUIMIENTO');
              }
            }

            console.log('✅ Tamizaje cargado correctamente');
          } else {
            console.log('ℹ️ No se encontró tamizaje registrado para este niño');
          }
        })
        .catch(error => {
          console.error('❌ Error al cargar tamizaje:', error);
        });
      }

      // Función para cargar visitas
      function cargarVisitas(ninoId) {
        console.log('🔄 Cargando visitas para ninoId:', ninoId);
        if (!ninoId) {
          console.error('❌ No se proporcionó ninoId');
          return Promise.reject('No se proporcionó ninoId');
        }

        return fetch(`{{ route("api.visitas") }}?nino_id=${ninoId}`, {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => {
          console.log('📡 Respuesta visitas:', response.status);
          return response.json();
        })
        .then(data => {
          console.log('📦 Datos recibidos visitas:', data);

          // NO limpiar los estados aquí - se actualizarán según la lógica de validación
          const visitaItems = document.querySelectorAll('#tab-visitas .visita-item');

          // Obtener fecha de nacimiento para validar rangos
          const fechaNacimientoEl = document.getElementById('fecha-nacimiento-visitas');
          let fechaNacimientoISO = null;
          if (fechaNacimientoEl && fechaNacimientoEl.textContent) {
            const fechaMatch = fechaNacimientoEl.textContent.match(/\((\d{4}-\d{2}-\d{2})\)/);
            if (fechaMatch) {
              fechaNacimientoISO = fechaMatch[1];
            }
          }

          // Calcular edad actual
          const hoy = new Date();
          hoy.setHours(0, 0, 0, 0);
          const hoyISO = hoy.getFullYear() + '-' +
                         String(hoy.getMonth() + 1).padStart(2, '0') + '-' +
                         String(hoy.getDate()).padStart(2, '0');
          const edadDiasActual = fechaNacimientoISO ? calcularEdadDias(fechaNacimientoISO, hoyISO) : 0;

          // Rangos por número de control
          const rangosVisitas = {
            1: { min: 28, max: 30 },
            2: { min: 60, max: 150 },
            3: { min: 180, max: 240 },
            4: { min: 270, max: 330 }
          };

          const visitasRegistradas = data.success && data.data && data.data.visitas ? data.data.visitas : [];
          const controlesConVisita = visitasRegistradas.map(v => v.control_de_visita || v.numero_control || v.numero_visitas);

          // Procesar todas las visitas (registradas y no registradas)
          visitaItems.forEach(item => {
            const controlNumero = parseInt(item.getAttribute('data-control-visita')) || parseInt(item.querySelector('.visita-control-numero')?.textContent.trim());
            const estadoBadge = item.querySelector('.estado-badge');
            const button = item.querySelector('.btn-registrar-visita');
            
            if (!controlNumero || !estadoBadge) return;
            
            const rango = rangosVisitas[controlNumero];
            if (!rango) return;

            // Buscar visita por número de control
            const visita = visitasRegistradas.find(v => {
              const vControl = v.control_de_visita || v.numero_control || v.numero_visitas;
              return vControl == controlNumero;
            });
            
            if (visita && visita.fecha_visita && fechaNacimientoISO) {
              // Hay visita registrada - verificar si cumple
              const fechaVisitaISO = visita.fecha_visita;
              const edadDiasVisita = calcularEdadDias(fechaNacimientoISO, fechaVisitaISO);
              const cumpleRango = edadDiasVisita >= rango.min && edadDiasVisita <= rango.max;
              
              const fechaVisita = new Date(fechaVisitaISO + 'T00:00:00').toLocaleDateString('es-PE', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
              });

              if (cumpleRango) {
                estadoBadge.className = 'estado-badge cumple';
                estadoBadge.textContent = 'CUMPLE';
                // Guardar la fecha en un atributo data para que validarRangosYHabilitarBotones pueda acceder a ella
                estadoBadge.setAttribute('data-fecha', fechaVisitaISO);
                // También actualizar el elemento de fecha y edad
                const fechaEl = document.getElementById(`visita-fecha-${controlNumero}`);
                const edadEl = document.getElementById(`visita-edad-${controlNumero}`);
                if (fechaEl) fechaEl.textContent = fechaVisita;
                if (edadEl) edadEl.textContent = edadDiasVisita + ' días';
                console.log(`✅ Visita Control ${controlNumero} CUMPLE: realizada a los ${edadDiasVisita} días (rango: ${rango.min}-${rango.max} días)`);
              } else {
                // Visita registrada pero NO CUMPLE
                estadoBadge.className = 'estado-badge no-cumple';
                estadoBadge.textContent = 'NO CUMPLE';
                // Guardar la fecha en un atributo data para que validarRangosYHabilitarBotones pueda acceder a ella
                estadoBadge.setAttribute('data-fecha', fechaVisitaISO);
                // También actualizar el elemento de fecha y edad
                const fechaEl = document.getElementById(`visita-fecha-${controlNumero}`);
                const edadEl = document.getElementById(`visita-edad-${controlNumero}`);
                if (fechaEl) fechaEl.textContent = fechaVisita;
                if (edadEl) edadEl.textContent = edadDiasVisita + ' días';
                console.log(`❌ Visita Control ${controlNumero} NO CUMPLE: realizada a los ${edadDiasVisita} días (rango: ${rango.min}-${rango.max} días)`);
              }

              if (button) {
                button.innerHTML = `
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                  </svg>
                  Editar
                `;
              }
            } else {
              // NO hay visita registrada - verificar según la edad actual
              if (fechaNacimientoISO && edadDiasActual > rango.max) {
                // YA PASÓ el rango - NO CUMPLE
                estadoBadge.className = 'estado-badge no-cumple';
                estadoBadge.textContent = 'NO CUMPLE';
                estadoBadge.removeAttribute('data-fecha');
                console.log(`❌ Visita Control ${controlNumero} NO CUMPLE: no registrada y ya pasó el rango (edad: ${edadDiasActual} días > rango max: ${rango.max} días)`);
              } else if (fechaNacimientoISO && edadDiasActual < rango.min) {
                // Aún no llega al rango
                estadoBadge.className = 'estado-badge estado-seguimiento';
                estadoBadge.textContent = 'SEGUIMIENTO';
                estadoBadge.removeAttribute('data-fecha');
              } else if (fechaNacimientoISO && edadDiasActual >= rango.min && edadDiasActual <= rango.max) {
                // Dentro del rango - puede registrar
                estadoBadge.className = 'estado-badge estado-seguimiento';
                estadoBadge.textContent = 'SEGUIMIENTO';
                estadoBadge.removeAttribute('data-fecha');
              } else {
                // Sin fecha de nacimiento - mostrar SEGUIMIENTO
                estadoBadge.className = 'estado-badge estado-seguimiento';
                estadoBadge.textContent = 'SEGUIMIENTO';
                estadoBadge.removeAttribute('data-fecha');
              }
              
              // Restaurar botón a estado inicial si no hay visita
              if (button) {
                button.innerHTML = `
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"></path>
                    <path d="M12 5v14"></path>
                  </svg>
                  + Registrar
                `;
              }
            }
          });

          console.log(`✅ Visitas procesadas: ${visitasRegistradas.length} registradas`);
          
          // Ejecutar validación de rangos después de cargar las visitas
          if (typeof validarRangosYHabilitarBotones === 'function') {
            setTimeout(() => validarRangosYHabilitarBotones(ninoId), 500);
          }
        })
        .catch(error => {
          console.error('❌ Error al cargar visitas:', error);
          // Aún así, intentar validar rangos
          if (typeof validarRangosYHabilitarBotones === 'function') {
            setTimeout(() => validarRangosYHabilitarBotones(ninoId), 500);
          }
        });
      }

      // Función para cargar vacunas
      function cargarVacunas(ninoId) {
        console.log('🔄 Cargando vacunas para ninoId:', ninoId);
        if (!ninoId) {
          console.error('❌ No se proporcionó ninoId');
          return;
        }

        fetch(`{{ route("api.vacunas") }}?nino_id=${ninoId}`, {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => {
          console.log('📡 Respuesta vacunas:', response.status);
          return response.json();
        })
        .then(data => {
          console.log('📦 Datos recibidos vacunas:', data);

          // Limpiar primero
          const fechaBcgEl = document.getElementById('fecha-bcg');
          const edadBcgEl = document.getElementById('edad-bcg');
          const estadoBcgEl = document.getElementById('estado-bcg');
          const fechaHvbEl = document.getElementById('fecha-hvb');
          const edadHvbEl = document.getElementById('edad-hvb');
          const estadoHvbEl = document.getElementById('estado-hvb');

          if (fechaBcgEl) fechaBcgEl.textContent = '-';
          if (edadBcgEl) edadBcgEl.textContent = '-';
          if (estadoBcgEl) {
            estadoBcgEl.className = 'estado-badge pendiente';
            estadoBcgEl.textContent = '-';
          }
          if (fechaHvbEl) fechaHvbEl.textContent = '-';
          if (edadHvbEl) edadHvbEl.textContent = '-';
          if (estadoHvbEl) {
            estadoHvbEl.className = 'estado-badge pendiente';
            estadoHvbEl.textContent = '-';
          }

          if (data.success && data.data && data.data.vacunas && data.data.vacunas.length > 0) {
            console.log(`✅ Vacunas encontradas: ${data.data.vacunas.length}`);

            // Buscar BCG y HVB (buscar por nombre exacto o variantes)
            const bcg = data.data.vacunas.find(v => {
              const nombre = (v.nombre_vacuna || '').toUpperCase();
              return nombre === 'BCG' || nombre.includes('BCG') || nombre === 'VACUNAS RN';
            });
            const hvb = data.data.vacunas.find(v => {
              const nombre = (v.nombre_vacuna || '').toUpperCase();
              return nombre === 'HVB' || nombre.includes('HVB') || nombre.includes('HEPATITIS') || nombre.includes('HEP B');
            });

            // Actualizar BCG
            if (bcg) {
              console.log('✅ BCG encontrada:', bcg);
              const fechaBcg = bcg.fecha_aplicacion ? new Date(bcg.fecha_aplicacion + 'T00:00:00').toLocaleDateString('es-PE', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
              }) : '-';
              const edadBcg = bcg.edad_dias || '-';
              const estadoBcg = bcg.estado || 'pendiente';

              if (fechaBcgEl) {
                fechaBcgEl.textContent = fechaBcg;
                fechaBcgEl.style.color = '#1e293b';
                fechaBcgEl.style.fontWeight = '500';
              }
              if (edadBcgEl) {
                edadBcgEl.textContent = edadBcg;
                edadBcgEl.style.color = '#1e293b';
                edadBcgEl.style.fontWeight = '500';
              }
              if (estadoBcgEl) {
                estadoBcgEl.className = `estado-badge ${estadoBcg === 'aplicada' ? 'cumple' : 'pendiente'}`;
                estadoBcgEl.textContent = estadoBcg === 'aplicada' ? 'APLICADA' : 'PENDIENTE';
              }
              console.log(`✅ BCG actualizada: ${fechaBcg}, edad: ${edadBcg}`);
            } else {
              console.log('ℹ️ No se encontró vacuna BCG');
            }

            // Actualizar HVB
            if (hvb) {
              console.log('✅ HVB encontrada:', hvb);
              const fechaHvb = hvb.fecha_aplicacion ? new Date(hvb.fecha_aplicacion + 'T00:00:00').toLocaleDateString('es-PE', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
              }) : '-';
              const edadHvb = hvb.edad_dias || '-';
              const estadoHvb = hvb.estado || 'pendiente';

              if (fechaHvbEl) {
                fechaHvbEl.textContent = fechaHvb;
                fechaHvbEl.style.color = '#1e293b';
                fechaHvbEl.style.fontWeight = '500';
              }
              if (edadHvbEl) {
                edadHvbEl.textContent = edadHvb;
                edadHvbEl.style.color = '#1e293b';
                edadHvbEl.style.fontWeight = '500';
              }
              if (estadoHvbEl) {
                estadoHvbEl.className = `estado-badge ${estadoHvb === 'aplicada' ? 'cumple' : 'pendiente'}`;
                estadoHvbEl.textContent = estadoHvb === 'aplicada' ? 'APLICADA' : 'PENDIENTE';
              }
              console.log(`✅ HVB actualizada: ${fechaHvb}, edad: ${edadHvb}`);
            } else {
              console.log('ℹ️ No se encontró vacuna HVB');
            }

            // Actualizar cumplimiento general de vacunas RN
            const cumpleVacunasRN = document.getElementById('cumple-vacunas-rn');
            if (cumpleVacunasRN) {
              const tieneBcg = bcg && bcg.estado === 'aplicada';
              const tieneHvb = hvb && hvb.estado === 'aplicada';

              if (tieneBcg && tieneHvb) {
                cumpleVacunasRN.className = 'estado-badge cumple';
                cumpleVacunasRN.textContent = 'CUMPLE';
                console.log('✅ Cumplimiento vacunas RN: CUMPLE');
              } else {
                cumpleVacunasRN.className = 'estado-badge no-cumple';
                cumpleVacunasRN.textContent = 'NO CUMPLE';
                console.log('⚠️ Cumplimiento vacunas RN: NO CUMPLE');
              }
            }

            console.log('✅ Vacunas cargadas correctamente');
          } else {
            console.log('ℹ️ No hay vacunas registradas para este niño');
            // Actualizar cumplimiento a NO CUMPLE si no hay vacunas
            const cumpleVacunasRN = document.getElementById('cumple-vacunas-rn');
            if (cumpleVacunasRN) {
              cumpleVacunasRN.className = 'estado-badge no-cumple';
              cumpleVacunasRN.textContent = 'NO CUMPLE';
            }
          }
        })
        .catch(error => {
          console.error('❌ Error al cargar vacunas:', error);
        });
      }

      // Función para cargar CNV
      function cargarCNV(ninoId) {
        console.log('🔄 Cargando CNV para ninoId:', ninoId);
        if (!ninoId) {
          console.error('❌ No se proporcionó ninoId');
          return;
        }

        fetch(`{{ route("api.cnv") }}?nino_id=${ninoId}`, {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => {
          console.log('📡 Respuesta CNV:', response.status);
          return response.json();
        })
        .then(data => {
          console.log('📦 Datos recibidos CNV:', data);
          if (data.success && data.data && data.data.cnv) {
            const cnv = data.data.cnv;
            console.log('✅ CNV encontrado:', cnv);
            const infoCard = document.querySelector('#tab-cnv .info-card');

            if (infoCard) {
              const infoRows = infoCard.querySelectorAll('.info-row');
              infoRows.forEach((row, index) => {
                const label = row.querySelector('label');
                const span = row.querySelector('span');

                if (label && span) {
                  const labelText = label.textContent.trim();

                  if (labelText.includes('Peso')) {
                    if (cnv.peso || cnv.peso_nacer) {
                      const peso = cnv.peso || cnv.peso_nacer;
                      span.textContent = peso + ' g';
                      span.style.color = '#1e293b';
                      span.style.fontWeight = '600';
                    } else {
                      span.textContent = 'No registrado';
                      span.style.color = '#64748b';
                    }
                  } else if (labelText.includes('Edad gestacional')) {
                    if (cnv.edad_gestacional) {
                      span.textContent = cnv.edad_gestacional + ' semanas';
                      span.style.color = '#1e293b';
                      span.style.fontWeight = '600';
                    } else {
                      span.textContent = '-';
                      span.style.color = '#64748b';
                    }
                  } else if (labelText.includes('Clasificación')) {
                    if (cnv.clasificacion) {
                      // Convertir clasificación a formato legible
                      let clasificacionTexto = cnv.clasificacion;
                      if (clasificacionTexto === 'normal') clasificacionTexto = 'NORMAL';
                      else if (clasificacionTexto === 'bajo_peso') clasificacionTexto = 'BAJO PESO';
                      else if (clasificacionTexto === 'prematuro') clasificacionTexto = 'PREMATURO';
                      else if (clasificacionTexto === 'prematuro_bajo_peso') clasificacionTexto = 'PREMATURO Y BAJO PESO';
                      else if (clasificacionTexto === 'postermino') clasificacionTexto = 'POSTÉRMINO';

                      span.textContent = clasificacionTexto;
                      span.className = 'estado-badge cumple';
                      console.log('✅ Clasificación actualizada:', clasificacionTexto);
                    } else {
                      span.textContent = 'PENDIENTE';
                      span.className = 'estado-badge pendiente';
                    }
                  }
                }
              });
            }
            console.log('✅ CNV cargado correctamente');
          } else {
            console.log('ℹ️ No se encontró CNV registrado para este niño');
            // Limpiar campos
            const infoCard = document.querySelector('#tab-cnv .info-card');
            if (infoCard) {
              const infoRows = infoCard.querySelectorAll('.info-row');
              infoRows.forEach((row) => {
                const label = row.querySelector('label');
                const span = row.querySelector('span');
                if (label && span) {
                  const labelText = label.textContent.trim();
                  if (labelText.includes('Peso')) {
                    span.textContent = 'No registrado';
                    span.style.color = '#64748b';
                  } else if (labelText.includes('Edad gestacional')) {
                    span.textContent = '-';
                    span.style.color = '#64748b';
                  } else if (labelText.includes('Clasificación')) {
                    span.textContent = 'PENDIENTE';
                    span.className = 'estado-badge pendiente';
                  }
                }
              });
            }
          }
        })
        .catch(error => {
          console.error('❌ Error al cargar CNV:', error);
        });
      }

      // ========== FUNCIÓN PARA EVALUAR Y MOSTRAR ALERTAS ==========
      function evaluarAlertas(ninoId, nombre, dni, establecimiento) {
        // Obtener datos del niño para calcular edad
        fetch(`{{ route("api.nino.datos-extras") }}?documento=${dni}`, {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success && data.data) {
            const nino = data.data;
            const fechaNacimientoStr = nino.fecha_nacimiento;
            const fechaNacimiento = crearFechaLocal(fechaNacimientoStr);
            const hoy = new Date();
            hoy.setHours(0, 0, 0, 0);
            const edadDias = Math.floor((hoy - fechaNacimiento) / (1000 * 60 * 60 * 24));
            const edadMeses = Math.floor(edadDias / 30);

            // Actualizar fecha de nacimiento en el modal si aún no se ha actualizado
            if (nino.fecha_nacimiento) {
              const fechaISO = formatearFechaISO(fechaNacimientoStr);
              const fechaNacimientoLocal = crearFechaLocal(fechaNacimientoStr);
              const fechaFormateada = fechaNacimientoLocal.toLocaleDateString('es-PE', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
              });

              const fechaNacimientoHeader = document.getElementById('fechaNacimientoValue');
              if (fechaNacimientoHeader && (!fechaNacimientoHeader.textContent || fechaNacimientoHeader.textContent === '-')) {
                fechaNacimientoHeader.textContent = fechaFormateada + ' (' + fechaISO + ')';
              }

              const fechaNacimientoControl = document.getElementById('fecha-nacimiento-control-recien-nacido');
              if (fechaNacimientoControl && (!fechaNacimientoControl.textContent || fechaNacimientoControl.textContent === '-')) {
                fechaNacimientoControl.textContent = fechaFormateada + ' (' + fechaISO + ')';
              }

              const fechaNacimientoCredMensual = document.getElementById('fecha-nacimiento-cred-mensual');
              if (fechaNacimientoCredMensual && (!fechaNacimientoCredMensual.textContent || fechaNacimientoCredMensual.textContent === '-')) {
                fechaNacimientoCredMensual.textContent = fechaFormateada + ' (' + fechaISO + ')';
              }

              const fechaNacimientoTamizaje = document.getElementById('fecha-nacimiento-tamizaje');
              if (fechaNacimientoTamizaje && (!fechaNacimientoTamizaje.textContent || fechaNacimientoTamizaje.textContent === '-')) {
                fechaNacimientoTamizaje.textContent = fechaFormateada + ' (' + fechaISO + ')';
              }

              const fechaNacimientoVisitas = document.getElementById('fecha-nacimiento-visitas');
              if (fechaNacimientoVisitas && (!fechaNacimientoVisitas.textContent || fechaNacimientoVisitas.textContent === '-')) {
                fechaNacimientoVisitas.textContent = fechaFormateada + ' (' + fechaISO + ')';
              }

              const fechaNacimientoVacunas = document.getElementById('fecha-nacimiento-vacunas');
              if (fechaNacimientoVacunas && (!fechaNacimientoVacunas.textContent || fechaNacimientoVacunas.textContent === '-')) {
                fechaNacimientoVacunas.textContent = fechaFormateada + ' (' + fechaISO + ')';
              }
            }

            // Datos del paciente para mostrar en alertas
            const datosPaciente = `${nombre} | DNI: ${dni} | Edad: ${edadDias} días (${edadMeses} meses) | Establecimiento: ${establecimiento || 'No registrado'}`;

            // Evaluar Control del Recién Nacido
            evaluarControlRecienNacido(ninoId, datosPaciente, edadDias);

            // Evaluar CRED Mensual
            evaluarCredMensual(ninoId, datosPaciente, edadDias);

            // Evaluar Tamizaje Neonatal
            evaluarTamizaje(ninoId, datosPaciente, edadDias);

            // Evaluar Visitas Domiciliarias
            evaluarVisitasDomiciliarias(ninoId, datosPaciente, edadDias);

            // Evaluar Vacunas
            evaluarVacunas(ninoId, datosPaciente, edadDias);

            // Mostrar sección de alertas si hay alguna alerta visible
            const alertasSection = document.getElementById('alertasSection');
            const tieneAlertas = Array.from(document.querySelectorAll('.alerta-item')).some(item =>
              item.style.display !== 'none'
            );
            if (tieneAlertas && alertasSection) {
              alertasSection.style.display = 'block';
            }
          }
        })
        .catch(error => {
          console.error('Error al evaluar alertas:', error);
        });
      }

      function evaluarControlRecienNacido(ninoId, datosPaciente, edadDias) {
        fetch(`{{ route("api.controles-recien-nacido") }}?nino_id=${ninoId}`, {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => response.json())
        .then(data => {
          const alerta = document.getElementById('alertaRecienNacido');
          if (!alerta) return;

          if (edadDias <= 28) {
            const rangos = [
              { num: 1, min: 2, max: 6, desc: 'Verifica adaptación y lactancia' },
              { num: 2, min: 7, max: 13, desc: 'Seguimiento del peso y signos de alarma' },
              { num: 3, min: 14, max: 20, desc: 'Evaluación del crecimiento y orientación al cuidador' },
              { num: 4, min: 21, max: 28, desc: 'Confirmación final del estado de salud neonatal' }
            ];

            if (data.success && data.data.cumplimiento) {
              const cumplimiento = data.data.cumplimiento;
              const controles = data.data.controles || [];

              if (cumplimiento.cumple) {
                alerta.style.display = 'none';
              } else {
                alerta.style.display = 'block';
                document.getElementById('datosPacienteRecienNacido').textContent = datosPaciente;
                document.getElementById('estadoRecienNacido').innerHTML = '<span class="badge-alerta no-cumple">NO CUMPLE</span>';

                const faltantes = [];
                const fueraRango = [];

                rangos.forEach(rango => {
                  const control = controles.find(c => c.numero_control === rango.num);
                  if (!control) {
                    faltantes.push(`Control ${rango.num} (${rango.min}-${rango.max} días)`);
                  } else {
                    const edadDiasControl = parseInt(control.edad_dias) || 0;
                    if (edadDiasControl < rango.min || edadDiasControl > rango.max) {
                      fueraRango.push(`Control ${rango.num} realizado a los ${edadDiasControl} días (rango: ${rango.min}-${rango.max})`);
                    }
                  }
                });

                let error = '';
                if (faltantes.length > 0) {
                  error += `Faltan ${faltantes.length} controles: ${faltantes.join(', ')}. `;
                }
                if (fueraRango.length > 0) {
                  error += `Controles fuera de rango: ${fueraRango.join(', ')}.`;
                }

                document.getElementById('errorRecienNacido').textContent = error || 'No se cumplen los 4 controles requeridos.';

                let sugerencia = 'Se requieren 4 controles en los siguientes rangos: ';
                sugerencia += rangos.map(r => `Control ${r.num}: ${r.min}-${r.max} días (${r.desc})`).join('; ');
                sugerencia += '. Programe las citas según la edad del niño.';

                document.getElementById('sugerenciaRecienNacido').textContent = sugerencia;
              }
            } else {
              // Si no hay controles registrados
              alerta.style.display = 'block';
              document.getElementById('datosPacienteRecienNacido').textContent = datosPaciente;
              document.getElementById('estadoRecienNacido').innerHTML = '<span class="badge-alerta pendiente">PENDIENTE</span>';
              document.getElementById('errorRecienNacido').textContent = 'No se han registrado controles del recién nacido.';

              let sugerencia = 'El niño necesita 4 controles en los siguientes rangos: ';
              sugerencia += rangos.map(r => `Control ${r.num}: ${r.min}-${r.max} días`).join('; ');
              sugerencia += '. Registre cada control según la edad del niño.';
              document.getElementById('sugerenciaRecienNacido').textContent = sugerencia;
            }
          } else {
            alerta.style.display = 'none';
          }
        })
        .catch(error => {
          console.error('Error al evaluar control recién nacido:', error);
        });
      }

      function evaluarCredMensual(ninoId, datosPaciente, edadDias) {
        const alerta = document.getElementById('alertaCredMensual');
        if (!alerta) return;

        if (edadDias >= 29 && edadDias <= 359) {
          // Obtener controles CRED mensuales desde la API
          fetch(`{{ route("api.controles-cred-mensual") }}?nino_id=${ninoId}`, {
            method: 'GET',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
          })
          .then(response => response.json())
          .then(data => {
            const rangosCred = {
              1: { min: 29, max: 59 }, 2: { min: 60, max: 89 }, 3: { min: 90, max: 119 },
              4: { min: 120, max: 149 }, 5: { min: 150, max: 179 }, 6: { min: 180, max: 209 },
              7: { min: 210, max: 239 }, 8: { min: 240, max: 269 }, 9: { min: 270, max: 299 },
              10: { min: 300, max: 329 }, 11: { min: 330, max: 359 }
            };

            const controles = data.success && data.data && data.data.controles ? data.data.controles : [];
            const fechaNacimientoISO = data.data && data.data.fecha_nacimiento ? data.data.fecha_nacimiento : null;
            
            const controlesNoCumplen = [];
            const controlesFaltantes = [];
            let tieneControlesNoCumplen = false;

            // Verificar cada control esperado
            Object.keys(rangosCred).forEach(numControl => {
              const num = parseInt(numControl);
              const rango = rangosCred[num];
              const control = controles.find(c => c.numero_control === num);
              
              if (control && control.fecha && fechaNacimientoISO) {
                // Calcular edad en días al momento del control
                const edadDiasControl = calcularEdadDias(fechaNacimientoISO, control.fecha);
                if (edadDiasControl < rango.min || edadDiasControl > rango.max) {
                  controlesNoCumplen.push(`Mes ${num} (realizado a los ${edadDiasControl} días, rango: ${rango.min}-${rango.max} días)`);
                  tieneControlesNoCumplen = true;
                }
              } else if (edadDias > rango.max) {
                // Ya pasó el rango y no hay control registrado
                controlesFaltantes.push(`Mes ${num} (rango: ${rango.min}-${rango.max} días)`);
                tieneControlesNoCumplen = true;
              }
            });

            if (tieneControlesNoCumplen || controlesFaltantes.length > 0) {
              alerta.style.display = 'block';
              document.getElementById('datosPacienteCred').textContent = datosPaciente;
              
              let error = '';
              if (controlesNoCumplen.length > 0) {
                error += `Controles fuera de rango: ${controlesNoCumplen.join('; ')}. `;
              }
              if (controlesFaltantes.length > 0) {
                error += `Controles faltantes: ${controlesFaltantes.join('; ')}. `;
              }
              
              document.getElementById('estadoCredMensual').innerHTML = '<span class="badge-alerta no-cumple">NO CUMPLE</span>';
              document.getElementById('errorCredMensual').textContent = error || 'Hay controles CRED mensuales que no cumplen con los rangos establecidos.';
              document.getElementById('sugerenciaCredMensual').textContent = 'Los controles CRED mensuales deben realizarse dentro de los rangos establecidos: Mes 1 (29-59 días), Mes 2 (60-89 días), y así sucesivamente hasta el Mes 11 (330-359 días).';
            } else if (controles.length < 11 && edadDias >= 29) {
              alerta.style.display = 'block';
              document.getElementById('datosPacienteCred').textContent = datosPaciente;
              document.getElementById('estadoCredMensual').innerHTML = '<span class="badge-alerta seguimiento">SEGUIMIENTO</span>';
              document.getElementById('errorCredMensual').textContent = `Se requieren 11 controles mensuales. Actualmente hay ${controles.length} registrado(s).`;
              document.getElementById('sugerenciaCredMensual').textContent = 'Programe controles mensuales según la edad: Mes 1 (29-59 días), Mes 2 (60-89 días), y así sucesivamente hasta el Mes 11 (330-359 días).';
            } else {
              alerta.style.display = 'none';
            }
          })
          .catch(error => {
            console.error('Error al evaluar controles CRED mensual:', error);
            // En caso de error, mostrar alerta genérica
            alerta.style.display = 'block';
            document.getElementById('datosPacienteCred').textContent = datosPaciente;
            document.getElementById('estadoCredMensual').innerHTML = '<span class="badge-alerta seguimiento">SEGUIMIENTO</span>';
            document.getElementById('errorCredMensual').textContent = 'Se requieren 11 controles mensuales desde los 29 días hasta los 11 meses y 29 días.';
            document.getElementById('sugerenciaCredMensual').textContent = 'Programe controles mensuales según la edad: Mes 1 (29-59 días), Mes 2 (60-89 días), y así sucesivamente hasta el Mes 11 (330-359 días).';
          });
        } else {
          alerta.style.display = 'none';
        }
      }

      function evaluarTamizaje(ninoId, datosPaciente, edadDias) {
        const alerta = document.getElementById('alertaTamizaje');
        if (!alerta) return;

        if (edadDias >= 1 && edadDias <= 29) {
          // TODO: Verificar si se realizó el tamizaje
          alerta.style.display = 'block';
          document.getElementById('datosPacienteTamizaje').textContent = datosPaciente;
          document.getElementById('estadoTamizaje').innerHTML = '<span class="badge-alerta pendiente">PENDIENTE</span>';
          document.getElementById('errorTamizaje').textContent = 'El tamizaje neonatal debe realizarse entre el día 1 y el día 29 de vida.';
          document.getElementById('sugerenciaTamizaje').textContent = 'Realice el tamizaje neonatal idealmente entre los 2 y 5 días de vida del recién nacido para detectar precozmente enfermedades metabólicas, endocrinas y genéticas.';
        } else if (edadDias > 29 && edadDias <= 60) {
          // Pasó el periodo pero aún es reciente
          alerta.style.display = 'block';
          document.getElementById('datosPacienteTamizaje').textContent = datosPaciente;
          document.getElementById('estadoTamizaje').innerHTML = '<span class="badge-alerta no-cumple">NO CUMPLE</span>';
          document.getElementById('errorTamizaje').textContent = 'El periodo recomendado para el tamizaje neonatal (día 1-29) ha pasado.';
          document.getElementById('sugerenciaTamizaje').textContent = 'Aunque el periodo ideal ha pasado, aún puede realizarse el tamizaje. Consulte con el médico especialista sobre la posibilidad de realizarlo.';
        } else {
          alerta.style.display = 'none';
        }
      }

      function evaluarVisitasDomiciliarias(ninoId, datosPaciente, edadDias) {
        const alerta = document.getElementById('alertaVisitas');
        if (!alerta) return;

        if (edadDias <= 365) {
          // Obtener visitas registradas desde la API
          fetch(`{{ route("api.visitas") }}?nino_id=${ninoId}`, {
            method: 'GET',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
          })
          .then(response => response.json())
          .then(data => {
            const rangosVisitas = {
              1: { min: 28, max: 30 },
              2: { min: 60, max: 150 },
              3: { min: 180, max: 240 },
              4: { min: 270, max: 330 }
            };

            const visitas = data.success && data.data && data.data.visitas ? data.data.visitas : [];
            const visitasNoCumplen = [];
            const visitasFaltantes = [];
            let tieneVisitasNoCumplen = false;

            // Obtener fecha de nacimiento para calcular edades
            const fechaNacimientoEl = document.getElementById('fecha-nacimiento-visitas');
            let fechaNacimientoISO = null;
            if (fechaNacimientoEl && fechaNacimientoEl.textContent) {
              const fechaMatch = fechaNacimientoEl.textContent.match(/\((\d{4}-\d{2}-\d{2})\)/);
              if (fechaMatch) {
                fechaNacimientoISO = fechaMatch[1];
              }
            }

            // Verificar cada control de visita (1-4)
            Object.keys(rangosVisitas).forEach(controlNumero => {
              const controlNum = parseInt(controlNumero);
              const rango = rangosVisitas[controlNum];
              // Buscar visita por número de control
              const visita = visitas.find(v => {
                const vControl = v.control_de_visita || v.numero_control || v.numero_visitas;
                return vControl == controlNum;
              });
              
              if (visita && visita.fecha_visita && fechaNacimientoISO) {
                // Calcular edad en días al momento de la visita
                const edadDiasVisita = calcularEdadDias(fechaNacimientoISO, visita.fecha_visita);
                if (edadDiasVisita < rango.min || edadDiasVisita > rango.max) {
                  visitasNoCumplen.push(`Control ${controlNum} (realizada a los ${edadDiasVisita} días, rango: ${rango.min}-${rango.max} días)`);
                  tieneVisitasNoCumplen = true;
                }
              } else if (edadDias > rango.max) {
                // Ya pasó el rango y no hay visita registrada
                visitasFaltantes.push(`Control ${controlNum} (rango: ${rango.min}-${rango.max} días)`);
                tieneVisitasNoCumplen = true;
              }
            });

            if (tieneVisitasNoCumplen || visitasFaltantes.length > 0) {
              alerta.style.display = 'block';
              document.getElementById('datosPacienteVisitas').textContent = datosPaciente;
              
              let error = '';
              if (visitasNoCumplen.length > 0) {
                error += `Visitas fuera de rango: ${visitasNoCumplen.join('; ')}. `;
              }
              if (visitasFaltantes.length > 0) {
                error += `Visitas faltantes: ${visitasFaltantes.join('; ')}. `;
              }
              
              document.getElementById('estadoVisitas').innerHTML = '<span class="badge-alerta no-cumple">NO CUMPLE</span>';
              document.getElementById('errorVisitas').textContent = error || 'Hay visitas domiciliarias que no cumplen con los rangos establecidos.';
              document.getElementById('sugerenciaVisitas').textContent = 'Las visitas domiciliarias deben realizarse dentro de los rangos establecidos: 28 días (28-35 días), 2-5 meses (60-150 días), 6-8 meses (180-240 días) y 9-11 meses (270-330 días).';
            } else if (visitas.length < 2) {
              alerta.style.display = 'block';
              document.getElementById('datosPacienteVisitas').textContent = datosPaciente;
              document.getElementById('estadoVisitas').innerHTML = '<span class="badge-alerta seguimiento">SEGUIMIENTO</span>';
              document.getElementById('errorVisitas').textContent = `Se requiere al menos 2 visitas domiciliarias. Actualmente hay ${visitas.length} registrada(s).`;
              document.getElementById('sugerenciaVisitas').textContent = 'Programe visitas domiciliarias en: 28 días de vida, 2-5 meses, 6-8 meses y 9-11 meses. Mínimo 2 visitas son requeridas para cumplir.';
            } else {
              alerta.style.display = 'none';
            }
          })
          .catch(error => {
            console.error('Error al evaluar visitas domiciliarias:', error);
            // En caso de error, mostrar alerta genérica
            alerta.style.display = 'block';
            document.getElementById('datosPacienteVisitas').textContent = datosPaciente;
            document.getElementById('estadoVisitas').innerHTML = '<span class="badge-alerta seguimiento">SEGUIMIENTO</span>';
            document.getElementById('errorVisitas').textContent = 'Se requiere al menos 2 visitas domiciliarias durante el primer año de vida.';
            document.getElementById('sugerenciaVisitas').textContent = 'Programe visitas domiciliarias en: 28 días de vida, 2-5 meses, 6-8 meses y 9-11 meses. Mínimo 2 visitas son requeridas para cumplir.';
          });
        } else {
          alerta.style.display = 'none';
        }
      }

      function evaluarVacunas(ninoId, datosPaciente, edadDias) {
        const alerta = document.getElementById('alertaVacunas');
        if (!alerta) return;

        if (edadDias <= 2) {
          // TODO: Verificar vacunas registradas
          alerta.style.display = 'block';
          document.getElementById('datosPacienteVacunas').textContent = datosPaciente;
          document.getElementById('estadoVacunas').innerHTML = '<span class="badge-alerta pendiente">PENDIENTE</span>';
          document.getElementById('errorVacunas').textContent = 'Faltan vacunas del recién nacido: BCG y Hepatitis B (HvB).';
          document.getElementById('sugerenciaVacunas').textContent = 'Aplique la vacuna BCG (previene formas graves de tuberculosis) y Hepatitis B (HvB) (protege contra infección por virus de hepatitis B) durante el periodo neonatal.';
        } else {
          alerta.style.display = 'none';
        }
      }
    });

    // ========== FUNCIONES PARA CARGAR DATOS SIMULADOS (PARA PRUEBAS) ==========
    function cargarDatosSimulados(ninoId) {
      console.log('🔄 Cargando datos simulados para ninoId:', ninoId);

      // Obtener fecha de nacimiento del niño
      const fechaNacimientoText = document.getElementById('fechaNacimientoValue')?.textContent;
      if (!fechaNacimientoText || fechaNacimientoText === '-') {
        console.warn('⚠️ No se encontró fecha de nacimiento para calcular fechas simuladas');
        return;
      }

      // Extraer fecha ISO
      const fechaMatch = fechaNacimientoText.match(/\((\d{4}-\d{2}-\d{2})\)/);
      if (!fechaMatch) {
        const fechaDirecta = fechaNacimientoText.match(/(\d{4}-\d{2}-\d{2})/);
        if (!fechaDirecta) {
          console.warn('⚠️ No se pudo extraer la fecha ISO');
          return;
        }
        var fechaNacimientoISO = fechaDirecta[1];
      } else {
        var fechaNacimientoISO = fechaMatch[1];
      }

      const fechaNacimiento = crearFechaLocal(fechaNacimientoISO);
      console.log('📅 Fecha de nacimiento para simulación:', fechaNacimientoISO);

      setTimeout(() => {
        // Rangos para controles recién nacido - usar valores dentro del rango
        const rangosRecienNacido = {
          1: { min: 2, max: 6, edadEjemplo: 3 }, // Día 3 (dentro de 2-6)
          2: { min: 7, max: 13, edadEjemplo: 9 }, // Día 9 (dentro de 7-13)
          3: { min: 14, max: 20, edadEjemplo: 16 }, // Día 16 (dentro de 14-20)
          4: { min: 21, max: 28, edadEjemplo: 23 } // Día 23 (dentro de 21-28)
        };

        // 1. Simular datos de controles recién nacido basados en fecha de nacimiento
        const controlesSimulados = [];
        for (let numControl = 1; numControl <= 4; numControl++) {
          const rango = rangosRecienNacido[numControl];
          // Verificar que la edad esté dentro del rango
          if (rango.edadEjemplo >= rango.min && rango.edadEjemplo <= rango.max) {
            // Calcular fecha del control basada en la fecha de nacimiento + edad ejemplo
            const fechaControl = new Date(fechaNacimiento);
            fechaControl.setDate(fechaControl.getDate() + rango.edadEjemplo);

            const fechaControlISO = fechaControl.getFullYear() + '-' +
                                    String(fechaControl.getMonth() + 1).padStart(2, '0') + '-' +
                                    String(fechaControl.getDate()).padStart(2, '0');

            controlesSimulados.push({
              numero: numControl,
              fecha: fechaControlISO,
              edad: rango.edadEjemplo,
              estado: 'cumple'
            });
          }
        }

        controlesSimulados.forEach(control => {
          const fechaEl = document.getElementById(`control-${control.numero}-fecha`);
          const edadEl = document.getElementById(`control-${control.numero}-edad`);
          const estadoEl = document.getElementById(`control-${control.numero}-estado`);

          if (fechaEl) {
            const fecha = crearFechaLocal(control.fecha);
            fechaEl.textContent = fecha.toLocaleDateString('es-PE', {
              year: 'numeric',
              month: '2-digit',
              day: '2-digit'
            });
            fechaEl.style.color = '#1e293b';
            fechaEl.style.fontWeight = '500';
          }
          if (edadEl) {
            edadEl.textContent = control.edad;
            edadEl.style.color = '#1e293b';
            edadEl.style.fontWeight = '500';
          }
          if (estadoEl) {
            estadoEl.className = 'estado-badge cumple';
            estadoEl.textContent = 'CUMPLE';
          }
        });

        // 2. Simular datos de CRED mensual basados en fecha de nacimiento - usar valores dentro del rango
        const rangosCredMensual = {
          1: { min: 29, max: 59, edadEjemplo: 35 }, // Día 35 (dentro de 29-59)
          2: { min: 60, max: 89, edadEjemplo: 65 }, // Día 65 (dentro de 60-89)
          3: { min: 90, max: 119, edadEjemplo: 95 }, // Día 95 (dentro de 90-119)
          4: { min: 120, max: 149, edadEjemplo: 125 }, // Día 125 (dentro de 120-149)
          5: { min: 150, max: 179, edadEjemplo: 155 }, // Día 155 (dentro de 150-179)
          6: { min: 180, max: 209, edadEjemplo: 185 }, // Día 185 (dentro de 180-209)
          7: { min: 210, max: 239, edadEjemplo: 215 }, // Día 215 (dentro de 210-239)
          8: { min: 240, max: 269, edadEjemplo: 245 }, // Día 245 (dentro de 240-269)
          9: { min: 270, max: 299, edadEjemplo: 275 }, // Día 275 (dentro de 270-299)
          10: { min: 300, max: 329, edadEjemplo: 305 }, // Día 305 (dentro de 300-329)
          11: { min: 330, max: 359, edadEjemplo: 335 } // Día 335 (dentro de 330-359)
        };

        const credSimulados = [];
        for (let mes = 1; mes <= 11; mes++) {
          const rango = rangosCredMensual[mes];
          // Verificar que la edad esté dentro del rango
          if (rango.edadEjemplo >= rango.min && rango.edadEjemplo <= rango.max) {
            // Calcular fecha del control basada en la fecha de nacimiento + edad ejemplo
            const fechaControl = new Date(fechaNacimiento);
            fechaControl.setDate(fechaControl.getDate() + rango.edadEjemplo);

            const fechaControlISO = fechaControl.getFullYear() + '-' +
                                    String(fechaControl.getMonth() + 1).padStart(2, '0') + '-' +
                                    String(fechaControl.getDate()).padStart(2, '0');

            credSimulados.push({
              mes: mes,
              fecha: fechaControlISO,
              edad: rango.edadEjemplo,
              estado: 'cumple'
            });
          }
        }

        credSimulados.forEach(control => {
          const fechaEl = document.getElementById(`fo_cred_${control.mes}`);
          const edadEl = document.getElementById(`edad_cred_${control.mes}`);
          const estadoEl = document.getElementById(`estado_cred_${control.mes}`);

          if (fechaEl) {
            const fecha = crearFechaLocal(control.fecha);
            fechaEl.textContent = fecha.toLocaleDateString('es-PE', {
              year: 'numeric',
              month: '2-digit',
              day: '2-digit'
            });
            fechaEl.style.color = '#1e293b';
            fechaEl.style.fontWeight = '500';
          }
          if (edadEl) {
            edadEl.textContent = control.edad;
            edadEl.style.color = '#1e293b';
            edadEl.style.fontWeight = '500';
          }
          if (estadoEl) {
            estadoEl.className = 'estado-badge cumple';
            estadoEl.textContent = 'CUMPLE';
          }
        });

        // 3. Simular tamizaje basado en fecha de nacimiento (rango 1-29 días)
        const fechaTamizaje = document.getElementById('fecha-tamizaje-1');
        const edadTamizaje = document.getElementById('edad-tamizaje-1');
        const cumpleTamizaje = document.getElementById('cumple-tamizaje');

        // Tamizaje a los 3 días de vida (dentro del rango 1-29)
        const edadTamizajeDias = 3; // Día 3 está dentro del rango 1-29
        if (edadTamizajeDias >= 1 && edadTamizajeDias <= 29) {
          const fechaTamizajeObj = new Date(fechaNacimiento);
          fechaTamizajeObj.setDate(fechaTamizajeObj.getDate() + edadTamizajeDias);
          const fechaTamizajeISO = fechaTamizajeObj.getFullYear() + '-' +
                                   String(fechaTamizajeObj.getMonth() + 1).padStart(2, '0') + '-' +
                                   String(fechaTamizajeObj.getDate()).padStart(2, '0');

          if (fechaTamizaje) {
            const fecha = crearFechaLocal(fechaTamizajeISO);
            fechaTamizaje.textContent = fecha.toLocaleDateString('es-PE', {
              year: 'numeric',
              month: '2-digit',
              day: '2-digit'
            });
            fechaTamizaje.style.color = '#1e293b';
            fechaTamizaje.style.fontWeight = '500';
          }
          if (edadTamizaje) {
            edadTamizaje.textContent = edadTamizajeDias.toString();
            edadTamizaje.style.color = '#1e293b';
            edadTamizaje.style.fontWeight = '500';
          }
          if (cumpleTamizaje) {
            cumpleTamizaje.className = 'estado-badge cumple';
            cumpleTamizaje.textContent = 'CUMPLE';
          }
        }

        // 4. Simular vacunas basadas en fecha de nacimiento (día 0, dentro del rango permitido)
        const fechaBcg = document.getElementById('fecha-bcg');
        const edadBcg = document.getElementById('edad-bcg');
        const estadoBcg = document.getElementById('estado-bcg');

        // BCG al nacer (día 0) - dentro del rango permitido para vacunas RN
        const edadVacunaDias = 0; // Día 0 está dentro del rango para vacunas RN
        const fechaVacunaObj = new Date(fechaNacimiento);
        const fechaVacunaISO = fechaVacunaObj.getFullYear() + '-' +
                               String(fechaVacunaObj.getMonth() + 1).padStart(2, '0') + '-' +
                               String(fechaVacunaObj.getDate()).padStart(2, '0');

        if (fechaBcg) {
          const fecha = crearFechaLocal(fechaVacunaISO);
          fechaBcg.textContent = fecha.toLocaleDateString('es-PE', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
          });
          fechaBcg.style.color = '#1e293b';
          fechaBcg.style.fontWeight = '500';
        }
        if (edadBcg) {
          edadBcg.textContent = edadVacunaDias.toString();
          edadBcg.style.color = '#1e293b';
          edadBcg.style.fontWeight = '500';
        }
        if (estadoBcg) {
          estadoBcg.className = 'estado-badge cumple';
          estadoBcg.textContent = 'APLICADA';
        }

        const fechaHvb = document.getElementById('fecha-hvb');
        const edadHvb = document.getElementById('edad-hvb');
        const estadoHvb = document.getElementById('estado-hvb');

        // HVB al nacer (día 0) - misma fecha que BCG
        if (fechaHvb) {
          const fecha = crearFechaLocal(fechaVacunaISO);
          fechaHvb.textContent = fecha.toLocaleDateString('es-PE', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
          });
          fechaHvb.style.color = '#1e293b';
          fechaHvb.style.fontWeight = '500';
        }
        if (edadHvb) {
          edadHvb.textContent = edadVacunaDias.toString();
          edadHvb.style.color = '#1e293b';
          edadHvb.style.fontWeight = '500';
        }
        if (estadoHvb) {
          estadoHvb.className = 'estado-badge cumple';
          estadoHvb.textContent = 'APLICADA';
        }

        const cumpleVacunas = document.getElementById('cumple-vacunas-rn');
        if (cumpleVacunas) {
          cumpleVacunas.className = 'estado-badge cumple';
          cumpleVacunas.textContent = 'CUMPLE';
        }

        // 5. Simular datos de CNV (Recién Nacido)
        const infoCardCNV = document.querySelector('#tab-cnv .info-card');
        if (infoCardCNV) {
          const infoRows = infoCardCNV.querySelectorAll('.info-row');
          infoRows.forEach((row) => {
            const label = row.querySelector('label');
            const span = row.querySelector('span');
            if (label && span) {
              const labelText = label.textContent.trim();
              if (labelText.includes('Peso')) {
                // Peso al nacer normal: entre 2500g y 4000g
                const pesoNacer = 3200; // 3.2 kg (peso normal)
                span.textContent = pesoNacer + ' g';
                span.style.color = '#1e293b';
                span.style.fontWeight = '600';
              } else if (labelText.includes('Edad gestacional')) {
                // Edad gestacional normal: entre 37-42 semanas
                const edadGestacional = 39; // 39 semanas (término)
                span.textContent = edadGestacional + ' semanas';
                span.style.color = '#1e293b';
                span.style.fontWeight = '600';
              } else if (labelText.includes('Clasificación')) {
                // Clasificación según peso y edad gestacional
                // Para peso 3200g y 39 semanas = A término, peso adecuado
                span.className = 'estado-badge cumple';
                span.textContent = 'A TÉRMINO';
              }
            }
          });
        }

        // 6. Simular visitas domiciliarias basadas en fecha de nacimiento
        const visitasSimuladas = [
          { controlNumero: 1, edadDias: 28, fechaId: 'visita-fecha-1', estadoId: 'visita-estado-1' },
          { controlNumero: 2, edadDias: 90, fechaId: 'visita-fecha-2', estadoId: 'visita-estado-2' }, // 3 meses = 90 días
          { controlNumero: 3, edadDias: 210, fechaId: 'visita-fecha-3', estadoId: 'visita-estado-3' }, // 7 meses = 210 días
          { controlNumero: 4, edadDias: 300, fechaId: 'visita-fecha-4', estadoId: 'visita-estado-4' } // 10 meses = 300 días
        ];

        visitasSimuladas.forEach(visita => {
          const fechaVisitaObj = new Date(fechaNacimiento);
          fechaVisitaObj.setDate(fechaVisitaObj.getDate() + visita.edadDias);
          const fechaVisitaISO = fechaVisitaObj.getFullYear() + '-' +
                                 String(fechaVisitaObj.getMonth() + 1).padStart(2, '0') + '-' +
                                 String(fechaVisitaObj.getDate()).padStart(2, '0');

          const fechaEl = document.getElementById(visita.fechaId);
          const estadoEl = document.getElementById(visita.estadoId);

          if (fechaEl) {
            const fecha = crearFechaLocal(fechaVisitaISO);
            fechaEl.textContent = fecha.toLocaleDateString('es-PE', {
              year: 'numeric',
              month: '2-digit',
              day: '2-digit'
            });
            fechaEl.style.color = '#1e293b';
            fechaEl.style.fontWeight = '500';
          }

          if (estadoEl) {
            estadoEl.className = 'estado-badge cumple';
            estadoEl.textContent = 'CUMPLE';
          }
        });

        console.log('✅ Datos simulados cargados en todas las secciones (incluyendo CNV y visitas domiciliarias)');
      }, 500);
    }

    // Función mejorada para cargar datos desde el controlador (lógica centralizada)
    function cargarDatosControles(ninoId) {
      if (!ninoId) {
        console.warn('⚠️ No se proporcionó ninoId');
        return Promise.reject('No se proporcionó ninoId');
      }

      console.log('🔄 Cargando todos los controles desde el controlador para ninoId:', ninoId);

      // Usar el endpoint consolidado que centraliza toda la lógica en el controlador
      return fetch(`/api/nino/${ninoId}/controles`, {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
      })
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        if (data.success && data.data) {
          console.log('✅ Datos recibidos del controlador:', data);

          // Procesar datos del controlador
          procesarDatosDelControlador(data.data);
          return data; // Devolver datos para que la promesa se resuelva
        } else {
          console.warn('⚠️ No se recibieron datos válidos');
          return data; // Devolver datos incluso si no son válidos
        }
      })
      .catch(error => {
        console.error('❌ Error al cargar datos desde el controlador:', error);
        // Solo usar datos simulados si hay un error de conexión real
        // Si no hay datos en la BD, simplemente mostrar tablas vacías
        console.log('ℹ️ No se pudieron cargar datos desde la base de datos. Verifique la conexión.');
        console.log('ℹ️ Si el niño no tiene controles registrados, las tablas estarán vacías (comportamiento esperado).');
        // NO cargar datos simulados automáticamente - solo mostrar tablas vacías
        // Devolver una promesa resuelta para que el .then() continúe
        return Promise.resolve({ success: false, error: error.message, data: null });
      });
    }

    // Función para procesar los datos recibidos del controlador
    function procesarDatosDelControlador(datos) {
      // 1. Actualizar información del niño
      if (datos.nino) {
        const fechaNacimientoHeader = document.getElementById('fechaNacimientoValue');
        if (fechaNacimientoHeader && datos.nino.fecha_nacimiento) {
          const fechaNacimientoStr = datos.nino.fecha_nacimiento;
          const fechaISO = formatearFechaISO(fechaNacimientoStr);
          const fecha = crearFechaLocal(fechaNacimientoStr);
          const fechaFormateada = fecha.toLocaleDateString('es-PE', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
          });
          fechaNacimientoHeader.textContent = fechaFormateada + ' (' + fechaISO + ')';
        }
      }

      // 1.1. Guardar datos extras en variable global para usar cuando se abra el modal
      if (datos.datos_extra) {
        window.datosExtrasActuales = datos.datos_extra;
      }

      // 2. Procesar controles recién nacido
      console.log('🔄 Procesando controles recién nacido:', datos.controles_recien_nacido);
      
      // Definir rangos de los 4 controles recién nacido
      const rangosRecienNacido = {
        1: { min: 2, max: 6 },   // Control 1: 2-6 días
        2: { min: 7, max: 13 },  // Control 2: 7-13 días
        3: { min: 14, max: 20 }, // Control 3: 14-20 días
        4: { min: 21, max: 28 }  // Control 4: 21-28 días
      };

      // Calcular edad actual del niño en días
      let edadActualDias = 0;
      if (datos.nino && datos.nino.fecha_nacimiento) {
        try {
          const fechaNac = crearFechaLocal(datos.nino.fecha_nacimiento);
          const hoy = new Date();
          const diffTime = hoy - fechaNac;
          edadActualDias = Math.floor(diffTime / (1000 * 60 * 60 * 24));
          console.log(`📅 Edad actual del niño: ${edadActualDias} días`);
        } catch (e) {
          console.error('❌ Error al calcular edad actual:', e);
        }
      }

      // Obtener controles registrados
      const controlesRegistrados = datos.controles_recien_nacido && datos.controles_recien_nacido.controles && Array.isArray(datos.controles_recien_nacido.controles)
        ? datos.controles_recien_nacido.controles
        : [];
      
      console.log(`📋 Controles registrados encontrados: ${controlesRegistrados.length}`);

      // Crear un mapa de controles por número para fácil acceso
      const controlesMap = {};
      controlesRegistrados.forEach(control => {
        const num = control.numero_control;
        if (num >= 1 && num <= 4) {
          controlesMap[num] = control;
        }
      });

      // Procesar cada control (1-4)
      for (let num = 1; num <= 4; num++) {
        const rango = rangosRecienNacido[num];
        const control = controlesMap[num];
        
        const fechaElement = document.getElementById(`control-${num}-fecha`);
        const edadElement = document.getElementById(`control-${num}-edad`);
        const estadoElement = document.getElementById(`control-${num}-estado`);

        let fechaControlStr = '-';
        let edadDiasStr = '-';
        let estadoFinal = 'SEGUIMIENTO';
        let edadDiasControl = null;

        // Si HAY control registrado
        if (control) {
          // Obtener fecha del control
          if (control.fecha_control || control.fecha) {
            try {
              const fechaStr = control.fecha_control || control.fecha;
              const fechaControl = crearFechaLocal(fechaStr);
              fechaControlStr = fechaControl.toLocaleDateString('es-PE', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
              });
              
              // Calcular edad en días al momento del control
              if (datos.nino && datos.nino.fecha_nacimiento) {
                const fechaNac = crearFechaLocal(datos.nino.fecha_nacimiento);
                const diffTime = fechaControl - fechaNac;
                edadDiasControl = Math.floor(diffTime / (1000 * 60 * 60 * 24));
                edadDiasStr = edadDiasControl.toString();
              } else if (control.edad_dias || control.edad) {
                edadDiasControl = parseInt(control.edad_dias || control.edad);
                edadDiasStr = edadDiasControl.toString();
              }
            } catch (e) {
              console.error(`❌ Error al procesar fecha del control ${num}:`, e);
            }
          } else if (control.edad_dias || control.edad) {
            edadDiasControl = parseInt(control.edad_dias || control.edad);
            edadDiasStr = edadDiasControl.toString();
          }

          // Verificar si la edad del control está dentro del rango
          if (edadDiasControl !== null) {
            if (edadDiasControl >= rango.min && edadDiasControl <= rango.max) {
              estadoFinal = 'CUMPLE';
            } else {
              estadoFinal = 'NO CUMPLE';
              console.warn(`⚠️ Control ${num} fuera de rango: ${edadDiasControl} días (rango: ${rango.min}-${rango.max})`);
            }
          } else {
            // Si no se puede calcular la edad
            if (fechaControlStr !== '-') {
              // Hay fecha registrada pero no se pudo calcular edad
              // Intentar usar el estado del backend, pero si no está disponible, validar según edad actual
              if (control.estado === 'cumple') {
                estadoFinal = 'CUMPLE';
              } else if (control.estado === 'no_cumple' || control.estado === 'no cumple') {
                estadoFinal = 'NO CUMPLE';
              } else {
                // Si no hay estado del backend, validar según edad actual
                if (edadActualDias > rango.max) {
                  estadoFinal = 'NO CUMPLE';
                  console.warn(`⚠️ Control ${num} con fecha pero sin edad calculada y ya pasó el límite (máx: ${rango.max} días, actual: ${edadActualDias} días)`);
                } else {
                  estadoFinal = 'SEGUIMIENTO';
                }
              }
            } else {
              // No hay fecha registrada, siempre mostrar SEGUIMIENTO
              // NO CUMPLE solo se muestra cuando hay una fecha registrada que está fuera del rango
              estadoFinal = 'SEGUIMIENTO';
            }
          }

          console.log(`✅ Control ${num} registrado: ${fechaControlStr} (${edadDiasStr} días) - ${estadoFinal}`);
        } else {
          // Si NO HAY control registrado, siempre mostrar SEGUIMIENTO
          // NO CUMPLE solo se muestra cuando hay una fecha registrada que está fuera del rango
          estadoFinal = 'SEGUIMIENTO';
        }

        // Actualizar elementos en la tabla
        if (fechaElement) {
          fechaElement.textContent = fechaControlStr;
          if (fechaControlStr !== '-') {
            fechaElement.style.color = '#1e293b';
            fechaElement.style.fontWeight = '500';
          }
        }
        
        if (edadElement) {
          edadElement.textContent = edadDiasStr !== '-' ? edadDiasStr + ' días' : '-';
          if (edadDiasStr !== '-') {
            edadElement.style.color = '#1e293b';
            edadElement.style.fontWeight = '500';
          }
        }
        
        if (estadoElement) {
          if (estadoFinal === 'CUMPLE') {
            estadoElement.className = 'estado-badge cumple';
            estadoElement.textContent = 'CUMPLE';
          } else if (estadoFinal === 'NO CUMPLE') {
            estadoElement.className = 'estado-badge no-cumple';
            estadoElement.textContent = 'NO CUMPLE';
          } else {
            estadoElement.className = 'estado-badge estado-seguimiento';
            estadoElement.textContent = 'SEGUIMIENTO';
          }
        }
      }
      

      console.log('✅ Controles recién nacido procesados correctamente');

      // 2.1. Actualizar fecha de nacimiento en tab de recién nacido
      if (datos.nino && datos.nino.fecha_nacimiento) {
        const fechaNacimientoControlRN = document.getElementById('fecha-nacimiento-control-recien-nacido');
        if (fechaNacimientoControlRN) {
          const fechaNacStr = datos.nino.fecha_nacimiento;
          const fechaISO = formatearFechaISO(fechaNacStr);
          const fecha = crearFechaLocal(fechaNacStr);
          const fechaFormateada = fecha.toLocaleDateString('es-PE', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
          });
          fechaNacimientoControlRN.textContent = fechaFormateada + ' (' + fechaISO + ')';
        }
      }

      // 3. Procesar controles CRED mensual
      // Rangos para CRED mensual
      const rangosCredMensual = {
        1: { min: 29, max: 59 },
        2: { min: 60, max: 89 },
        3: { min: 90, max: 119 },
        4: { min: 120, max: 149 },
        5: { min: 150, max: 179 },
        6: { min: 180, max: 209 },
        7: { min: 210, max: 239 },
        8: { min: 240, max: 269 },
        9: { min: 270, max: 299 },
        10: { min: 300, max: 329 },
        11: { min: 330, max: 359 }
      };
      
      if (datos.controles_cred_mensual && datos.controles_cred_mensual.controles) {
        console.log('🔄 Procesando controles CRED mensual:', datos.controles_cred_mensual.controles);
        
        datos.controles_cred_mensual.controles.forEach(control => {
          // Usar numero_control en lugar de mes
          const mes = control.numero_control || control.mes;
          if (!mes || mes < 1 || mes > 11) {
            console.warn('⚠️ Control CRED con número inválido:', control);
            return;
          }
          
          const fechaEl = document.getElementById(`fo_cred_${mes}`);
          const edadEl = document.getElementById(`edad_cred_${mes}`);
          const estadoEl = document.getElementById(`estado_cred_${mes}`);

          // Usar fecha en lugar de fecha_control
          const fechaControl = control.fecha || control.fecha_control;
          
          if (fechaEl && fechaControl) {
            try {
              const fecha = crearFechaLocal(fechaControl);
              fechaEl.textContent = fecha.toLocaleDateString('es-PE', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
              });
              fechaEl.style.color = '#1e293b';
              fechaEl.style.fontWeight = '500';
            } catch (e) {
              console.error(`❌ Error al formatear fecha del control CRED ${mes}:`, e);
              fechaEl.textContent = fechaControl;
            }
          }
          
          // Calcular edad en días si tenemos fecha de nacimiento y fecha del control
          let edadDias = control.edad_dias || control.edad;
          if (!edadDias && datos.nino && datos.nino.fecha_nacimiento && fechaControl) {
            try {
              const fechaNac = crearFechaLocal(datos.nino.fecha_nacimiento);
              const fechaControlObj = crearFechaLocal(fechaControl);
              const diffTime = fechaControlObj - fechaNac;
              edadDias = Math.floor(diffTime / (1000 * 60 * 60 * 24));
            } catch (e) {
              console.error(`❌ Error al calcular edad del control CRED ${mes}:`, e);
            }
          }
          
          if (edadEl) {
            edadEl.textContent = edadDias ? edadDias.toString() : '-';
            if (edadDias) {
              edadEl.style.color = '#1e293b';
              edadEl.style.fontWeight = '500';
            } else {
              edadEl.style.color = '#64748b';
            }
          }
          
          if (estadoEl) {
            const rango = rangosCredMensual[mes];
            let estadoFinal = control.estado || 'SEGUIMIENTO';
            
            // Si no hay fecha registrada, siempre mostrar SEGUIMIENTO
            // NO CUMPLE solo se muestra cuando hay una fecha registrada que está fuera del rango
            if (!fechaControl) {
              estadoFinal = 'SEGUIMIENTO';
            }
            
            // Aplicar el estado final
            if (estadoFinal === 'CUMPLE' || estadoFinal === 'cumple') {
              estadoEl.className = 'estado-badge cumple';
              estadoEl.textContent = 'CUMPLE';
            } else if (estadoFinal === 'NO CUMPLE' || estadoFinal === 'no_cumple' || estadoFinal === 'no cumple') {
              estadoEl.className = 'estado-badge no-cumple';
              estadoEl.textContent = 'NO CUMPLE';
            } else {
              estadoEl.className = 'estado-badge estado-seguimiento';
              estadoEl.textContent = 'SEGUIMIENTO';
            }
          }
          
          console.log(`✅ Control CRED ${mes} procesado: fecha=${fechaControl}, edad=${edadDias}, estado=${control.estado}`);
        });
      }
      

      // 4. Procesar tamizaje
      const rangoTamizajeMin = 1;
      const rangoTamizajeMax = 29;

      // Calcular edad actual del niño en días
      let edadActualDiasTamizaje = 0;
      if (datos.nino && datos.nino.fecha_nacimiento) {
        try {
          const fechaNacTamizaje = crearFechaLocal(datos.nino.fecha_nacimiento);
          const hoyTamizaje = new Date();
          const diffTimeTamizaje = hoyTamizaje - fechaNacTamizaje;
          edadActualDiasTamizaje = Math.floor(diffTimeTamizaje / (1000 * 60 * 60 * 24));
          console.log(`📅 Edad actual del niño para tamizaje: ${edadActualDiasTamizaje} días`);
        } catch (e) {
          console.error('❌ Error al calcular edad actual para tamizaje:', e);
        }
      }

      const fechaTamizajeEl = document.getElementById('fecha-tamizaje-1');
      const edadTamizajeEl = document.getElementById('edad-tamizaje-1');
      const cumpleTamizajeEl = document.getElementById('cumple-tamizaje');

      // Procesar tamizaje neonatal (fecha_tam_neo)
      const fechaTamizajeNeo = datos.tamizaje && datos.tamizaje.fecha_tam_neo ? datos.tamizaje.fecha_tam_neo : null;
      const fechaTamizajeGalen = datos.tamizaje && datos.tamizaje.galen_fecha_tam_feo ? datos.tamizaje.galen_fecha_tam_feo : null;
      
      if (fechaTamizajeNeo && datos.nino && datos.nino.fecha_nacimiento) {
        try {
          const fechaTamizaje = crearFechaLocal(fechaTamizajeNeo);
          const fechaNacTamizaje = crearFechaLocal(datos.nino.fecha_nacimiento);
          const fechaFormateada = fechaTamizaje.toLocaleDateString('es-PE', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
          });

          // Calcular edad en días al momento del tamizaje
          const diffTime = fechaTamizaje - fechaNacTamizaje;
          const edadDiasTamizaje = Math.floor(diffTime / (1000 * 60 * 60 * 24));

          if (fechaTamizajeEl) {
            fechaTamizajeEl.textContent = fechaFormateada;
            fechaTamizajeEl.style.color = '#1e293b';
            fechaTamizajeEl.style.fontWeight = '500';
          }

          if (edadTamizajeEl) {
            edadTamizajeEl.textContent = edadDiasTamizaje + ' días';
            edadTamizajeEl.style.color = '#1e293b';
            edadTamizajeEl.style.fontWeight = '500';
          }

          if (cumpleTamizajeEl) {
            // Evaluar si cumple con el rango (1-29 días)
            if (edadDiasTamizaje >= rangoTamizajeMin && edadDiasTamizaje <= rangoTamizajeMax) {
              cumpleTamizajeEl.className = 'estado-badge cumple';
              cumpleTamizajeEl.textContent = 'CUMPLE';
            } else {
              cumpleTamizajeEl.className = 'estado-badge no-cumple';
              cumpleTamizajeEl.textContent = 'NO CUMPLE';
            }
          }

          console.log(`✅ Tamizaje Neonatal: ${fechaFormateada} (${edadDiasTamizaje} días) - ${edadDiasTamizaje >= rangoTamizajeMin && edadDiasTamizaje <= rangoTamizajeMax ? 'CUMPLE' : 'NO CUMPLE'}`);
        } catch (e) {
          console.error('❌ Error al procesar tamizaje:', e);
        }
      }
      
      // Procesar tamizaje Galen (galen_fecha_tam_feo)
      const fechaTamizajeGalenEl = document.getElementById('fecha-tamizaje-galen');
      const edadTamizajeGalenEl = document.getElementById('edad-tamizaje-galen');
      const cumpleTamizajeGalenEl = document.getElementById('cumple-tamizaje-galen');
      
      if (fechaTamizajeGalen && datos.nino && datos.nino.fecha_nacimiento) {
        try {
          const fechaGalen = crearFechaLocal(fechaTamizajeGalen);
          const fechaNacGalen = crearFechaLocal(datos.nino.fecha_nacimiento);
          const fechaFormateadaGalen = fechaGalen.toLocaleDateString('es-PE', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
          });

          // Calcular edad en días al momento del tamizaje Galen
          const diffTimeGalen = fechaGalen - fechaNacGalen;
          const edadDiasGalen = Math.floor(diffTimeGalen / (1000 * 60 * 60 * 24));

          if (fechaTamizajeGalenEl) {
            fechaTamizajeGalenEl.textContent = fechaFormateadaGalen;
            fechaTamizajeGalenEl.style.color = '#1e293b';
            fechaTamizajeGalenEl.style.fontWeight = '500';
          }

          if (edadTamizajeGalenEl) {
            edadTamizajeGalenEl.textContent = edadDiasGalen + ' días';
            edadTamizajeGalenEl.style.color = '#1e293b';
            edadTamizajeGalenEl.style.fontWeight = '500';
          }

          if (cumpleTamizajeGalenEl) {
            // Evaluar si cumple con el rango (1-29 días)
            // Si tiene datos y está dentro del rango, siempre mostrar CUMPLE
            if (edadDiasGalen >= rangoTamizajeMin && edadDiasGalen <= rangoTamizajeMax) {
              cumpleTamizajeGalenEl.className = 'estado-badge cumple';
              cumpleTamizajeGalenEl.textContent = 'CUMPLE';
              console.log(`✅ Tamizaje Galen: ${fechaFormateadaGalen} (${edadDiasGalen} días) - CUMPLE (dentro del rango ${rangoTamizajeMin}-${rangoTamizajeMax} días)`);
            } else {
              cumpleTamizajeGalenEl.className = 'estado-badge no-cumple';
              cumpleTamizajeGalenEl.textContent = 'NO CUMPLE';
              console.log(`⚠️ Tamizaje Galen: ${fechaFormateadaGalen} (${edadDiasGalen} días) - NO CUMPLE (fuera del rango ${rangoTamizajeMin}-${rangoTamizajeMax} días)`);
            }
          }
        } catch (e) {
          console.error('❌ Error al procesar tamizaje Galen:', e);
        }
      } else {
        // Validar tamizaje Galen sin fecha registrada
        if (cumpleTamizajeGalenEl) {
          // Solo mostrar NO CUMPLE si ya pasó el rango máximo y no hay fecha registrada
          if (edadActualDiasTamizaje > rangoTamizajeMax) {
            cumpleTamizajeGalenEl.className = 'estado-badge no-cumple';
            cumpleTamizajeGalenEl.textContent = 'NO CUMPLE';
          } else {
            cumpleTamizajeGalenEl.className = 'estado-badge estado-seguimiento';
            cumpleTamizajeGalenEl.textContent = 'SEGUIMIENTO';
          }
          console.log(`ℹ️ Tamizaje Galen: No registrado, edad actual: ${edadActualDiasTamizaje} días → ${edadActualDiasTamizaje > rangoTamizajeMax ? 'NO CUMPLE' : 'SEGUIMIENTO'}`);
        }
      }
      
      // Validar tamizaje Neonatal sin fecha registrada
      if (!fechaTamizajeNeo) {
        // Si NO hay tamizaje registrado, siempre mostrar SEGUIMIENTO
        // NO CUMPLE solo se muestra cuando hay una fecha registrada que está fuera del rango
        if (cumpleTamizajeEl) {
          cumpleTamizajeEl.className = 'estado-badge estado-seguimiento';
          cumpleTamizajeEl.textContent = 'SEGUIMIENTO';
          console.log(`ℹ️ Tamizaje Neonatal: No registrado, edad actual: ${edadActualDiasTamizaje} días → SEGUIMIENTO`);
        }
      }

      // 5. Procesar vacunas
      if (datos.nino && datos.nino.fecha_nacimiento) {
        const fechaNacimientoStr = datos.nino.fecha_nacimiento;
        const fechaNacimiento = crearFechaLocal(fechaNacimientoStr);
        const rangoMin = 0; // Mínimo 0 días
        const rangoMax = 2; // Máximo 2 días

        // Calcular edad actual del niño en días
        const hoy = new Date();
        const diffTimeHoy = hoy - fechaNacimiento;
        const edadActualDias = Math.floor(diffTimeHoy / (1000 * 60 * 60 * 24));

        // Variables para rastrear si hay vacunas registradas
        let tieneBcg = false;
        let tieneHvb = false;

        // Procesar vacunas registradas
        if (datos.vacunas && datos.vacunas.length > 0) {
          datos.vacunas.forEach(vacuna => {
            const nombre = (vacuna.nombre_vacuna || '').toUpperCase();
            const esBcg = nombre === 'BCG' || nombre.includes('BCG');
            const esHvb = nombre === 'HVB' || nombre.includes('HVB') || nombre.includes('HEPATITIS');

            if (esBcg && vacuna.fecha_aplicacion) {
              tieneBcg = true;
              const fechaBcg = document.getElementById('fecha-bcg');
              const edadBcg = document.getElementById('edad-bcg');
              const estadoBcg = document.getElementById('estado-bcg');

              try {
                const fechaAplicacion = crearFechaLocal(vacuna.fecha_aplicacion);
                const fechaFormateada = fechaAplicacion.toLocaleDateString('es-PE', {
                  year: 'numeric',
                  month: '2-digit',
                  day: '2-digit'
                });

                // Calcular edad en días al momento de la aplicación
                const diffTime = fechaAplicacion - fechaNacimiento;
                const edadDias = Math.floor(diffTime / (1000 * 60 * 60 * 24));

                if (fechaBcg) {
                  fechaBcg.textContent = fechaFormateada;
                  fechaBcg.style.color = '#1e293b';
                  fechaBcg.style.fontWeight = '500';
                }

                if (edadBcg) {
                  edadBcg.textContent = edadDias + ' días';
                  edadBcg.style.color = '#1e293b';
                  edadBcg.style.fontWeight = '500';
                }

                if (estadoBcg) {
                  // Evaluar si cumple con el rango (0-2 días)
                  if (edadDias >= rangoMin && edadDias <= rangoMax) {
                    estadoBcg.className = 'estado-badge cumple';
                    estadoBcg.textContent = 'CUMPLE';
                  } else {
                    estadoBcg.className = 'estado-badge no-cumple';
                    estadoBcg.textContent = 'NO CUMPLE';
                  }
                }

                console.log(`✅ Vacuna BCG: ${fechaFormateada} (${edadDias} días) - ${edadDias >= rangoMin && edadDias <= rangoMax ? 'CUMPLE' : 'NO CUMPLE'}`);
              } catch (e) {
                console.error('❌ Error al procesar vacuna BCG:', e);
              }
            }

            if (esHvb && vacuna.fecha_aplicacion) {
              tieneHvb = true;
              const fechaHvb = document.getElementById('fecha-hvb');
              const edadHvb = document.getElementById('edad-hvb');
              const estadoHvb = document.getElementById('estado-hvb');

              try {
                const fechaAplicacion = crearFechaLocal(vacuna.fecha_aplicacion);
                const fechaFormateada = fechaAplicacion.toLocaleDateString('es-PE', {
                  year: 'numeric',
                  month: '2-digit',
                  day: '2-digit'
                });

                // Calcular edad en días al momento de la aplicación
                const diffTime = fechaAplicacion - fechaNacimiento;
                const edadDias = Math.floor(diffTime / (1000 * 60 * 60 * 24));

                if (fechaHvb) {
                  fechaHvb.textContent = fechaFormateada;
                  fechaHvb.style.color = '#1e293b';
                  fechaHvb.style.fontWeight = '500';
                }

                if (edadHvb) {
                  edadHvb.textContent = edadDias + ' días';
                  edadHvb.style.color = '#1e293b';
                  edadHvb.style.fontWeight = '500';
                }

                if (estadoHvb) {
                  // Evaluar si cumple con el rango (0-2 días)
                  if (edadDias >= rangoMin && edadDias <= rangoMax) {
                    estadoHvb.className = 'estado-badge cumple';
                    estadoHvb.textContent = 'CUMPLE';
                  } else {
                    estadoHvb.className = 'estado-badge no-cumple';
                    estadoHvb.textContent = 'NO CUMPLE';
                  }
                }

                console.log(`✅ Vacuna HVB: ${fechaFormateada} (${edadDias} días) - ${edadDias >= rangoMin && edadDias <= rangoMax ? 'CUMPLE' : 'NO CUMPLE'}`);
              } catch (e) {
                console.error('❌ Error al procesar vacuna HVB:', e);
              }
            }
          });
        }

        // Si no hay vacunas registradas, evaluar según la edad actual
        const estadoBcg = document.getElementById('estado-bcg');
        const estadoHvb = document.getElementById('estado-hvb');

        // Evaluar BCG - si no está registrada, siempre mostrar PENDIENTE
        // NO CUMPLE solo se muestra cuando hay una fecha registrada que está fuera del rango
        if (!tieneBcg && estadoBcg) {
          estadoBcg.className = 'estado-badge estado-seguimiento';
          estadoBcg.textContent = 'PENDIENTE';
          console.log(`ℹ️ Vacuna BCG: No registrada, edad actual: ${edadActualDias} días → PENDIENTE`);
        }

        // Evaluar HVB - si no está registrada, siempre mostrar PENDIENTE
        // NO CUMPLE solo se muestra cuando hay una fecha registrada que está fuera del rango
        if (!tieneHvb && estadoHvb) {
          estadoHvb.className = 'estado-badge estado-seguimiento';
          estadoHvb.textContent = 'PENDIENTE';
          console.log(`ℹ️ Vacuna HVB: No registrada, edad actual: ${edadActualDias} días → PENDIENTE`);
        }
      }

      // 6. Procesar CNV
      if (datos.cnv) {
        const infoCard = document.querySelector('#tab-cnv .info-card');
        if (infoCard) {
          const infoRows = infoCard.querySelectorAll('.info-row');
          infoRows.forEach((row) => {
            // Buscar el texto de la etiqueta en el primer TD (que contiene el SVG y texto)
            const firstTd = row.querySelector('td:first-child');
            const secondTd = row.querySelector('td:last-child');
            const span = secondTd ? secondTd.querySelector('span') : null;
            
            if (firstTd && span) {
              const labelText = firstTd.textContent.trim().toLowerCase();
              
              if (labelText.includes('peso')) {
                // El peso viene en kg desde la BD, pero mostrar en gramos para CNV
                const pesoKg = datos.cnv.peso_nacer || datos.cnv.peso;
                const pesoGramos = pesoKg ? Math.round(pesoKg * 1000) : null;
                span.textContent = pesoGramos ? pesoGramos + ' g' : 'No registrado';
                span.style.color = pesoGramos ? '#1e293b' : '#64748b';
                span.style.fontWeight = pesoGramos ? '600' : '400';
                console.log(`✅ CNV Peso actualizado: ${pesoGramos} g`);
              } else if (labelText.includes('edad gestacional') || labelText.includes('gestacional')) {
                span.textContent = datos.cnv.edad_gestacional ? datos.cnv.edad_gestacional + ' semanas' : '-';
                span.style.color = datos.cnv.edad_gestacional ? '#1e293b' : '#64748b';
                span.style.fontWeight = datos.cnv.edad_gestacional ? '600' : '400';
                console.log(`✅ CNV Edad gestacional actualizada: ${datos.cnv.edad_gestacional} semanas`);
              } else if (labelText.includes('clasificación') || labelText.includes('clasificacion')) {
                if (datos.cnv.clasificacion) {
                  span.className = 'estado-badge cumple';
                  span.textContent = datos.cnv.clasificacion;
                  console.log(`✅ CNV Clasificación actualizada: ${datos.cnv.clasificacion}`);
                } else {
                  span.className = 'estado-badge pendiente';
                  span.textContent = 'PENDIENTE';
                }
              }
            }
          });
        }
      } else {
        console.log('ℹ️ No se encontraron datos CNV para este niño');
      }

      // 6.1. Actualizar fecha de nacimiento en tab de CNV
      if (datos.nino && datos.nino.fecha_nacimiento) {
        const fechaNacimientoCNV = document.getElementById('fecha-nacimiento-cnv');
        if (fechaNacimientoCNV) {
          const fechaNacStr = datos.nino.fecha_nacimiento;
          const fechaISO = formatearFechaISO(fechaNacStr);
          const fecha = crearFechaLocal(fechaNacStr);
          const fechaFormateada = fecha.toLocaleDateString('es-PE', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
          });
          fechaNacimientoCNV.textContent = fechaFormateada + ' (' + fechaISO + ')';
        }
      }

      // 7. Procesar visitas
      // Rangos para visitas domiciliarias
      const rangosVisitas = {
        1: { min: 28, max: 30 },
        2: { min: 60, max: 150 },
        3: { min: 180, max: 240 },
        4: { min: 270, max: 330 }
      };
      
      // Calcular edad actual del niño para visitas
      let edadDiasActualVisitas = 0;
      if (datos.nino && datos.nino.fecha_nacimiento) {
        try {
          const fechaNacVisitas = crearFechaLocal(datos.nino.fecha_nacimiento);
          const hoyVisitas = new Date();
          edadDiasActualVisitas = Math.floor((hoyVisitas - fechaNacVisitas) / (1000 * 60 * 60 * 24));
        } catch (e) {
          console.error('❌ Error al calcular edad actual para visitas:', e);
        }
      }
      
      // Mapear números de control (1-4) a IDs de elementos (orden: fecha, edad, estado)
      const controlMap = {
        1: { fecha: 'visita-fecha-1', edad: 'visita-edad-1', estado: 'visita-estado-1' },
        2: { fecha: 'visita-fecha-2', edad: 'visita-edad-2', estado: 'visita-estado-2' },
        3: { fecha: 'visita-fecha-3', edad: 'visita-edad-3', estado: 'visita-estado-3' },
        4: { fecha: 'visita-fecha-4', edad: 'visita-edad-4', estado: 'visita-estado-4' }
      };
      
      if (datos.visitas && datos.visitas.length > 0 && datos.nino && datos.nino.fecha_nacimiento) {
        const fechaNacimientoStr = datos.nino.fecha_nacimiento;
        const fechaNacimiento = crearFechaLocal(fechaNacimientoStr);

        datos.visitas.forEach(visita => {
          const fechaVisitaISO = visita.fecha_visita || '';
          // Usar control_de_visita o numero_control (1-4) en lugar de grupo_visita
          const numeroControl = visita.control_de_visita || visita.numero_control || visita.numero_visitas || null;

          // Si viene grupo_visita (A, B, C, D), mapearlo a número de control
          let controlNumero = numeroControl;
          if (!controlNumero && visita.grupo_visita) {
            const grupoToNumero = { 'A': 1, 'B': 2, 'C': 3, 'D': 4 };
            controlNumero = grupoToNumero[visita.grupo_visita];
          }

          const mapItem = controlMap[controlNumero];
          
          if (mapItem && fechaVisitaISO && controlNumero) {
            try {
              const fechaVisita = crearFechaLocal(fechaVisitaISO);
              const fechaVisitaFormateada = fechaVisita.toLocaleDateString('es-PE', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
              });

              // Calcular edad en días
              const diffTime = fechaVisita - fechaNacimiento;
              const edadDias = Math.floor(diffTime / (1000 * 60 * 60 * 24));

              const fechaEl = document.getElementById(mapItem.fecha);
              const edadEl = document.getElementById(mapItem.edad);
              const estadoEl = document.getElementById(mapItem.estado);

              if (fechaEl) {
                fechaEl.textContent = fechaVisitaFormateada;
                fechaEl.style.color = '#1e293b';
                fechaEl.style.fontWeight = '500';
              }

              if (edadEl) {
                edadEl.textContent = edadDias + ' días';
                edadEl.style.color = '#1e293b';
                edadEl.style.fontWeight = '500';
              }

              if (estadoEl) {
                const rango = rangosVisitas[controlNumero];
                if (rango && edadDias >= rango.min && edadDias <= rango.max) {
                  estadoEl.className = 'estado-badge cumple';
                  estadoEl.textContent = 'CUMPLE';
                } else {
                  estadoEl.className = 'estado-badge no-cumple';
                  estadoEl.textContent = 'NO CUMPLE';
                }
              }

              console.log(`✅ Visita Control ${controlNumero} actualizada: ${fechaVisitaFormateada} (${edadDias} días)`);
            } catch (e) {
              console.error(`❌ Error al procesar visita Control ${controlNumero}:`, e);
            }
          }
        });
      }
      
      // Validar visitas sin fecha registrada - siempre mostrar SEGUIMIENTO
      // NO CUMPLE solo se muestra cuando hay una fecha registrada que está fuera del rango
      for (let numControl = 1; numControl <= 4; numControl++) {
        const mapItem = controlMap[numControl];
        const fechaEl = document.getElementById(mapItem.fecha);
        const estadoEl = document.getElementById(mapItem.estado);
        
        // Si no tiene fecha registrada, mantener SEGUIMIENTO
        if (fechaEl && estadoEl && fechaEl.textContent === '-') {
          estadoEl.className = 'estado-badge estado-seguimiento';
          estadoEl.textContent = 'SEGUIMIENTO';
        }
      }

      // Actualizar fecha de nacimiento en todas las tabs
      if (datos.nino && datos.nino.fecha_nacimiento) {
        const fechaNacStr = datos.nino.fecha_nacimiento;
        const fechaISO = formatearFechaISO(fechaNacStr);
        const fecha = crearFechaLocal(fechaNacStr);
        const fechaFormateada = fecha.toLocaleDateString('es-PE', {
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        });

        // Tab de visitas
        const fechaNacimientoVisitas = document.getElementById('fecha-nacimiento-visitas');
        if (fechaNacimientoVisitas) {
          fechaNacimientoVisitas.textContent = fechaFormateada + ' (' + fechaISO + ')';
        }

        // Tab de vacunas
        const fechaNacimientoVacunas = document.getElementById('fecha-nacimiento-vacunas');
        if (fechaNacimientoVacunas) {
          fechaNacimientoVacunas.textContent = fechaFormateada + ' (' + fechaISO + ')';
        }

        // Tab de tamizaje
        const fechaNacimientoTamizaje = document.getElementById('fecha-nacimiento-tamizaje');
        if (fechaNacimientoTamizaje) {
          fechaNacimientoTamizaje.textContent = fechaFormateada + ' (' + fechaISO + ')';
        }

        // Tab de CRED mensual
        const fechaNacimientoCredMensual = document.getElementById('fecha-nacimiento-cred-mensual');
        if (fechaNacimientoCredMensual) {
          fechaNacimientoCredMensual.textContent = fechaFormateada + ' (' + fechaISO + ')';
        }
      }

      console.log('✅ Todos los datos procesados correctamente desde el controlador');
    }

    // Asegurar que las funciones de cierre de modales existan
    function closeModalRegistro(event) {
      if (event && event.target !== event.currentTarget) return;
      ModalManager.cerrarTodos();
      const modal = document.getElementById('modalRegistroControl');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('show');
        ModalManager.activeModal = null;
      }
    }

    function closeModalTamizaje(event) {
      if (event && event.target !== event.currentTarget) return;
      ModalManager.cerrarTodos();
      const modal = document.getElementById('modalTamizaje');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('show');
        ModalManager.activeModal = null;
      }
    }

    function closeModalCNV(event) {
      if (event && event.target !== event.currentTarget) return;
      ModalManager.cerrarTodos();
      const modal = document.getElementById('modalCNV');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('show');
        ModalManager.activeModal = null;
      }
    }

    function closeModalVisita(event) {
      if (event && event.target !== event.currentTarget) return;
      ModalManager.cerrarTodos();
      const modal = document.getElementById('modalVisita');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('show');
        ModalManager.activeModal = null;
      }
    }

    function closeModalVacuna(event) {
      if (event && event.target !== event.currentTarget) return;
      ModalManager.cerrarTodos();
      const modal = document.getElementById('modalVacuna');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('show');
        ModalManager.activeModal = null;
      }
    }

    function closeModalCredMensual(event) {
      if (event && event.target !== event.currentTarget) return;
      ModalManager.cerrarTodos();
      const modal = document.getElementById('modalCredMensual');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('show');
        ModalManager.activeModal = null;
      }
    }

    // Asegurar que todas las funciones estén disponibles globalmente
    window.closeModalRegistro = closeModalRegistro;
    window.closeModalTamizaje = closeModalTamizaje;
    window.closeModalCNV = closeModalCNV;
    window.closeModalVisita = closeModalVisita;
    window.closeModalVacuna = closeModalVacuna;
    window.closeModalCredMensual = closeModalCredMensual;
    window.cargarDatosControles = cargarDatosControles;
    window.cargarDatosSimulados = cargarDatosSimulados;
    window.procesarDatosDelControlador = procesarDatosDelControlador;
    window.validarRangosYHabilitarBotones = validarRangosYHabilitarBotones;
    window.openAgregarNinoModal = openAgregarNinoModal;
    window.closeAgregarNinoModal = closeAgregarNinoModal;
    window.openImportarControlesModal = openImportarControlesModal;
    window.closeImportarControlesModal = closeImportarControlesModal;
    window.filtrarTabla = filtrarTabla;
    window.filtrarPorGenero = filtrarPorGenero;
    window.cambiarRegistrosPorPagina = cambiarRegistrosPorPagina;
    // cargarNinos ya está disponible globalmente desde su definición
    window.obtenerTipoDocumento = obtenerTipoDocumento;
    // Variable global para almacenar el ID del niño a eliminar
    let ninoIdAEliminar = null;

    // Función para abrir el modal de confirmación de eliminación
    function confirmarEliminarNino() {
      const ninoId = typeof window !== 'undefined' && window.ninoIdActual ? window.ninoIdActual : ninoIdActual;
      if (!ninoId) {
        alert('Error: No se ha seleccionado un niño.');
        return;
      }

      ninoIdAEliminar = ninoId;

      // Obtener nombre del niño del modal
      const nombreNino = document.getElementById('modalPatientName')?.textContent?.replace(/^\s*-\s*|\s*$/g, '') || 'N/A';
      
      // Actualizar el nombre en el modal de confirmación
      const nombreNinoEl = document.getElementById('nombreNinoEliminar');
      if (nombreNinoEl) {
        nombreNinoEl.textContent = nombreNino;
      }

      // Limpiar el input de confirmación
      const inputConfirmacion = document.getElementById('confirmacionEliminarNino');
      if (inputConfirmacion) {
        inputConfirmacion.value = '';
        inputConfirmacion.classList.remove('valid', 'invalid');
      }

      // Deshabilitar el botón de eliminar
      const btnConfirmar = document.getElementById('btnConfirmarEliminarNino');
      if (btnConfirmar) {
        btnConfirmar.disabled = true;
      }

      // Mostrar el modal
      const modal = document.getElementById('modalConfirmarEliminarNino');
      if (modal) {
        modal.classList.add('show');
        // Enfocar el input
        setTimeout(() => {
          if (inputConfirmacion) {
            inputConfirmacion.focus();
          }
        }, 100);
      }
    }

    // Función para cerrar el modal de confirmación
    function closeModalConfirmarEliminarNino(event) {
      if (event && event.target !== event.currentTarget && !event.target.closest('.modal-eliminar-nino-container')) {
        return;
      }
      const modal = document.getElementById('modalConfirmarEliminarNino');
      if (modal) {
        modal.classList.remove('show');
      }
      ninoIdAEliminar = null;
    }

    // Función para validar la confirmación mientras se escribe
    function validarConfirmacionEliminarNino() {
      const input = document.getElementById('confirmacionEliminarNino');
      const btnConfirmar = document.getElementById('btnConfirmarEliminarNino');
      
      if (!input || !btnConfirmar) return;

      const valor = input.value.trim();
      
      if (valor === 'ELIMINAR') {
        input.classList.remove('invalid');
        input.classList.add('valid');
        btnConfirmar.disabled = false;
      } else if (valor.length > 0) {
        input.classList.remove('valid');
        input.classList.add('invalid');
        btnConfirmar.disabled = true;
      } else {
        input.classList.remove('valid', 'invalid');
        btnConfirmar.disabled = true;
      }
    }

    // Función para ejecutar la eliminación
    function ejecutarEliminarNino() {
      if (!ninoIdAEliminar) {
        alert('Error: No se ha seleccionado un niño.');
        return;
      }

      const inputConfirmacion = document.getElementById('confirmacionEliminarNino');
      if (!inputConfirmacion || inputConfirmacion.value.trim() !== 'ELIMINAR') {
        alert('Debes escribir "ELIMINAR" para confirmar.');
        return;
      }

      // Deshabilitar botones y mostrar loading
      const btnConfirmar = document.getElementById('btnConfirmarEliminarNino');
      const btnCancelar = document.querySelector('.modal-eliminar-nino-btn-cancel');
      
      if (btnConfirmar) {
        btnConfirmar.disabled = true;
        const originalText = btnConfirmar.innerHTML;
        btnConfirmar.innerHTML = `
          <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Eliminando...
        `;
      }
      
      if (btnCancelar) {
        btnCancelar.disabled = true;
      }

      // Realizar petición DELETE
      const urlEliminar = `/api/nino/${ninoIdAEliminar}`;
      console.log('🗑️ Eliminando niño con ID:', ninoIdAEliminar);
      console.log('📡 URL:', urlEliminar);
      
      fetch(urlEliminar, {
        method: 'DELETE',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      })
      .then(async response => {
        console.log('📡 Respuesta del servidor:', response.status, response.statusText);
        console.log('📡 Headers:', response.headers);
        
        // Intentar obtener el JSON siempre
        let data;
        try {
          const text = await response.text();
          console.log('📄 Respuesta texto:', text);
          data = JSON.parse(text);
        } catch (e) {
          console.error('❌ Error al parsear JSON:', e);
          throw new Error(`Error al procesar respuesta del servidor: ${response.status} ${response.statusText}`);
        }
        
        if (!response.ok) {
          // Mostrar el mensaje de error del servidor si existe
          const errorMsg = data.message || data.error || `Error HTTP ${response.status}: ${response.statusText}`;
          console.error('❌ Error del servidor:', errorMsg);
          throw new Error(errorMsg);
        }
        
        return data;
      })
      .then(data => {
        if (data.success) {
          // Cerrar modales
          closeModalConfirmarEliminarNino();
          closeVerControlesModal();
          
          // Mostrar mensaje de éxito
          const successMessage = document.createElement('div');
          successMessage.className = 'mensaje-exito animate-slide-in';
          successMessage.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
          successMessage.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
              <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <div style="flex: 1;">
              <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">✅ Niño eliminado exitosamente</div>
              <div style="font-size: 0.75rem; opacity: 0.95;">${data.message || 'Todos los datos del niño han sido eliminados.'}</div>
            </div>
          `;
          document.body.appendChild(successMessage);
          
          setTimeout(() => {
            successMessage.classList.add('animate-slide-out');
            setTimeout(() => successMessage.remove(), 300);
          }, 5000);

          // Recargar la tabla de niños
          if (typeof cargarNinos === 'function') {
            setTimeout(() => {
              cargarNinos(1);
            }, 1000);
          } else {
            // Si no existe la función, recargar la página
            setTimeout(() => {
              window.location.reload();
            }, 1500);
          }
        } else {
          // Mostrar mensaje de error
          const errorMessage = document.createElement('div');
          errorMessage.className = 'mensaje-error animate-slide-in';
          errorMessage.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4), 0 4px 6px -2px rgba(239, 68, 68, 0.3); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
          errorMessage.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div style="flex: 1;">
              <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">❌ Error al eliminar</div>
              <div style="font-size: 0.75rem; opacity: 0.95;">${data.message || 'No se pudo eliminar el niño. Por favor, intente nuevamente.'}</div>
            </div>
          `;
          document.body.appendChild(errorMessage);
          
          setTimeout(() => {
            errorMessage.classList.add('animate-slide-out');
            setTimeout(() => errorMessage.remove(), 300);
          }, 5000);

          // Restaurar botones del modal de confirmación
          const btnConfirmarError = document.getElementById('btnConfirmarEliminarNino');
          const btnCancelarError = document.querySelector('.modal-eliminar-nino-btn-cancel');
          
          if (btnConfirmarError) {
            btnConfirmarError.disabled = false;
            btnConfirmarError.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18"></path>
                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
              </svg>
              Eliminar Permanentemente
            `;
          }
          if (btnCancelarError) {
            btnCancelarError.disabled = false;
          }
        }
      })
      .catch(error => {
        console.error('Error al eliminar niño:', error);
        console.error('Error details:', {
          message: error.message,
          stack: error.stack,
          name: error.name
        });
        
        // Obtener mensaje de error más específico
        let errorMsg = 'Error al eliminar el niño. Por favor, intente nuevamente.';
        if (error.message) {
          errorMsg = error.message;
        }
        
        // Mostrar mensaje de error
        const errorMessage = document.createElement('div');
        errorMessage.className = 'mensaje-error animate-slide-in';
        errorMessage.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4), 0 4px 6px -2px rgba(239, 68, 68, 0.3); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
        errorMessage.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
          </svg>
          <div style="flex: 1;">
            <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">❌ Error al eliminar</div>
            <div style="font-size: 0.75rem; opacity: 0.95;">${errorMsg}</div>
          </div>
        `;
        document.body.appendChild(errorMessage);
        
        setTimeout(() => {
          errorMessage.classList.add('animate-slide-out');
          setTimeout(() => errorMessage.remove(), 300);
        }, 5000);

        // Restaurar botones del modal de confirmación
        const btnConfirmarCatch = document.getElementById('btnConfirmarEliminarNino');
        const btnCancelarCatch = document.querySelector('.modal-eliminar-nino-btn-cancel');
        
        if (btnConfirmarCatch) {
          btnConfirmarCatch.disabled = false;
          btnConfirmarCatch.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 6h18"></path>
              <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
              <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
            </svg>
            Eliminar Permanentemente
          `;
        }
        if (btnCancelarCatch) {
          btnCancelarCatch.disabled = false;
        }
      });
    }

    window.openVerControlesModal = openVerControlesModal;
    window.openDatosExtrasModal = openDatosExtrasModal;
    window.closeDatosExtrasModal = closeDatosExtrasModal;
    window.closeVerControlesModal = closeVerControlesModal;
    window.cambiarTab = cambiarTab;
    window.confirmarEliminarNino = confirmarEliminarNino;
    window.closeModalConfirmarEliminarNino = closeModalConfirmarEliminarNino;
    window.validarConfirmacionEliminarNino = validarConfirmacionEliminarNino;
    window.ejecutarEliminarNino = ejecutarEliminarNino;
    window.mostrarAdvertenciaAgregarNino = mostrarAdvertenciaAgregarNino;
    window.closeModalAdvertenciaAgregarNino = closeModalAdvertenciaAgregarNino;
    window.closeModalAdvertenciaAgregarNinoOnOverlay = closeModalAdvertenciaAgregarNinoOnOverlay;
    window.confirmarAdvertenciaAgregarNino = confirmarAdvertenciaAgregarNino;
    window.scrollTabs = scrollTabs;
    window.formatearFechaISO = formatearFechaISO;
    window.crearFechaLocal = crearFechaLocal;
    window.initModalSelects = initModalSelects;
    window.resetModalSelects = resetModalSelects;

    // Las funciones de registro y abrir modales ya están disponibles globalmente desde su definición

    // Asegurar que ninoIdActual esté disponible globalmente
    if (typeof window.ninoIdActual === 'undefined') {
      window.ninoIdActual = ninoIdActual;
    }

    console.log('✅ Todas las funciones de modales están disponibles globalmente');
  </script>
</body>

</html>


