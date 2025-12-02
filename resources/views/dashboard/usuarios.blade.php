<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#000000">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="Sistema de Control y Alerta de Etapas de Vida del Niño - SISCADIT">
  <title>SISCADIT - Gestión de Usuarios</title>
  <link rel="stylesheet" href="/Css/dashbord.css">
  <link rel="stylesheet" href="/Css/modal-usuario.css">
  @stack('styles')
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
              <x-sidebar-main activeRoute="usuarios" />
              <main class="flex-1 overflow-auto">
                <div class="p-8">
                  <div class="space-y-6" data-testid="usuarios-page">
                    <!-- Header Section -->
                    <div class="flex items-center justify-between flex-wrap gap-6">
                      <div>
                        <h1 class="text-4xl font-bold text-slate-700 mb-1">Gestión de Usuarios</h1>
                        <p class="text-slate-500 text-base">Solicitudes y usuarios del sistema</p>
                      </div>
                    </div>

                    <!-- Tabs -->
                    <div class="bg-white rounded-xl border border-slate-200 p-1 shadow-sm">
                      <div class="flex gap-2">
                        <button id="tabSolicitudes" onclick="cambiarTab('solicitudes')" 
                          class="flex-1 px-6 py-3 rounded-lg font-medium transition-all text-center tab-button active">
                          <span class="flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                              <polyline points="14 2 14 8 20 8"></polyline>
                              <line x1="16" x2="8" y1="13" y2="13"></line>
                              <line x1="16" x2="8" y1="17" y2="17"></line>
                            </svg>
                            Solicitudes
                          </span>
                        </button>
                        <button id="tabUsuarios" onclick="cambiarTab('usuarios')" 
                          class="flex-1 px-6 py-3 rounded-lg font-medium transition-all text-center tab-button">
                          <span class="flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                              <circle cx="9" cy="7" r="4"></circle>
                              <line x1="19" x2="19" y1="8" y2="14"></line>
                              <line x1="22" x2="16" y1="11" y2="11"></line>
                            </svg>
                            Usuarios
                          </span>
                        </button>
                      </div>
                    </div>

                    <!-- Sección de Solicitudes -->
                    <div id="seccionSolicitudes" class="tab-content">
                      <!-- Filtros -->
                      <div class="flex gap-4 items-center flex-wrap">
                        <div class="search-container-cred">
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                          </svg>
                          <input type="text" id="searchInputSolicitudes" placeholder="Buscar por documento o correo..."
                            class="search-input-cred" onkeyup="filtrarTablaSolicitudes()">
                        </div>
                      </div>

                      <!-- Table Section -->
                      <div style="margin-top: 24px; overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                          <thead>
                            <tr style="background: linear-gradient(to right, #3b82f6, #2563eb); color: white;">
                                <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Tipo Doc.</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">N° Documento</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Red</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Microred</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Establecimiento</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Correo</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Cargo</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Celular</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Motivo</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Fecha</th>
                                <th style="padding: 12px; text-align: center; font-weight: 600; font-size: 13px; text-transform: uppercase;">Acciones</th>
                              </tr>
                            </thead>
                            <tbody id="tablaSolicitudesBody">
                              <!-- Las filas se cargarán dinámicamente -->
                            </tbody>
                            <tfoot id="footerSolicitudes" style="background: #f8fafc; border-top: 2px solid #e2e8f0;">
                              <tr>
                                <td colspan="11" style="padding: 16px 24px;">
                                  <div style="display: flex; align-items: center; justify-content: space-between; font-size: 14px; color: #475569;">
                                    <div style="display: flex; align-items: center; gap: 16px;">
                                      <span style="font-weight: 600; color: #1e293b;">Total de solicitudes:</span>
                                      <span id="totalSolicitudes" style="padding: 6px 12px; background: #eef2ff; color: #6366f1; border-radius: 999px; font-weight: 600;">0</span>
                                    </div>
                                    <div style="font-size: 12px; color: #64748b;">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; margin-right: 4px; vertical-align: middle;">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M12 16v-4"></path>
                                        <path d="M12 8h.01"></path>
                                      </svg>
                                      Última actualización: <span id="ultimaActualizacionSolicitudes">--</span>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                            </tfoot>
                          </table>
                      </div>
                    </div>

                    <!-- Sección de Usuarios -->
                    <div id="seccionUsuarios" class="tab-content hidden">
                      <!-- Filtros -->
                      <div class="flex gap-4 items-center flex-wrap">
                        <div class="search-container-cred">
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                          </svg>
                          <input type="text" id="searchInputUsuarios" placeholder="Buscar por nombre o correo..."
                            class="search-input-cred" onkeyup="filtrarTablaUsuarios()">
                        </div>
                        <select id="filtroRol" onchange="cambiarRol()" class="bg-white border border-slate-300 text-slate-700 px-4 py-3 rounded-xl font-medium transition-all shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                          <option value="">Todos los roles</option>
                          <option value="jefe_microred">Jefe de Red</option>
                          <option value="coordinador_red">Coordinador de MicroRed</option>
                        </select>
                      </div>

                      <!-- Table Section -->
                      <div style="margin-top: 24px; overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                          <thead>
                            <tr style="background: linear-gradient(to right, #3b82f6, #2563eb); color: white;">
                                <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Tipo Doc.</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">N° Documento</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Red</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Microred</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Establecimiento</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Correo</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Cargo</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Celular</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Motivo</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Rol/Permiso</th>
                                <th style="padding: 12px; text-align: center; font-weight: 600; font-size: 13px; text-transform: uppercase;">Acciones</th>
                              </tr>
                            </thead>
                            <tbody id="tablaUsuariosBody">
                              <!-- Las filas se cargarán dinámicamente -->
                            </tbody>
                            <tfoot id="footerUsuarios" style="background: #f8fafc; border-top: 2px solid #e2e8f0;">
                              <tr>
                                <td colspan="11" style="padding: 16px 24px;">
                                  <div style="display: flex; align-items: center; justify-content: space-between; font-size: 14px; color: #475569;">
                                    <div style="display: flex; align-items: center; gap: 16px;">
                                      <span style="font-weight: 600; color: #1e293b;">Total de usuarios:</span>
                                      <span id="totalUsuarios" style="padding: 6px 12px; background: #eef2ff; color: #6366f1; border-radius: 999px; font-weight: 600;">0</span>
                                    </div>
                                    <div style="font-size: 12px; color: #64748b;">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; margin-right: 4px; vertical-align: middle;">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M12 16v-4"></path>
                                        <path d="M12 8h.01"></path>
                                      </svg>
                                      Última actualización: <span id="ultimaActualizacionUsuarios">--</span>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                            </tfoot>
                          </table>
                      </div>
                      <!-- Paginación -->
                      <div id="paginacionUsuarios" style="padding: 16px 24px; border-top: 1px solid #e2e8f0; background: white; border-radius: 0 0 8px 8px;">
                        <!-- Los controles de paginación se cargarán dinámicamente -->
                      </div>
                    </div>
                  </div>
                </div>
              </main>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para crear usuario desde solicitud -->
  <div id="modalCrearUsuario" class="modal-usuario-overlay" onclick="closeModalCrearUsuario(event)">
    <div class="modal-usuario-container" onclick="event.stopPropagation()">
      <!-- Header del Modal con gradiente -->
      <div class="modal-usuario-header">
        <div class="modal-usuario-header-content">
          <div class="modal-usuario-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
              <circle cx="9" cy="7" r="4"></circle>
              <line x1="19" x2="19" y1="8" y2="14"></line>
              <line x1="22" x2="16" y1="11" y2="11"></line>
            </svg>
          </div>
          <div>
            <h3 class="modal-usuario-title">Crear Usuario desde Solicitud</h3>
            <p class="modal-usuario-subtitle">Complete los datos para crear la cuenta de usuario</p>
          </div>
        </div>
        <button onclick="closeModalCrearUsuario()" class="modal-usuario-close">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>
      
      <!-- Contenido del Modal con scroll -->
      <div class="modal-usuario-content">
        <form id="formCrearUsuario" onsubmit="crearUsuario(event)">
          <input type="hidden" id="solicitudId" name="solicitud_id">
          <div class="space-y-5">
            <!-- Búsqueda RENIEC -->
            <div class="modal-usuario-section reniec">
            <h4 class="modal-usuario-section-title">
              <div class="modal-usuario-section-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="11" cy="11" r="8"></circle>
                  <path d="m21 21-4.35-4.35"></path>
                </svg>
              </div>
              <span>Consulta RENIEC</span>
            </h4>
            <p class="text-sm text-slate-700 mb-4 font-medium">Ingrese el tipo y número de documento para consultar los datos en RENIEC:</p>
            <div class="grid grid-cols-2 gap-4 mb-3">
              <div class="modal-usuario-form-group">
                <label class="modal-usuario-label required">
                  Tipo de Documento
                </label>
                <select id="reniecTipoDoc" class="modal-usuario-select" required disabled>
                  <option value="1" selected>DNI</option>
                </select>
                <input type="hidden" id="reniecTipoDocHidden" value="1">
                <p class="text-xs text-slate-500 mt-1">Solo se permite consulta por DNI</p>
              </div>
              <div class="modal-usuario-form-group">
                <label class="modal-usuario-label required">
                  Número de Documento
                </label>
                <div class="flex gap-3">
                  <input type="text" id="reniecNumeroDoc" 
                    class="modal-usuario-input flex-1" 
                    placeholder="Ingrese el DNI (8 dígitos)" required maxlength="8" pattern="[0-9]{8}" 
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                  <button type="button" id="btnBuscarReniec" onclick="buscarReniec()" 
                    class="modal-usuario-btn-reniec">
                    <svg id="iconBuscar" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="transition-all">
                      <circle cx="11" cy="11" r="8"></circle>
                      <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <svg id="iconLoading" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="hidden animate-spin">
                      <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.25"></circle>
                      <path d="M12 2v4" stroke="currentColor" stroke-opacity="0.75"></path>
                    </svg>
                    <span id="textBuscar">Buscar</span>
                  </button>
                </div>
              </div>
            </div>
            <div id="reniecResultado" class="modal-usuario-reniec-result hidden">
              <div class="modal-usuario-reniec-result-header">
                <div class="modal-usuario-reniec-result-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                  </svg>
                </div>
                <h5 class="modal-usuario-reniec-result-title">Datos encontrados en RENIEC</h5>
              </div>
              <div class="space-y-3">
                <div class="modal-usuario-reniec-data">
                  <span class="modal-usuario-reniec-label">Nombres Completos:</span>
                  <span class="modal-usuario-reniec-value" id="reniecNombres">-</span>
                </div>
                <div class="modal-usuario-reniec-data">
                  <span class="modal-usuario-reniec-label">Apellido Paterno:</span>
                  <span class="modal-usuario-reniec-value" id="reniecApellidoPaterno">-</span>
                </div>
                <div class="modal-usuario-reniec-data">
                  <span class="modal-usuario-reniec-label">Apellido Materno:</span>
                  <span class="modal-usuario-reniec-value" id="reniecApellidoMaterno">-</span>
                </div>
                <div class="modal-usuario-reniec-data">
                  <span class="modal-usuario-reniec-label">Nombres:</span>
                  <span class="modal-usuario-reniec-value" id="reniecNombresOnly">-</span>
                </div>
              </div>
            </div>
            <div id="reniecError" class="modal-usuario-reniec-error hidden">
              <div class="modal-usuario-reniec-error-content">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="modal-usuario-reniec-error-icon">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="12" y1="8" x2="12" y2="12"></line>
                  <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <div id="reniecErrorContent" class="flex-1"></div>
              </div>
            </div>
          </div>

            <!-- Información del Establecimiento -->
            <div class="modal-usuario-section info">
              <h4 class="modal-usuario-section-title">
                <div class="modal-usuario-section-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                  </svg>
                </div>
                <span>Información del Establecimiento</span>
              </h4>
              <div class="space-y-4">
                <div class="modal-usuario-form-group">
                  <label class="modal-usuario-label">Red</label>
                  <input type="text" id="red" class="modal-usuario-input" readonly>
                </div>
                <div class="modal-usuario-form-group">
                  <label class="modal-usuario-label">Microred</label>
                  <input type="text" id="microred" class="modal-usuario-input" readonly>
                </div>
                <div class="modal-usuario-form-group">
                  <label class="modal-usuario-label">Establecimiento</label>
                  <input type="text" id="establecimiento" class="modal-usuario-input" readonly>
                </div>
              </div>
            </div>

            <!-- Información Adicional -->
            <div class="modal-usuario-section info">
              <h4 class="modal-usuario-section-title">
                <div class="modal-usuario-section-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" x2="8" y1="13" y2="13"></line>
                    <line x1="16" x2="8" y1="17" y2="17"></line>
                  </svg>
                </div>
                <span>Información Adicional</span>
              </h4>
              <div class="space-y-4">
                <div class="modal-usuario-form-group">
                  <label class="modal-usuario-label">Motivo</label>
                  <textarea id="motivo" class="modal-usuario-textarea" rows="3" readonly></textarea>
                </div>
                <div class="modal-usuario-form-group">
                  <label class="modal-usuario-label">Cargo</label>
                  <input type="text" id="cargo" class="modal-usuario-input" readonly>
                </div>
              </div>
            </div>

            <!-- Contacto -->
            <div class="modal-usuario-section info">
              <h4 class="modal-usuario-section-title">
                <div class="modal-usuario-section-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                  </svg>
                </div>
                <span>Contacto</span>
              </h4>
              <div class="grid grid-cols-2 gap-4">
                <div class="modal-usuario-form-group">
                  <label class="modal-usuario-label">Celular</label>
                  <input type="text" id="celular" class="modal-usuario-input" readonly>
                </div>
                <div class="modal-usuario-form-group">
                  <label class="modal-usuario-label">Correo Electrónico</label>
                  <input type="email" id="correo" class="modal-usuario-input" readonly>
                </div>
              </div>
            </div>

            <!-- Sección de Creación de Usuario -->
            <div class="modal-usuario-section create">
              <h4 class="modal-usuario-section-title">
                <div class="modal-usuario-section-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <line x1="19" x2="19" y1="8" y2="14"></line>
                    <line x1="22" x2="16" y1="11" y2="11"></line>
                  </svg>
                </div>
                <span>Crear Usuario</span>
              </h4>
              <p class="text-sm text-slate-700 mb-6 font-medium">Complete los siguientes campos para crear la cuenta de usuario:</p>
              <div class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="modal-usuario-form-group">
                    <label class="modal-usuario-label required">
                      Nombre de Usuario (desde RENIEC)
                    </label>
                    <input type="text" id="nameUsuario" name="name" 
                      class="modal-usuario-input" 
                      placeholder="Se llenará automáticamente desde RENIEC" required readonly>
                    <p class="text-xs text-slate-600 mt-2.5 font-medium flex items-center gap-1.5">
                      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 16v-4"></path>
                        <path d="M12 8h.01"></path>
                      </svg>
                      Este campo se llenará automáticamente con los datos de RENIEC
                    </p>
                  </div>
                  <div class="modal-usuario-form-group">
                    <label class="modal-usuario-label required">
                      Contraseña
                    </label>
                    <div class="relative">
                      <input type="password" id="passwordUsuario" name="password" 
                        class="modal-usuario-input" 
                        placeholder="Ingrese la contraseña" required minlength="6">
                      <button type="button" onclick="togglePasswordVisibility('passwordUsuario')" class="password-toggle-btn" aria-label="Mostrar/Ocultar contraseña">
                        <svg id="eyeIcon-passwordUsuario" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="hidden">
                          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                          <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg id="eyeOffIcon-passwordUsuario" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                          <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                      </button>
                    </div>
                    <p class="text-xs text-slate-600 mt-2.5 font-medium flex items-center gap-1.5">
                      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500">
                        <polyline points="20 6 9 17 4 12"></polyline>
                      </svg>
                      Mínimo 6 caracteres
                    </p>
                  </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="modal-usuario-form-group">
                    <label class="modal-usuario-label required">
                      Correo Electrónico
                    </label>
                    <input type="email" id="correoUsuario" name="email" 
                      class="modal-usuario-input" 
                      placeholder="ejemplo@correo.com" required>
                    <p class="text-xs text-slate-600 mt-2.5 font-medium flex items-center gap-1.5">
                      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 16v-4"></path>
                        <path d="M12 8h.01"></path>
                      </svg>
                      Se usará el correo de la solicitud por defecto
                    </p>
                  </div>
                  <div class="modal-usuario-form-group">
                    <label class="modal-usuario-label required">
                      Rol/Permiso
                    </label>
                    <select id="rolUsuario" name="role" 
                      class="modal-usuario-select" required>
                      <option value="">Seleccione un rol</option>
                      <option value="jefe_microred">Jefe de Micro Red</option>
                      <option value="coordinador_red">Coordinador de Red</option>
                    </select>
                    <p class="text-xs text-slate-600 mt-2.5 font-medium flex items-center gap-1.5">
                      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 16v-4"></path>
                        <path d="M12 8h.01"></path>
                      </svg>
                      Seleccione el nivel de acceso del usuario
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      
      <!-- Footer del Modal -->
      <div class="modal-usuario-footer">
        <button type="button" onclick="closeModalCrearUsuario()" class="modal-usuario-btn modal-usuario-btn-secondary">
          Cancelar
        </button>
        <button type="submit" form="formCrearUsuario" class="modal-usuario-btn modal-usuario-btn-primary">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <line x1="19" x2="19" y1="8" y2="14"></line>
            <line x1="22" x2="16" y1="11" y2="11"></line>
          </svg>
          Crear Usuario
        </button>
      </div>
    </div>
  </div>

  <!-- Modal para editar usuario -->
  <div id="modalEditarUsuario" class="modal-usuario-overlay" onclick="closeModalEditarUsuario(event)">
    <div class="modal-usuario-container" onclick="event.stopPropagation()">
      <!-- Header del Modal con gradiente -->
      <div class="modal-usuario-header edit">
        <div class="modal-usuario-header-content">
          <div class="modal-usuario-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
          </div>
          <div>
            <h3 class="modal-usuario-title">Editar Usuario</h3>
            <p class="modal-usuario-subtitle">Modifique los datos del usuario</p>
          </div>
        </div>
        <button onclick="closeModalEditarUsuario()" class="modal-usuario-close">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>
      
      <!-- Contenido del Modal con scroll -->
      <div class="modal-usuario-content">
        <form id="formEditarUsuario" onsubmit="actualizarUsuario(event)">
          <input type="hidden" id="usuarioIdEditar" name="usuario_id">
          <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Información del Usuario -->
            <div class="modal-usuario-section">
              <h4 class="modal-usuario-section-title">
                <div class="modal-usuario-section-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                  </svg>
                </div>
                <span>Información del Usuario</span>
              </h4>
              
              <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <div class="modal-usuario-form-group">
                  <label class="modal-usuario-label required">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;">
                      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                      <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Nombre Completo
                  </label>
                  <input type="text" id="nombreUsuarioEditar" name="name" 
                    class="modal-usuario-input" 
                    placeholder="Ingrese el nombre completo" required>
                </div>
                
                <div class="modal-usuario-form-group">
                  <label class="modal-usuario-label required">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;">
                      <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                      <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    Correo Electrónico
                  </label>
                  <input type="email" id="correoUsuarioEditar" name="email" 
                    class="modal-usuario-input" 
                    placeholder="Ingrese el correo electrónico" required>
                </div>
                
                <div class="modal-usuario-form-group">
                  <label class="modal-usuario-label required">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;">
                      <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                      <circle cx="9" cy="7" r="4"></circle>
                      <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                      <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    Rol/Permiso
                  </label>
                  <select id="rolUsuarioEditar" name="role" 
                    class="modal-usuario-select" required>
                    <option value="">Seleccione un rol</option>
                    <option value="jefe_microred">Red</option>
                    <option value="coordinador_red">MicroRed</option>
                    <option value="usuario">Cancelar Permisos</option>
                  </select>
                  <p style="font-size: 0.75rem; color: #475569; margin-top: 0.5rem; font-weight: 500; display: flex; align-items: flex-start; gap: 0.375rem; line-height: 1.5;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #3b82f6; margin-top: 0.125rem; flex-shrink: 0;">
                      <circle cx="12" cy="12" r="10"></circle>
                      <path d="M12 16v-4"></path>
                      <path d="M12 8h.01"></path>
                    </svg>
                    <span>Seleccione el rol del usuario. Use "Cancelar Permisos" para quitar los permisos especiales.</span>
                  </p>
                </div>
              </div>
            </div>

            <!-- Cambio de Contraseña (Opcional) -->
            <div class="modal-usuario-section">
              <h4 class="modal-usuario-section-title">
                <div class="modal-usuario-section-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                  </svg>
                </div>
                <span>Cambiar Contraseña (Opcional)</span>
              </h4>
              <p style="font-size: 0.875rem; color: #475569; margin-bottom: 1rem; font-weight: 500;">Deje en blanco si no desea cambiar la contraseña</p>
              
              <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div class="modal-usuario-form-group">
                  <label class="modal-usuario-label">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #64748b;">
                      <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                      <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    Nueva Contraseña
                  </label>
                  <div class="relative">
                    <input type="password" id="nuevaPasswordEditar" name="password" 
                      class="modal-usuario-input" 
                      placeholder="Ingrese nueva contraseña (mínimo 6 caracteres)" minlength="6">
                    <button type="button" onclick="togglePasswordVisibility('nuevaPasswordEditar')" class="password-toggle-btn" aria-label="Mostrar/Ocultar contraseña">
                      <svg id="eyeIcon-nuevaPasswordEditar" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="hidden">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                      </svg>
                      <svg id="eyeOffIcon-nuevaPasswordEditar" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                      </svg>
                    </button>
                  </div>
                  <p style="font-size: 0.75rem; color: #475569; margin-top: 0.5rem; font-weight: 500;">Deje en blanco si no desea cambiar la contraseña. Mínimo 6 caracteres si la cambia.</p>
                </div>
              </div>
            </div>

            <!-- Datos de la Solicitud -->
            <div class="modal-usuario-section" id="seccionSolicitud">
              <h4 class="modal-usuario-section-title">
                <div class="modal-usuario-section-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                  </svg>
                </div>
                <span>Datos de la Solicitud</span>
              </h4>
              
              <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <!-- Tipo de Documento y Número -->
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1rem;">
                  <div class="modal-usuario-form-group">
                    <label class="modal-usuario-label required">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                      </svg>
                      Tipo Documento
                    </label>
                    <select id="tipoDocumentoSolicitud" name="solicitud[id_tipo_documento]" class="modal-usuario-select" required disabled>
                      <option value="1" selected>DNI</option>
                    </select>
                    <input type="hidden" id="tipoDocumentoSolicitudHidden" name="solicitud[id_tipo_documento]" value="1">
                    <p class="text-xs text-slate-500 mt-1">Solo se permite DNI</p>
                  </div>
                  
                  <div class="modal-usuario-form-group">
                    <label class="modal-usuario-label required">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                      </svg>
                      Número de Documento
                    </label>
                    <input type="text" id="numeroDocumentoSolicitud" name="solicitud[numero_documento]" 
                      class="modal-usuario-input" 
                      placeholder="Ingrese el número de documento" maxlength="20" required>
                  </div>
                </div>

                <!-- Red, Microred y Establecimiento -->
                <div class="modal-usuario-form-group">
                  <label class="modal-usuario-label required">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;">
                      <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    Red
                  </label>
                  <select id="codigoRedSolicitud" name="solicitud[codigo_red]" class="modal-usuario-select" required>
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

                <div class="modal-usuario-form-group">
                  <label class="modal-usuario-label required">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;">
                      <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    </svg>
                    Microred
                  </label>
                  <select id="codigoMicroredSolicitud" name="solicitud[codigo_microred]" class="modal-usuario-select" required disabled>
                    <option value="">Seleccione una Microred</option>
                  </select>
                </div>

                <div class="modal-usuario-form-group">
                  <label class="modal-usuario-label required">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;">
                      <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                      <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Establecimiento
                  </label>
                  <select id="idEstablecimientoSolicitud" name="solicitud[id_establecimiento]" class="modal-usuario-select" required disabled>
                    <option value="">Seleccione un Establecimiento</option>
                  </select>
                </div>

                <!-- Motivo y Cargo -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                  <div class="modal-usuario-form-group">
                    <label class="modal-usuario-label required">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                      </svg>
                      Motivo
                    </label>
                    <input type="text" id="motivoSolicitud" name="solicitud[motivo]" 
                      class="modal-usuario-input" 
                      placeholder="Ingrese el motivo" maxlength="255" required>
                  </div>
                  
                  <div class="modal-usuario-form-group">
                    <label class="modal-usuario-label required">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                      </svg>
                      Cargo
                    </label>
                    <input type="text" id="cargoSolicitud" name="solicitud[cargo]" 
                      class="modal-usuario-input" 
                      placeholder="Ingrese el cargo" maxlength="255" required>
                  </div>
                </div>

                <!-- Celular -->
                <div class="modal-usuario-form-group">
                  <label class="modal-usuario-label required">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;">
                      <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    Celular
                  </label>
                  <input type="text" id="celularSolicitud" name="solicitud[celular]" 
                    class="modal-usuario-input" 
                    placeholder="Ingrese el celular" maxlength="20" required>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      
      <!-- Footer del Modal -->
      <div class="modal-usuario-footer">
        <button type="button" onclick="closeModalEditarUsuario()" class="modal-usuario-btn modal-usuario-btn-secondary">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
          <span>Cancelar</span>
        </button>
        <button type="submit" form="formEditarUsuario" class="modal-usuario-btn modal-usuario-btn-primary">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 6L9 17l-5-5"></path>
          </svg>
          <span>Guardar Cambios</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Modal para confirmar eliminación de usuario -->
  <div id="modalConfirmarEliminar" class="modal-usuario-overlay" onclick="closeModalConfirmarEliminar(event)">
    <div class="modal-usuario-container" style="max-width: 32rem;" onclick="event.stopPropagation()">
      <!-- Header del Modal -->
      <div class="modal-usuario-header" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #b91c1c 100%);">
        <div class="modal-usuario-header-content">
          <div class="modal-usuario-icon" style="background: rgba(255, 255, 255, 0.25);">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
          </div>
          <div>
            <h3 class="modal-usuario-title">Confirmar Eliminación</h3>
            <p class="modal-usuario-subtitle">Esta acción no se puede deshacer</p>
          </div>
        </div>
        <button onclick="closeModalConfirmarEliminar()" class="modal-usuario-close">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>
      
      <!-- Contenido del Modal -->
      <div class="modal-usuario-content" style="padding: 2rem; text-align: center;">
        <div style="margin-bottom: 1.5rem;">
          <div style="margin: 0 auto; width: 5rem; height: 5rem; background-color: #fee2e2; border-radius: 9999px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="3 6 5 6 21 6"></polyline>
              <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
              <line x1="10" y1="11" x2="10" y2="17"></line>
              <line x1="14" y1="11" x2="14" y2="17"></line>
            </svg>
          </div>
          <h4 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">¿Está seguro de eliminar este usuario?</h4>
          <p style="color: #475569; margin-bottom: 0.25rem; font-size: 0.875rem;">
            El usuario <strong id="nombreUsuarioEliminar" style="color: #0f172a; font-weight: 600;"></strong> será eliminado permanentemente.
          </p>
          <p style="font-size: 0.875rem; color: #dc2626; font-weight: 600; margin-top: 0.75rem;">
            ⚠️ Esta acción no se puede deshacer
          </p>
        </div>
      </div>
      
      <!-- Footer del Modal -->
      <div class="modal-usuario-footer" style="display: flex; gap: 0.75rem; justify-content: space-between;">
        <button type="button" onclick="closeModalConfirmarEliminar()" class="modal-usuario-btn modal-usuario-btn-secondary" style="flex: 0 1 auto; min-width: 140px;">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
          Cancelar
        </button>
        <button type="button" onclick="confirmarEliminarUsuario()" class="modal-usuario-btn" style="background: linear-gradient(135deg, #ef4444, #dc2626, #b91c1c); color: white; flex: 1; margin-left: 0.75rem; box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.3);">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
            <line x1="10" y1="11" x2="10" y2="17"></line>
            <line x1="14" y1="11" x2="14" y2="17"></line>
          </svg>
          Sí, Eliminar Usuario
        </button>
      </div>
    </div>
  </div>

  <!-- Modal de Confirmación para Rechazar Solicitud -->
  <div id="modalConfirmarRechazar" class="modal-usuario-overlay" onclick="closeModalConfirmarRechazar(event)">
    <div class="modal-usuario-container" style="max-width: 32rem;" onclick="event.stopPropagation()">
      <!-- Header del Modal -->
      <div class="modal-usuario-header" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #b91c1c 100%);">
        <div class="modal-usuario-header-content">
          <div class="modal-usuario-icon" style="background: rgba(255, 255, 255, 0.25);">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
          </div>
          <div>
            <h3 class="modal-usuario-title">Confirmar Rechazo de Solicitud</h3>
            <p class="modal-usuario-subtitle">Esta acción no se puede deshacer</p>
          </div>
        </div>
        <button onclick="closeModalConfirmarRechazar()" class="modal-usuario-close">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>
      
      <!-- Contenido del Modal -->
      <div class="modal-usuario-content" style="padding: 2rem;">
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
            La solicitud con DNI <strong id="numeroDocSolicitudRechazar" style="color: #0f172a; font-weight: 600;"></strong> será rechazada y eliminada permanentemente.
          </p>
          <p style="font-size: 0.875rem; color: #dc2626; font-weight: 600; margin-top: 0.75rem;">
            ⚠️ Esta acción no se puede deshacer
          </p>
        </div>
        
        <!-- Campo para motivo de rechazo (opcional) -->
        <div style="margin-top: 1.5rem;">
          <label for="motivoRechazo" style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
            Motivo de rechazo <span style="color: #9ca3af; font-weight: 400;">(Opcional)</span>
          </label>
          <textarea 
            id="motivoRechazo" 
            name="motivoRechazo" 
            rows="3" 
            placeholder="Ingrese el motivo del rechazo de la solicitud..."
            style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; color: #374151; resize: vertical; transition: border-color 0.2s;"
            onfocus="this.style.borderColor='#667eea'; this.style.outline='none';"
            onblur="this.style.borderColor='#d1d5db';"
          ></textarea>
          <p style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;">
            Este motivo puede ser útil para referencia futura.
          </p>
        </div>
      </div>
      
      <!-- Footer del Modal -->
      <div class="modal-usuario-footer" style="display: flex; gap: 0.75rem; justify-content: space-between;">
        <button type="button" onclick="closeModalConfirmarRechazar()" class="modal-usuario-btn modal-usuario-btn-secondary" style="flex: 0 1 auto; min-width: 140px;">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
          Cancelar
        </button>
        <button type="button" onclick="confirmarRechazarSolicitud()" class="modal-usuario-btn" style="background: linear-gradient(135deg, #ef4444, #dc2626, #b91c1c); color: white; flex: 1; margin-left: 0.75rem; box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.3);">
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
    @keyframes slideIn {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes slideOut {
      from {
        transform: translateX(0);
        opacity: 1;
      }
      to {
        transform: translateX(100%);
        opacity: 0;
      }
    }

    .animate-slide-in {
      animation: slideIn 0.3s ease-out forwards;
    }

    .animate-slide-out {
      animation: slideOut 0.3s ease-in forwards;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    @keyframes pulse {
      0%, 100% {
        opacity: 1;
      }
      50% {
        opacity: 0.8;
      }
    }
    
    #reniecResultado:not(.hidden) {
      animation: fadeIn 0.3s ease-out;
    }
    
    /* Mejoras para botones de acción */
    button[onclick*="abrirModalEditarUsuario"],
    button[onclick*="eliminarUsuario"] {
      position: relative;
      isolation: isolate;
    }
    
    button[onclick*="abrirModalEditarUsuario"]::before,
    button[onclick*="eliminarUsuario"]::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }
    
    button[onclick*="abrirModalEditarUsuario"]:active::before,
    button[onclick*="eliminarUsuario"]:active::before {
      width: 300px;
      height: 300px;
    }
    
    .tab-button {
      background: transparent;
      color: #64748b;
      border: none;
    }
    .tab-button.active {
      background: linear-gradient(to right, #6366f1, #8b5cf6);
      color: white;
      box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
    }
    .tab-content {
      display: block;
    }
    .tab-content.hidden {
      display: none;
    }
  </style>

  <script src="/JS/dashbord.js"></script>
  <script src="/JS/formulario-selec-de-EESS.js"></script>
  <script>
    let solicitudesActuales = [];
    let usuariosActuales = [];
    let estadoActual = 'pendiente'; // Solo mostrar solicitudes pendientes por defecto
    let tabActual = 'solicitudes';
    let paginaActualUsuarios = 1;
    let paginacionUsuarios = null;

    // Función helper para obtener el nombre del establecimiento
    function obtenerNombreEstablecimiento(codigoRed, codigoMicrored, idEstablecimiento) {
      if (!codigoRed || !codigoMicrored || !idEstablecimiento) return 'N/A';
      
      try {
        // Usar los datos del archivo formulario-selec-de-EESS.js si están disponibles
        if (typeof data !== 'undefined' && data[codigoRed] && data[codigoRed][codigoMicrored]) {
          const establecimiento = data[codigoRed][codigoMicrored].find(e => e.value === idEstablecimiento);
          if (establecimiento) return establecimiento.text;
        }
      } catch (e) {
        console.error('Error al obtener nombre de establecimiento:', e);
      }
      
      // Si no se encuentra, mostrar el código sin el prefijo EST_ y con espacios
      return idEstablecimiento.replace(/^EST_/, '').replace(/_/g, ' ') || idEstablecimiento;
    }

    // Cambiar entre tabs
    function cambiarTab(tab) {
      tabActual = tab;
      const tabSolicitudes = document.getElementById('tabSolicitudes');
      const tabUsuarios = document.getElementById('tabUsuarios');
      const seccionSolicitudes = document.getElementById('seccionSolicitudes');
      const seccionUsuarios = document.getElementById('seccionUsuarios');

      if (tab === 'solicitudes') {
        tabSolicitudes.classList.add('active');
        tabUsuarios.classList.remove('active');
        seccionSolicitudes.classList.remove('hidden');
        seccionUsuarios.classList.add('hidden');
        cargarSolicitudes(estadoActual);
      } else {
        tabSolicitudes.classList.remove('active');
        tabUsuarios.classList.add('active');
        seccionSolicitudes.classList.add('hidden');
        seccionUsuarios.classList.remove('hidden');
        cargarUsuarios();
      }
    }

    // Cargar solicitudes
    function cargarSolicitudes(estado = null) {
      // Cargar solo solicitudes pendientes por defecto
      estadoActual = estado || 'pendiente';
      const url = `{{ route('api.solicitudes') }}?estado=${estadoActual}`;
      fetch(url, {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success && data.data) {
          solicitudesActuales = data.data;
          renderizarTabla(solicitudesActuales);
          if (data.estadisticas) {
            // Contar solo solicitudes pendientes para el footer
            const pendientes = solicitudesActuales.filter(s => s.estado === 'pendiente').length;
            actualizarFooterSolicitudes(pendientes || data.estadisticas.pendientes || 0);
          }
        } else {
          console.error('Error al cargar solicitudes:', data.message);
          document.getElementById('tablaSolicitudesBody').innerHTML = '<tr><td colspan="12" class="px-6 py-4 text-center text-slate-500">No se pudieron cargar las solicitudes</td></tr>';
        }
      })
      .catch(error => {
        console.error('Error:', error);
        document.getElementById('tablaSolicitudesBody').innerHTML = '<tr><td colspan="12" class="px-6 py-4 text-center text-red-500">Error al cargar las solicitudes</td></tr>';
      });
    }

    function renderizarTabla(solicitudes) {
      const tbody = document.getElementById('tablaSolicitudesBody');
      if (!tbody) return;

      // Filtrar solo solicitudes pendientes (por si acaso vienen aprobadas)
      const solicitudesPendientes = solicitudes.filter(s => s.estado === 'pendiente');

      if (solicitudesPendientes.length === 0) {
        tbody.innerHTML = '<tr><td colspan="11" class="px-6 py-4 text-center text-slate-500">No hay solicitudes pendientes</td></tr>';
        return;
      }

      const tiposDocumento = {
        1: 'DNI',
        2: 'CE',
        3: 'PASS',
        4: 'DIE',
        5: 'S/ DOCUMENTO',
        6: 'CNV'
      };

      const nombresRedes = {
        1: 'AGUAYTIA',
        2: 'ATALAYA',
        3: 'BAP-CURARAY',
        4: 'CORONEL PORTILLO',
        5: 'ESSALUD',
        6: 'FEDERICO BASADRE - YARINACOCHA',
        7: 'HOSPITAL AMAZONICO - YARINACOCHA',
        8: 'HOSPITAL REGIONAL DE PUCALLPA',
        9: 'NO PERTENECE A NINGUNA RED'
      };


      tbody.innerHTML = solicitudesPendientes.map(solicitud => {
        const fecha = new Date(solicitud.created_at).toLocaleDateString('es-PE');
        const tipoDoc = tiposDocumento[solicitud.id_tipo_documento] || 'N/A';
        const nombreRed = nombresRedes[solicitud.codigo_red] || `Red ${solicitud.codigo_red}`;
        const nombreMicrored = solicitud.codigo_microred || 'N/A';
        const nombreEstablecimiento = obtenerNombreEstablecimiento(
          solicitud.codigo_red, 
          solicitud.codigo_microred, 
          solicitud.id_establecimiento
        );
        const btnAccion = solicitud.estado === 'pendiente' 
          ? `<div class="flex items-center gap-2">
              <button onclick="abrirModalCrearUsuario(${solicitud.id})" 
                class="btn-crear-usuario"
                title="Crear usuario desde esta solicitud">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                  <circle cx="9" cy="7" r="4"></circle>
                  <line x1="19" x2="19" y1="8" y2="14"></line>
                  <line x1="22" x2="16" y1="11" y2="11"></line>
                </svg>
                <span>Crear Usuario</span>
              </button>
              <button onclick="rechazarSolicitud(${solicitud.id}, '${solicitud.numero_documento.replace(/'/g, "\\'")}')" 
                class="btn-rechazar-solicitud"
                title="Rechazar y eliminar esta solicitud">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="3 6 5 6 21 6"></polyline>
                  <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                  <line x1="10" x2="10" y1="11" y2="17"></line>
                  <line x1="14" x2="14" y1="11" y2="17"></line>
                </svg>
                <span>Rechazar</span>
              </button>
            </div>`
          : '<span class="text-slate-400 text-sm font-medium">-</span>';

        return `
          <tr class="hover:bg-slate-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">${tipoDoc}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">${solicitud.numero_documento}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">${nombreRed}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">${nombreMicrored}</td>
            <td class="px-6 py-4 text-sm text-slate-900">${nombreEstablecimiento}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">${solicitud.correo}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">${solicitud.cargo}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">${solicitud.celular}</td>
            <td class="px-6 py-4 text-sm text-slate-900">${solicitud.motivo.substring(0, 50)}${solicitud.motivo.length > 50 ? '...' : ''}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">${fecha}</td>
            <td class="px-6 py-4 whitespace-nowrap">${btnAccion}</td>
          </tr>
        `;
      }).join('');
      
      // Actualizar footer de solicitudes
      actualizarFooterSolicitudes(solicitudesPendientes.length);
    }
    
    function actualizarFooterSolicitudes(total) {
      const totalElement = document.getElementById('totalSolicitudes');
      const fechaElement = document.getElementById('ultimaActualizacionSolicitudes');
      
      if (totalElement) {
        totalElement.textContent = total;
      }
      
      if (fechaElement) {
        const ahora = new Date();
        fechaElement.textContent = ahora.toLocaleTimeString('es-PE', { hour: '2-digit', minute: '2-digit' });
      }
    }

    function filtrarTablaSolicitudes() {
      const searchTerm = document.getElementById('searchInputSolicitudes').value.toLowerCase();
      const filas = document.querySelectorAll('#tablaSolicitudesBody tr');
      
      filas.forEach(fila => {
        const texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(searchTerm) ? '' : 'none';
      });
    }

    // Cargar usuarios
    function cargarUsuarios(pagina = 1) {
      paginaActualUsuarios = pagina;
      const rol = document.getElementById('filtroRol')?.value || '';
      const url = `{{ route("api.usuarios") }}?page=${pagina}&per_page=10${rol ? '&rol=' + rol : ''}`;
      
      return fetch(url, {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
          'Accept': 'application/json'
        }
      })
      .then(response => {
        // Verificar si la respuesta es un error 403 (Forbidden)
        if (response.status === 403) {
          return response.json().then(data => {
            throw new Error(data.message || 'No tiene permisos para acceder a esta funcionalidad');
          });
        }
        // Verificar otros errores HTTP
        if (!response.ok) {
          return response.json().then(data => {
            throw new Error(data.message || 'Error al cargar los usuarios');
          });
        }
        return response.json();
      })
      .then(data => {
        console.log('Datos recibidos de la API:', data);
        if (data.success && data.data) {
          usuariosActuales = data.data;
          console.log('Usuarios actuales:', usuariosActuales);
          paginacionUsuarios = data.pagination;
          renderizarTablaUsuarios(usuariosActuales);
          renderizarPaginacionUsuarios(paginacionUsuarios);
          actualizarFooterUsuarios(data.estadisticas, paginacionUsuarios);
          return data; // Devolver los datos para verificación
        } else {
          console.error('Error al cargar usuarios:', data.message);
          const tbody = document.getElementById('tablaUsuariosBody');
          if (tbody) {
            tbody.innerHTML = `<tr><td colspan="11" class="px-6 py-4 text-center text-red-500">
              <div class="flex flex-col items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="12" x2="12" y1="8" y2="12"></line>
                  <line x1="12" x2="12.01" y1="16" y2="16"></line>
                </svg>
                <span class="font-semibold">${data.message || 'No se pudieron cargar los usuarios'}</span>
              </div>
            </td></tr>`;
          }
          throw new Error(data.message || 'Error al cargar usuarios');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        const tbody = document.getElementById('tablaUsuariosBody');
        if (tbody) {
          tbody.innerHTML = `<tr><td colspan="11" class="px-6 py-4 text-center text-red-500">
            <div class="flex flex-col items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" x2="12" y1="8" y2="12"></line>
                <line x1="12" x2="12.01" y1="16" y2="16"></line>
              </svg>
              <span class="font-semibold">${error.message || 'Error al cargar los usuarios'}</span>
              <span class="text-sm text-slate-600">Verifique que tenga permisos de administrador (DIRESA)</span>
            </div>
          </td></tr>`;
        }
        throw error; // Re-lanzar el error para que pueda ser manejado
      });
    }

    function renderizarTablaUsuarios(usuarios) {
      const tbody = document.getElementById('tablaUsuariosBody');
      if (!tbody) return;

      if (usuarios.length === 0) {
        tbody.innerHTML = '<tr><td colspan="11" class="px-6 py-4 text-center text-slate-500">No hay usuarios registrados</td></tr>';
        return;
      }

      const rolesBadge = {
        'admin': '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Administrador</span>',
        'medico': '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Médico</span>',
        'usuario': '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Usuario</span>',
        'jefe_microred': '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">Jefe de Red</span>',
        'coordinador_red': '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">Coordinador de MicroRed</span>'
      };

      // Función helper para obtener nombre de establecimiento
      function obtenerNombreEstablecimiento(codigoRed, codigoMicrored, idEstablecimiento) {
        if (!idEstablecimiento) return 'N/A';
        try {
          if (typeof data !== 'undefined' && data[codigoRed] && data[codigoRed][codigoMicrored]) {
            const establecimiento = data[codigoRed][codigoMicrored].find(e => e.value === idEstablecimiento);
            if (establecimiento) return establecimiento.text;
          }
        } catch (e) {
          console.error('Error al obtener nombre de establecimiento:', e);
        }
        return idEstablecimiento.replace(/^EST_/, '').replace(/_/g, ' ') || idEstablecimiento;
      }

      console.log('Renderizando tabla con', usuarios.length, 'usuarios');
      tbody.innerHTML = usuarios.map(usuario => {
        console.log('Procesando usuario:', usuario);
        const rolBadge = rolesBadge[usuario.role] || rolesBadge['usuario'];
        const tipoDoc = usuario.tipo_documento || 'N/A';
        const numDoc = usuario.numero_documento || 'N/A';
        const red = usuario.red || 'N/A';
        const microred = usuario.microred || 'N/A';
        const establecimiento = usuario.establecimiento ? obtenerNombreEstablecimiento(usuario.codigo_red, usuario.codigo_microred, usuario.establecimiento) : 'N/A';
        const correo = usuario.correo || usuario.email || 'N/A';
        const cargo = usuario.cargo || 'N/A';
        const celular = usuario.celular || 'N/A';
        const motivo = usuario.motivo || 'N/A';

        return `
          <tr class="hover:bg-slate-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">${tipoDoc}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">${numDoc}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">${red}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">${microred}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900" title="${usuario.establecimiento || ''}">${establecimiento}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">${correo}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">${cargo}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">${celular}</td>
            <td class="px-6 py-4 text-sm text-slate-900" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${motivo}">${motivo}</td>
            <td class="px-6 py-4 whitespace-nowrap">${rolBadge}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center gap-2.5">
                <button onclick="abrirModalEditarUsuario(${usuario.id})" 
                  class="btn-editar-usuario"
                  title="Editar usuario">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                  </svg>
                  <span>Editar</span>
                </button>
                <button onclick="eliminarUsuario(${usuario.id}, '${usuario.name.replace(/'/g, "\\'")}')" 
                  class="btn-eliminar-usuario"
                  title="Eliminar usuario">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                  </svg>
                  <span>Eliminar</span>
                </button>
              </div>
            </td>
          </tr>
        `;
      }).join('');
    }
    
    function actualizarFooterUsuarios(estadisticas, pagination) {
      const totalElement = document.getElementById('totalUsuarios');
      const fechaElement = document.getElementById('ultimaActualizacionUsuarios');
      
      if (totalElement && estadisticas) {
        totalElement.textContent = estadisticas.total || 0;
      }
      
      if (fechaElement) {
        const ahora = new Date();
        fechaElement.textContent = ahora.toLocaleTimeString('es-PE', { hour: '2-digit', minute: '2-digit' });
      }
    }

    function filtrarTablaUsuarios() {
      const searchTerm = document.getElementById('searchInputUsuarios').value.toLowerCase();
      const filas = document.querySelectorAll('#tablaUsuariosBody tr');
      
      filas.forEach(fila => {
        const texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(searchTerm) ? '' : 'none';
      });
    }

    function cambiarRol() {
      // Recargar usuarios desde la página 1 cuando se cambia el filtro
      cargarUsuarios(1);
    }

    function renderizarPaginacionUsuarios(pagination) {
      const contenedor = document.getElementById('paginacionUsuarios');
      if (!contenedor || !pagination) {
        return;
      }

      const { current_page, last_page, total, from, to } = pagination;

      if (last_page <= 1) {
        contenedor.innerHTML = '';
        return;
      }

      let html = '<div class="flex items-center justify-between">';
      
      // Información de resultados
      html += `<div class="text-sm text-slate-600">
        Mostrando <span class="font-semibold text-slate-900">${from || 0}</span> a 
        <span class="font-semibold text-slate-900">${to || 0}</span> de 
        <span class="font-semibold text-slate-900">${total}</span> usuarios
      </div>`;

      // Controles de paginación
      html += '<div class="flex items-center gap-2">';
      
      // Botón Anterior
      if (current_page > 1) {
        html += `<button onclick="cargarUsuarios(${current_page - 1})" 
          class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
          Anterior
        </button>`;
      } else {
        html += `<button disabled 
          class="px-4 py-2 text-sm font-medium text-slate-400 bg-slate-100 border border-slate-200 rounded-lg cursor-not-allowed">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
          Anterior
        </button>`;
      }

      // Números de página
      html += '<div class="flex items-center gap-1">';
      const maxPages = 5;
      let startPage = Math.max(1, current_page - Math.floor(maxPages / 2));
      let endPage = Math.min(last_page, startPage + maxPages - 1);
      
      if (endPage - startPage < maxPages - 1) {
        startPage = Math.max(1, endPage - maxPages + 1);
      }

      if (startPage > 1) {
        html += `<button onclick="cargarUsuarios(1)" 
          class="px-3 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">1</button>`;
        if (startPage > 2) {
          html += '<span class="px-2 text-slate-400">...</span>';
        }
      }

      for (let i = startPage; i <= endPage; i++) {
        if (i === current_page) {
          html += `<button disabled 
            class="px-3 py-2 text-sm font-semibold text-white bg-purple-600 border border-purple-600 rounded-lg">${i}</button>`;
        } else {
          html += `<button onclick="cargarUsuarios(${i})" 
            class="px-3 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">${i}</button>`;
        }
      }

      if (endPage < last_page) {
        if (endPage < last_page - 1) {
          html += '<span class="px-2 text-slate-400">...</span>';
        }
        html += `<button onclick="cargarUsuarios(${last_page})" 
          class="px-3 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">${last_page}</button>`;
      }

      html += '</div>';

      // Botón Siguiente
      if (current_page < last_page) {
        html += `<button onclick="cargarUsuarios(${current_page + 1})" 
          class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
          Siguiente
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
        </button>`;
      } else {
        html += `<button disabled 
          class="px-4 py-2 text-sm font-medium text-slate-400 bg-slate-100 border border-slate-200 rounded-lg cursor-not-allowed">
          Siguiente
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
        </button>`;
      }

      html += '</div></div>';
      contenedor.innerHTML = html;
    }

    // Función anterior de cambiarRol (mantener por compatibilidad si se usa en otro lugar)
    function cambiarRolFiltro() {
      const rol = document.getElementById('filtroRol').value;
      const filas = document.querySelectorAll('#tablaUsuariosBody tr');
      
      filas.forEach(fila => {
        const rolBadge = fila.querySelector('td:nth-child(3) span');
        if (!rolBadge) return;
        
        const rolUsuario = rolBadge.textContent.toLowerCase();
        let coincide = false;
        
        if (!rol) {
          // Si no hay filtro, mostrar todos
          coincide = true;
        } else if (rol === 'jefe_microred') {
          // Filtrar por Jefe de Red
          coincide = rolUsuario.includes('jefe');
        } else if (rol === 'coordinador_red') {
          // Filtrar por Coordinador de MicroRed
          coincide = rolUsuario.includes('coordinador');
        }
        
        fila.style.display = coincide ? '' : 'none';
      });
    }

    // Función para abrir el modal de edición de usuario
    async function abrirModalEditarUsuario(usuarioId) {
      try {
        // Buscar el usuario en la lista actual
        const usuario = usuariosActuales.find(u => u.id === usuarioId);
        
        if (!usuario) {
          alert('No se pudo encontrar la información del usuario');
          return;
        }

        // Prellenar el formulario de usuario
        document.getElementById('usuarioIdEditar').value = usuario.id;
        document.getElementById('nombreUsuarioEditar').value = usuario.name || '';
        document.getElementById('correoUsuarioEditar').value = usuario.email || '';
        document.getElementById('rolUsuarioEditar').value = usuario.role || '';
        const passwordInput = document.getElementById('nuevaPasswordEditar');
        if (passwordInput) {
          passwordInput.value = '';
          passwordInput.type = 'password';
          // Resetear iconos de visibilidad
          const eyeIcon = document.getElementById('eyeIcon-nuevaPasswordEditar');
          const eyeOffIcon = document.getElementById('eyeOffIcon-nuevaPasswordEditar');
          if (eyeIcon && eyeOffIcon) {
            eyeIcon.classList.add('hidden');
            eyeOffIcon.classList.remove('hidden');
          }
        }

        // Asegurar que la sección de solicitud esté siempre visible
        const seccionSolicitud = document.getElementById('seccionSolicitud');
        if (seccionSolicitud) {
          seccionSolicitud.style.display = 'block';
        }
        
        // Cargar datos de la solicitud si existe
        try {
          console.log('Cargando solicitud para usuario ID:', usuarioId);
          const response = await fetch(`/api/solicitudes?user_id=${usuarioId}&estado=all`, {
            headers: {
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            }
          });
          
          console.log('Respuesta de solicitudes:', response.status, response.statusText);
          
          if (response.ok) {
            const data = await response.json();
            console.log('Datos de solicitud recibidos:', data);
            
            if (data.success && data.data && data.data.length > 0) {
              const solicitud = data.data[0]; // Tomar la primera solicitud
              console.log('Solicitud encontrada:', solicitud);
              
              // Prellenar campos de solicitud con validación
              const tipoDocInput = document.getElementById('tipoDocumentoSolicitud');
              const numDocInput = document.getElementById('numeroDocumentoSolicitud');
              const codRedInput = document.getElementById('codigoRedSolicitud');
              const motivoInput = document.getElementById('motivoSolicitud');
              const cargoInput = document.getElementById('cargoSolicitud');
              const celularInput = document.getElementById('celularSolicitud');
              
              if (tipoDocInput) tipoDocInput.value = '1'; // Siempre DNI
              if (document.getElementById('tipoDocumentoSolicitudHidden')) {
                document.getElementById('tipoDocumentoSolicitudHidden').value = '1';
              }
              if (numDocInput) numDocInput.value = solicitud.numero_documento || '';
              if (codRedInput) codRedInput.value = solicitud.codigo_red || '';
              if (motivoInput) motivoInput.value = solicitud.motivo || '';
              if (cargoInput) cargoInput.value = solicitud.cargo || '';
              if (celularInput) celularInput.value = solicitud.celular || '';
              
              // Guardar ID de solicitud para actualización
              const usuarioIdInput = document.getElementById('usuarioIdEditar');
              if (usuarioIdInput) {
                usuarioIdInput.setAttribute('data-solicitud-id', solicitud.id);
                console.log('ID de solicitud guardado:', solicitud.id);
              }
              
              // Cargar microredes y establecimientos
              if (solicitud.codigo_red) {
                console.log('Cargando microredes para red:', solicitud.codigo_red);
                await cargarMicroredesSolicitud(solicitud.codigo_red, solicitud.codigo_microred);
                if (solicitud.codigo_microred) {
                  console.log('Cargando establecimientos para microred:', solicitud.codigo_microred);
                  await cargarEstablecimientosSolicitud(solicitud.codigo_red, solicitud.codigo_microred, solicitud.id_establecimiento);
                }
              }
            } else {
              console.log('No se encontró solicitud para este usuario');
              // Si no hay solicitud, limpiar los campos pero mantener la sección visible
              const tipoDocInput = document.getElementById('tipoDocumentoSolicitud');
              const numDocInput = document.getElementById('numeroDocumentoSolicitud');
              const codRedInput = document.getElementById('codigoRedSolicitud');
              const microredSelect = document.getElementById('codigoMicroredSolicitud');
              const establecimientoSelect = document.getElementById('idEstablecimientoSolicitud');
              const motivoInput = document.getElementById('motivoSolicitud');
              const cargoInput = document.getElementById('cargoSolicitud');
              const celularInput = document.getElementById('celularSolicitud');
              const usuarioIdInput = document.getElementById('usuarioIdEditar');
              
              // Siempre establecer tipo de documento como DNI (1)
              if (tipoDocInput) tipoDocInput.value = '1';
              if (document.getElementById('tipoDocumentoSolicitudHidden')) {
                document.getElementById('tipoDocumentoSolicitudHidden').value = '1';
              }
              if (numDocInput) numDocInput.value = '';
              if (codRedInput) codRedInput.value = '';
              if (microredSelect) {
                microredSelect.innerHTML = '<option value="">Seleccione una Microred</option>';
                microredSelect.disabled = true;
              }
              if (establecimientoSelect) {
                establecimientoSelect.innerHTML = '<option value="">Seleccione un Establecimiento</option>';
                establecimientoSelect.disabled = true;
              }
              if (motivoInput) motivoInput.value = '';
              if (cargoInput) cargoInput.value = '';
              if (celularInput) celularInput.value = '';
              // Remover ID de solicitud si existe
              if (usuarioIdInput) {
                usuarioIdInput.removeAttribute('data-solicitud-id');
              }
            }
            
            // Agregar listeners siempre (tanto si hay solicitud como si no)
            const codigoRedSelect = document.getElementById('codigoRedSolicitud');
            if (codigoRedSelect) {
              // Remover listener anterior si existe para evitar duplicados
              const nuevoCodigoRedSelect = codigoRedSelect.cloneNode(true);
              codigoRedSelect.parentNode.replaceChild(nuevoCodigoRedSelect, codigoRedSelect);
              
              nuevoCodigoRedSelect.addEventListener('change', async function() {
                const redValue = this.value;
                if (redValue) {
                  await cargarMicroredesSolicitud(redValue);
                } else {
                  document.getElementById('codigoMicroredSolicitud').innerHTML = '<option value="">Seleccione una Microred</option>';
                  document.getElementById('codigoMicroredSolicitud').disabled = true;
                  document.getElementById('idEstablecimientoSolicitud').innerHTML = '<option value="">Seleccione un Establecimiento</option>';
                  document.getElementById('idEstablecimientoSolicitud').disabled = true;
                }
              });
            }
            
            // Agregar listener para cambio de microred
            const codigoMicroredSelect = document.getElementById('codigoMicroredSolicitud');
            if (codigoMicroredSelect) {
              // Remover listener anterior si existe para evitar duplicados
              const nuevoCodigoMicroredSelect = codigoMicroredSelect.cloneNode(true);
              codigoMicroredSelect.parentNode.replaceChild(nuevoCodigoMicroredSelect, codigoMicroredSelect);
              
              nuevoCodigoMicroredSelect.addEventListener('change', async function() {
                const microredValue = this.value;
                const redValue = document.getElementById('codigoRedSolicitud').value;
                if (microredValue && redValue) {
                  await cargarEstablecimientosSolicitud(redValue, microredValue);
                } else {
                  document.getElementById('idEstablecimientoSolicitud').innerHTML = '<option value="">Seleccione un Establecimiento</option>';
                  document.getElementById('idEstablecimientoSolicitud').disabled = true;
                }
              });
            }
          } else {
            // Si hay error, mantener la sección visible pero sin datos
            if (seccionSolicitud) {
              console.error('Error al cargar solicitud: respuesta no OK');
            }
          }
        } catch (error) {
          console.error('Error al cargar solicitud:', error);
          // Mantener la sección visible incluso si hay error
        }

        // Mostrar el modal
        const modal = document.getElementById('modalEditarUsuario');
        if (modal) {
          modal.classList.add('show');
          modal.scrollTop = 0;
        }
      } catch (error) {
        console.error('Error al abrir modal de edición:', error);
        alert('Error al cargar los datos del usuario. Por favor, intente nuevamente.');
      }
    }

    // Función para cerrar el modal de edición
    function closeModalEditarUsuario(event) {
      if (event && event.target === event.currentTarget) {
        document.getElementById('modalEditarUsuario').classList.remove('show');
      } else if (!event) {
        document.getElementById('modalEditarUsuario').classList.remove('show');
      }
    }

    // Función para actualizar usuario
    async function actualizarUsuario(event) {
      event.preventDefault();
      
      const usuarioId = document.getElementById('usuarioIdEditar').value;
      const nombre = document.getElementById('nombreUsuarioEditar').value;
      const correo = document.getElementById('correoUsuarioEditar').value;
      const rol = document.getElementById('rolUsuarioEditar').value;
      const nuevaPassword = document.getElementById('nuevaPasswordEditar').value;

      if (!nombre || !correo || !rol) {
        // Mostrar mensaje de error de validación
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
            <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Campos requeridos</div>
            <div style="font-size: 0.75rem; opacity: 0.95;">Por favor, complete todos los campos requeridos</div>
          </div>
        `;
        document.body.appendChild(errorDiv);
        setTimeout(() => {
          errorDiv.classList.add('animate-slide-out');
          setTimeout(() => errorDiv.remove(), 300);
        }, 4000);
        return;
      }

      try {
        const formData = {
          usuario_id: usuarioId,
          name: nombre,
          email: correo,
          role: rol
        };
        
        // Solo agregar password si tiene un valor (no vacío)
        if (nuevaPassword && nuevaPassword.trim() !== '') {
          if (nuevaPassword.length < 6) {
            alert('La contraseña debe tener al menos 6 caracteres');
            return;
          }
          formData.password = nuevaPassword.trim();
        }

        // Obtener datos de solicitud si existen
        const solicitudId = document.getElementById('usuarioIdEditar').getAttribute('data-solicitud-id');
        if (solicitudId) {
          formData.solicitud_id = solicitudId;
          // Usar el campo hidden para el tipo de documento (siempre DNI = 1)
          const tipoDocHidden = document.getElementById('tipoDocumentoSolicitudHidden');
          formData.id_tipo_documento = tipoDocHidden ? tipoDocHidden.value : '1';
          formData.numero_documento = document.getElementById('numeroDocumentoSolicitud').value.trim();
          formData.codigo_red = document.getElementById('codigoRedSolicitud').value;
          formData.codigo_microred = document.getElementById('codigoMicroredSolicitud').value;
          formData.id_establecimiento = document.getElementById('idEstablecimientoSolicitud').value;
          formData.motivo = document.getElementById('motivoSolicitud').value.trim();
          formData.cargo = document.getElementById('cargoSolicitud').value.trim();
          formData.celular = document.getElementById('celularSolicitud').value.trim();
        }

        // Actualizar usuario (y solicitud si existe)
        const response = await fetch('/api/usuarios/' + usuarioId, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json'
          },
          body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (!response.ok) {
          // Manejar errores de validación
          let errorMessage = 'Error al actualizar el usuario';
          if (data.errors) {
            const errors = Object.values(data.errors).flat();
            errorMessage = errors.join(', ');
          } else if (data.message) {
            errorMessage = data.message;
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
              <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Error al actualizar</div>
              <div style="font-size: 0.75rem; opacity: 0.95;">${errorMessage}</div>
            </div>
          `;
          document.body.appendChild(errorDiv);
          setTimeout(() => {
            errorDiv.classList.add('animate-slide-out');
            setTimeout(() => errorDiv.remove(), 300);
          }, 5000);
          return;
        }

        if (data.success) {
          // Cerrar el modal
          closeModalEditarUsuario();

          // Mostrar mensaje de éxito
          const solicitudId = document.getElementById('usuarioIdEditar').getAttribute('data-solicitud-id');
          const mensajeExito = solicitudId ? 'Usuario y solicitud actualizados exitosamente' : 'Usuario actualizado exitosamente';
          const mensajeDetalle = solicitudId 
            ? 'Los cambios del usuario y los datos de la solicitud han sido guardados correctamente.'
            : 'Los cambios del usuario han sido guardados correctamente.';
          
          const successMessage = document.createElement('div');
          successMessage.className = 'mensaje-exito animate-slide-in';
          successMessage.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
          successMessage.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
              <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <div style="flex: 1;">
              <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">${mensajeExito}</div>
              <div style="font-size: 0.75rem; opacity: 0.95;">${mensajeDetalle}</div>
            </div>
          `;
          document.body.appendChild(successMessage);
          
          setTimeout(() => {
            successMessage.classList.add('animate-slide-out');
            setTimeout(() => successMessage.remove(), 300);
          }, 4000);

          // Recargar la lista de usuarios
          cargarUsuarios();
        } else {
          // Mostrar mensaje de error
          const errorMessage = data.message || 'Error al actualizar el usuario';
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
              <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Error al actualizar usuario</div>
              <div style="font-size: 0.75rem; opacity: 0.95;">${errorMessage}</div>
            </div>
          `;
          document.body.appendChild(errorDiv);
          
          setTimeout(() => {
            errorDiv.classList.add('animate-slide-out');
            setTimeout(() => errorDiv.remove(), 300);
          }, 5000);
        }
      } catch (error) {
        console.error('Error al actualizar usuario:', error);
        
        // Mostrar mensaje de error
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
            <div style="font-size: 0.75rem; opacity: 0.95;">Error al actualizar el usuario. Por favor, intente nuevamente.</div>
          </div>
        `;
        document.body.appendChild(errorDiv);
        
        setTimeout(() => {
          errorDiv.classList.add('animate-slide-out');
          setTimeout(() => errorDiv.remove(), 300);
        }, 5000);
      }
    }

    // Variables para el modal de confirmación
    let usuarioIdAEliminar = null;
    let nombreUsuarioAEliminar = '';

    // Variables globales para el modal de rechazo
    let solicitudIdARechazar = null;
    let numeroDocSolicitud = '';

    // Función para abrir el modal de confirmación de rechazo
    function rechazarSolicitud(solicitudId, numeroDoc) {
      solicitudIdARechazar = solicitudId;
      numeroDocSolicitud = numeroDoc;
      
      // Mostrar el DNI en el modal
      document.getElementById('numeroDocSolicitudRechazar').textContent = numeroDoc;
      
      // Limpiar el campo de motivo
      const motivoInput = document.getElementById('motivoRechazo');
      if (motivoInput) {
        motivoInput.value = '';
      }
      
      // Abrir el modal
      const modal = document.getElementById('modalConfirmarRechazar');
      if (modal) {
        modal.classList.add('show');
      }
    }

    // Función para cerrar el modal de confirmación de rechazo
    function closeModalConfirmarRechazar(event) {
      if (event && event.target === event.currentTarget) {
        document.getElementById('modalConfirmarRechazar').classList.remove('show');
      } else if (!event) {
        document.getElementById('modalConfirmarRechazar').classList.remove('show');
      }
      // Limpiar variables
      solicitudIdARechazar = null;
      numeroDocSolicitud = '';
    }

    // Función para confirmar y ejecutar el rechazo
    async function confirmarRechazarSolicitud() {
      if (!solicitudIdARechazar) {
        console.error('No hay solicitud seleccionada para rechazar');
        return;
      }

      // Obtener el motivo de rechazo (opcional)
      const motivoInput = document.getElementById('motivoRechazo');
      const motivo = motivoInput ? motivoInput.value.trim() : '';

      // Obtener los botones para deshabilitarlos y mostrar loading
      const btnRechazar = document.querySelector('#modalConfirmarRechazar button[onclick*="confirmarRechazarSolicitud"]');
      const btnCancelar = document.querySelector('#modalConfirmarRechazar button[onclick*="closeModalConfirmarRechazar"]');
      
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
        const response = await fetch(`{{ route("api.solicitudes.destroy", ":id") }}`.replace(':id', solicitudIdARechazar), {
          method: 'DELETE',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
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

        if (response.ok && data.success) {
          // Cerrar el modal
          closeModalConfirmarRechazar();

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
              <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Solicitud rechazada</div>
              <div style="font-size: 0.75rem; opacity: 0.95;">La solicitud con DNI ${numeroDocSolicitud} ha sido rechazada y eliminada permanentemente</div>
            </div>
          `;
          document.body.appendChild(successMessage);
          
          setTimeout(() => {
            successMessage.classList.add('animate-slide-out');
            setTimeout(() => successMessage.remove(), 300);
          }, 4000);

          // Recargar la tabla de solicitudes
          await cargarSolicitudes();
        } else {
          // Mostrar mensaje de error
          const errorMessage = data.message || 'Error al rechazar la solicitud';
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
              <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Error al rechazar solicitud</div>
              <div style="font-size: 0.75rem; opacity: 0.95;">${errorMessage}</div>
            </div>
          `;
          document.body.appendChild(errorDiv);
          
          setTimeout(() => {
            errorDiv.classList.add('animate-slide-out');
            setTimeout(() => errorDiv.remove(), 300);
          }, 5000);
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

        // Mostrar mensaje de error
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
            <div style="font-size: 0.75rem; opacity: 0.95;">No se pudo conectar con el servidor. Por favor, intente nuevamente.</div>
          </div>
        `;
        document.body.appendChild(errorDiv);
        
        setTimeout(() => {
          errorDiv.classList.add('animate-slide-out');
          setTimeout(() => errorDiv.remove(), 300);
        }, 5000);
      }
    }

    // Función para abrir el modal de confirmación de eliminación
    function eliminarUsuario(usuarioId, nombreUsuario) {
      usuarioIdAEliminar = usuarioId;
      nombreUsuarioAEliminar = nombreUsuario;
      
      // Mostrar el nombre del usuario en el modal
      document.getElementById('nombreUsuarioEliminar').textContent = nombreUsuario;
      
      // Abrir el modal
      const modal = document.getElementById('modalConfirmarEliminar');
      if (modal) {
        modal.classList.add('show');
      }
    }

    // Función para cerrar el modal de confirmación
    function closeModalConfirmarEliminar(event) {
      if (event && event.target === event.currentTarget) {
        document.getElementById('modalConfirmarEliminar').classList.remove('show');
      } else if (!event) {
        document.getElementById('modalConfirmarEliminar').classList.remove('show');
      }
      // Limpiar variables
      usuarioIdAEliminar = null;
      nombreUsuarioAEliminar = '';
    }

    // Función para confirmar y ejecutar la eliminación
    async function confirmarEliminarUsuario() {
      if (!usuarioIdAEliminar) {
        console.error('No hay usuario seleccionado para eliminar');
        return;
      }

      // Obtener el botón de eliminar para deshabilitarlo y mostrar loading
      const btnEliminar = document.querySelector('#modalConfirmarEliminar button[onclick*="confirmarEliminarUsuario"]');
      const btnCancelar = document.querySelector('#modalConfirmarEliminar button[onclick*="closeModalConfirmarEliminar"]');
      
      // Guardar el contenido original del botón
      const contenidoOriginal = btnEliminar ? btnEliminar.innerHTML : '';
      
      // Deshabilitar botones y mostrar loading
      if (btnEliminar) {
        btnEliminar.disabled = true;
        btnEliminar.innerHTML = `
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

      try {
        const response = await fetch('{{ route("api.usuarios.destroy", ":id") }}'.replace(':id', usuarioIdAEliminar), {
          method: 'DELETE',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          }
        });

        // Verificar si la respuesta es JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
          throw new Error('El servidor no devolvió una respuesta JSON válida');
        }

        const data = await response.json();

        // Restaurar botones
        if (btnEliminar) {
          btnEliminar.disabled = false;
          btnEliminar.innerHTML = contenidoOriginal;
        }
        if (btnCancelar) {
          btnCancelar.disabled = false;
        }

        // Verificar respuesta del servidor
        if (response.ok && data.success) {
          // Cerrar el modal
          closeModalConfirmarEliminar();

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
              <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Usuario eliminado exitosamente</div>
              <div style="font-size: 0.75rem; opacity: 0.95;">El usuario "${nombreUsuarioAEliminar}" ha sido eliminado del sistema</div>
            </div>
          `;
          document.body.appendChild(successMessage);
          
          setTimeout(() => {
            successMessage.classList.add('animate-slide-out');
            setTimeout(() => successMessage.remove(), 300);
          }, 4000);

          // Recargar la lista de usuarios para verificar que se eliminó
          await cargarUsuarios();

          // Verificar que el usuario ya no está en la lista
          setTimeout(() => {
            const usuarioEliminado = usuariosActuales.find(u => u.id === usuarioIdAEliminar);
            if (usuarioEliminado) {
              console.warn('El usuario aún aparece en la lista después de eliminarlo');
              // Forzar recarga
              cargarUsuarios();
            } else {
              console.log('Usuario eliminado correctamente y verificado');
            }
          }, 500);

        } else {
          // Mostrar mensaje de error
          const errorMessage = data.message || 'Error al eliminar el usuario';
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
              <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Error al eliminar usuario</div>
              <div style="font-size: 0.75rem; opacity: 0.95;">${errorMessage}</div>
            </div>
          `;
          document.body.appendChild(errorDiv);
          
          setTimeout(() => {
            errorDiv.classList.add('animate-slide-out');
            setTimeout(() => errorDiv.remove(), 300);
          }, 5000);
        }
      } catch (error) {
        console.error('Error al eliminar usuario:', error);
        
        // Restaurar botones en caso de error
        if (btnEliminar) {
          btnEliminar.disabled = false;
          btnEliminar.innerHTML = contenidoOriginal;
        }
        if (btnCancelar) {
          btnCancelar.disabled = false;
        }

        // Mostrar mensaje de error
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
            <div style="font-size: 0.75rem; opacity: 0.95;">No se pudo conectar con el servidor. Por favor, intente nuevamente.</div>
          </div>
        `;
        document.body.appendChild(errorDiv);
        
        setTimeout(() => {
          errorDiv.classList.add('animate-slide-out');
          setTimeout(() => errorDiv.remove(), 300);
        }, 5000);
      }
    }

    function abrirModalCrearUsuario(solicitudId) {
      console.log('Abriendo modal para solicitud ID:', solicitudId);
      console.log('Solicitudes actuales:', solicitudesActuales);
      
      const solicitud = solicitudesActuales.find(s => s.id === solicitudId);
      if (!solicitud) {
        console.error('Solicitud no encontrada:', solicitudId);
        alert('Error: No se pudo encontrar la solicitud seleccionada.');
        return;
      }

      console.log('Solicitud encontrada:', solicitud);

      const tiposDocumento = {
        1: 'DNI',
        2: 'CE',
        3: 'PASS',
        4: 'DIE',
        5: 'S/ DOCUMENTO',
        6: 'CNV'
      };

      const redes = {
        1: 'AGUAYTIA',
        2: 'ATALAYA',
        3: 'BAP-CURARAY',
        4: 'CORONEL PORTILLO',
        5: 'ESSALUD',
        6: 'FEDERICO BASADRE - YARINACOCHA',
        7: 'HOSPITAL AMAZONICO - YARINACOCHA',
        8: 'HOSPITAL REGIONAL DE PUCALLPA',
        9: 'NO PERTENECE A NINGUNA RED'
      };

      // Prellenar todos los campos del modal
      try {
        // ID de la solicitud (oculto)
        const solicitudIdInput = document.getElementById('solicitudId');
        if (solicitudIdInput) solicitudIdInput.value = solicitud.id || '';

        // Información del Documento
        const tipoDocInput = document.getElementById('tipoDocumento');
        if (tipoDocInput) tipoDocInput.value = tiposDocumento[solicitud.id_tipo_documento] || 'N/A';

        const numDocInput = document.getElementById('numeroDocumento');
        if (numDocInput) numDocInput.value = solicitud.numero_documento || '';

        // Información del Establecimiento
        const redInput = document.getElementById('red');
        if (redInput) redInput.value = redes[solicitud.codigo_red] || `Código: ${solicitud.codigo_red}`;

        const microredInput = document.getElementById('microred');
        if (microredInput) microredInput.value = solicitud.codigo_microred || 'N/A';

        const establecimientoInput = document.getElementById('establecimiento');
        if (establecimientoInput) {
          const nombreEstablecimiento = obtenerNombreEstablecimiento(
            solicitud.codigo_red, 
            solicitud.codigo_microred, 
            solicitud.id_establecimiento
          );
          establecimientoInput.value = nombreEstablecimiento || solicitud.id_establecimiento || 'N/A';
        }

        // Información Adicional
        const motivoInput = document.getElementById('motivo');
        if (motivoInput) motivoInput.value = solicitud.motivo || '';

        const cargoInput = document.getElementById('cargo');
        if (cargoInput) cargoInput.value = solicitud.cargo || '';

        // Contacto
        const celularInput = document.getElementById('celular');
        if (celularInput) celularInput.value = solicitud.celular || '';

        const correoInput = document.getElementById('correo');
        if (correoInput) correoInput.value = solicitud.correo || '';

        // Prellenar campos de RENIEC con datos de la solicitud
        // Solo DNI está permitido, así que siempre usar valor 1
        const reniecTipoDoc = document.getElementById('reniecTipoDoc');
        if (reniecTipoDoc) reniecTipoDoc.value = '1'; // Siempre DNI
        
        const reniecTipoDocHidden = document.getElementById('reniecTipoDocHidden');
        if (reniecTipoDocHidden) reniecTipoDocHidden.value = '1';

        const reniecNumeroDoc = document.getElementById('reniecNumeroDoc');
        if (reniecNumeroDoc) {
          // Solo prellenar si el tipo de documento de la solicitud es DNI (1)
          if (solicitud.id_tipo_documento === 1 || solicitud.id_tipo_documento === '1') {
            reniecNumeroDoc.value = solicitud.numero_documento || '';
          } else {
            reniecNumeroDoc.value = '';
          }
        }

        // Limpiar resultado de RENIEC
        const reniecResultado = document.getElementById('reniecResultado');
        if (reniecResultado) reniecResultado.classList.add('hidden');
        const reniecError = document.getElementById('reniecError');
        if (reniecError) reniecError.classList.add('hidden');

        // Campos de creación de usuario (inicializar vacíos)
        const nameUsuarioInput = document.getElementById('nameUsuario');
        if (nameUsuarioInput) {
          nameUsuarioInput.value = '';
          nameUsuarioInput.readOnly = true;
        }

        const passwordUsuarioInput = document.getElementById('passwordUsuario');
        if (passwordUsuarioInput) {
          passwordUsuarioInput.value = '';
        }

        const correoUsuarioInput = document.getElementById('correoUsuario');
        if (correoUsuarioInput) {
          correoUsuarioInput.value = solicitud.correo || '';
        }

        const rolUsuarioInput = document.getElementById('rolUsuario');
        if (rolUsuarioInput) rolUsuarioInput.value = ''; // Resetear rol

        // Mostrar el modal
        const modal = document.getElementById('modalCrearUsuario');
        if (modal) {
          modal.classList.add('show');
          // Hacer scroll al inicio del modal
          modal.scrollTop = 0;
        } else {
          console.error('Modal no encontrado');
          alert('Error: No se pudo abrir el modal.');
        }
      } catch (error) {
        console.error('Error al prellenar el modal:', error);
        alert('Error al cargar los datos de la solicitud. Por favor, intente nuevamente.');
      }
    }

      function buscarReniec() {
      // Solo DNI está permitido
      const tipoDoc = '1'; // Siempre DNI
      const numeroDoc = document.getElementById('reniecNumeroDoc').value.trim();

      if (!numeroDoc) {
        mostrarErrorReniec('Por favor, ingrese el número de DNI');
        return;
      }

      // Validar DNI (8 dígitos)
      if (numeroDoc.length !== 8 || !/^\d+$/.test(numeroDoc)) {
        mostrarErrorReniec('El DNI debe tener exactamente 8 dígitos numéricos');
        return;
      }

      const resultadoDiv = document.getElementById('reniecResultado');
      const errorDiv = document.getElementById('reniecError');
      const nameUsuarioInput = document.getElementById('nameUsuario');
      const btnBuscar = document.getElementById('btnBuscarReniec');
      const iconBuscar = document.getElementById('iconBuscar');
      const iconLoading = document.getElementById('iconLoading');
      const textBuscar = document.getElementById('textBuscar');

      // Mostrar loading
      resultadoDiv.classList.add('hidden');
      errorDiv.classList.add('hidden');
      btnBuscar.disabled = true;
      iconBuscar.classList.add('hidden');
      iconLoading.classList.remove('hidden');
      textBuscar.textContent = 'Buscando...';

      // Siempre usar tipo_documento=1 (DNI) ya que solo DNI está permitido
      fetch(`{{ route("api.consultar-reniec") }}?tipo_documento=1&numero_documento=${encodeURIComponent(numeroDoc)}`, {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
          'Accept': 'application/json'
        }
      })
      .then(async response => {
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
          throw new Error('El servidor no devolvió una respuesta JSON válida');
        }
        
        const data = await response.json();
        
        // Si la respuesta no es exitosa, lanzar un error con los datos
        if (!response.ok) {
          const error = new Error(data.message || 'Error en la petición');
          error.response = data;
          error.status = response.status;
          throw error;
        }
        
        return data;
      })
      .then(data => {
        // Restaurar botón
        btnBuscar.disabled = false;
        iconBuscar.classList.remove('hidden');
        iconLoading.classList.add('hidden');
        textBuscar.textContent = 'Buscar';

        if (data.success && data.data) {
          // Mostrar datos de RENIEC (soporta tanto camelCase como snake_case)
          const nombresCompletos = data.data.nombres_completos || data.data.nombreCompleto || 
            `${data.data.nombres || ''} ${data.data.apellidoPaterno || data.data.apellido_paterno || ''} ${data.data.apellidoMaterno || data.data.apellido_materno || ''}`.trim();
          const apellidoPaterno = data.data.apellido_paterno || data.data.apellidoPaterno || '-';
          const apellidoMaterno = data.data.apellido_materno || data.data.apellidoMaterno || '-';
          const nombres = data.data.nombres || '-';
          
          document.getElementById('reniecNombres').textContent = nombresCompletos || '-';
          document.getElementById('reniecApellidoPaterno').textContent = apellidoPaterno;
          document.getElementById('reniecApellidoMaterno').textContent = apellidoMaterno;
          document.getElementById('reniecNombresOnly').textContent = nombres;
          
          resultadoDiv.classList.remove('hidden');
          errorDiv.classList.add('hidden');

          // Si hay una nota (datos estimados), mostrarla en el resultado
          const existingNote = resultadoDiv.querySelector('.note-warning');
          if (existingNote) {
            existingNote.remove();
          }
          
          if (data.data.note) {
            const noteDiv = document.createElement('div');
            noteDiv.className = 'mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-xs text-yellow-800 flex items-start gap-2';
            noteDiv.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-600 flex-shrink-0 mt-0.5">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
              </svg>
              <span>${data.data.note}</span>
            `;
            noteDiv.classList.add('note-warning');
            resultadoDiv.appendChild(noteDiv);
          }

          // Prellenar nombre de usuario con los datos
          if (nameUsuarioInput) {
            const nombreCompleto = nombresCompletos || '';
            nameUsuarioInput.value = nombreCompleto;
            // Si los datos vienen de la solicitud, permitir edición
            nameUsuarioInput.readOnly = !(data.data.source === 'solicitud');
            
            // Cambiar el estilo del campo si es editable
            if (!nameUsuarioInput.readOnly) {
              nameUsuarioInput.classList.remove('bg-gray-100');
              nameUsuarioInput.classList.add('bg-white');
            }
          }
        } else {
          let errorMessage = data.message || 'No se encontraron datos en RENIEC';
          let suggestion = '';
          
          if (data.suggestion) {
            suggestion = `<div class="mt-3 pt-3 border-t border-red-200"><strong>Sugerencia:</strong> ${data.suggestion}</div>`;
          }
          
          mostrarErrorReniec(errorMessage + suggestion);
        }
      })
      .catch(error => {
        console.error('Error al consultar RENIEC:', error);
        
        // Restaurar botón
        btnBuscar.disabled = false;
        iconBuscar.classList.remove('hidden');
        iconLoading.classList.add('hidden');
        textBuscar.textContent = 'Buscar';

        let mensajeError = '';
        let suggestion = '';

        // Si hay una respuesta con datos del servidor
        if (error.response) {
          mensajeError = error.response.message || 'Error al consultar RENIEC';
          suggestion = error.response.suggestion || '';
        } else if (error.message.includes('JSON')) {
          mensajeError = 'El servidor no respondió correctamente. Verifique su conexión a internet.';
        } else if (error.message.includes('fetch') || error.message.includes('Failed to fetch')) {
          mensajeError = 'No se pudo conectar con el servidor. Verifique su conexión a internet.';
          suggestion = 'Asegúrese de tener una conexión estable a internet e intente nuevamente.';
        } else {
          mensajeError = error.message || 'Error al consultar RENIEC. Por favor, intente nuevamente.';
        }

        if (suggestion) {
          mensajeError += `<div class="mt-3 pt-3 border-t border-red-200"><strong>Sugerencia:</strong> ${suggestion}</div>`;
        }
        
        mostrarErrorReniec(mensajeError);
      });
    }

    function mostrarErrorReniec(mensaje) {
      const errorDiv = document.getElementById('reniecError');
      const errorContent = document.getElementById('reniecErrorContent');
      const resultadoDiv = document.getElementById('reniecResultado');
      
      errorContent.innerHTML = typeof mensaje === 'string' ? mensaje.replace(/\n/g, '<br>') : mensaje;
      errorDiv.classList.remove('hidden');
      resultadoDiv.classList.add('hidden');
    }

    function togglePasswordVisibility(inputId) {
      const input = document.getElementById(inputId);
      const eyeIcon = document.getElementById(`eyeIcon-${inputId}`);
      const eyeOffIcon = document.getElementById(`eyeOffIcon-${inputId}`);
      
      if (input.type === 'password') {
        input.type = 'text';
        eyeIcon.classList.remove('hidden');
        eyeOffIcon.classList.add('hidden');
      } else {
        input.type = 'password';
        eyeIcon.classList.add('hidden');
        eyeOffIcon.classList.remove('hidden');
      }
    }

    function closeModalCrearUsuario(event) {
      if (event && event.target === event.currentTarget) {
        document.getElementById('modalCrearUsuario').classList.remove('show');
      } else if (!event) {
        document.getElementById('modalCrearUsuario').classList.remove('show');
      }
      
      // Limpiar campos al cerrar
      const reniecTipoDoc = document.getElementById('reniecTipoDoc');
      if (reniecTipoDoc) reniecTipoDoc.value = '1'; // Siempre DNI
      document.getElementById('reniecNumeroDoc').value = '';
      document.getElementById('reniecResultado').classList.add('hidden');
      document.getElementById('reniecError').classList.add('hidden');
      document.getElementById('nameUsuario').value = '';
      document.getElementById('passwordUsuario').value = '';
      document.getElementById('correoUsuario').value = '';
      document.getElementById('rolUsuario').value = '';
    }

    function crearUsuario(event) {
      event.preventDefault();
      
      const formData = new FormData(event.target);
      const data = {
        solicitud_id: formData.get('solicitud_id'),
        name: formData.get('name'),
        password: formData.get('password'),
        email: formData.get('email'),
        role: formData.get('role')
      };

      // Validaciones del formulario
      if (!data.name || data.name.trim() === '') {
        alert('Por favor, ingrese el nombre de usuario. Debe consultar RENIEC primero.');
        return;
      }

      if (!data.email || data.email.trim() === '') {
        alert('Por favor, ingrese el correo electrónico');
        return;
      }

      if (!data.password || data.password.trim() === '') {
        alert('Por favor, ingrese la contraseña');
        return;
      }

      if (data.password.length < 8) {
        alert('La contraseña debe tener al menos 8 caracteres');
        return;
      }

      if (!data.role) {
        alert('Por favor, seleccione un rol');
        return;
      }

      if (!data.solicitud_id) {
        alert('Error: No se encontró el ID de la solicitud');
        return;
      }

      // Mostrar loading en el botón de submit
      const submitBtn = event.target.querySelector('button[type="submit"]');
      const originalBtnText = submitBtn ? submitBtn.innerHTML : '';
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
          <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Creando usuario...
        `;
      }

      fetch('{{ route("api.crear-usuario-solicitud") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
          'Accept': 'application/json'
        },
        body: JSON.stringify(data)
      })
      .then(async response => {
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
          const text = await response.text();
          console.error('Respuesta no JSON:', text);
          throw new Error('El servidor no devolvió una respuesta JSON válida');
        }
        
        const data = await response.json();
        
        // Si la respuesta no es exitosa, lanzar error con los datos
        if (!response.ok) {
          const error = new Error(data.message || 'Error al crear el usuario');
          error.response = data;
          error.status = response.status;
          throw error;
        }
        
        return data;
      })
      .then(data => {
        // Restaurar botón
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnText;
        }

        if (data.success) {
          // Mostrar mensaje de éxito con mejor diseño
          const successMessage = document.createElement('div');
          successMessage.className = 'mensaje-exito animate-slide-in';
          successMessage.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2); z-index: 9999; display: flex; align-items: center; gap: 0.75rem; min-width: 300px; max-width: 500px;';
          successMessage.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white; flex-shrink: 0;">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
              <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <div style="flex: 1;">
              <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Usuario creado exitosamente</div>
              <div style="font-size: 0.75rem; opacity: 0.95;">La cuenta de usuario ha sido creada y la solicitud ha sido eliminada.</div>
            </div>
          `;
          document.body.appendChild(successMessage);
          
          setTimeout(() => {
            successMessage.classList.add('animate-slide-out');
            setTimeout(() => successMessage.remove(), 300);
          }, 4000);

          closeModalCrearUsuario();
          cargarSolicitudes(estadoActual);
          // Si estamos en la pestaña de usuarios, recargar también esa tabla
          if (tabActual === 'usuarios') {
            cargarUsuarios();
          }
        } else {
          // Mostrar mensaje de error con mejor diseño
          const errorMessage = data.message || 'No se pudo crear el usuario';
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
              <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Error al crear usuario</div>
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
        console.error('Error al crear usuario:', error);
        
        // Restaurar botón
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnText;
        }

        // Obtener mensaje de error
        let errorMessage = 'No se pudo crear el usuario';
        if (error.message) {
          errorMessage = error.message;
        } else if (error.response && error.response.message) {
          errorMessage = error.response.message;
        } else if (error.status === 422) {
          errorMessage = 'Datos inválidos. Verifique que todos los campos estén completos.';
        } else if (error.status === 500) {
          errorMessage = 'Error del servidor. Por favor, intente más tarde.';
        }

        // Mostrar mensaje de error
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
            <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">Error al crear usuario</div>
            <div style="font-size: 0.75rem; opacity: 0.95;">${errorMessage}</div>
          </div>
        `;
        document.body.appendChild(errorDiv);
        
        setTimeout(() => {
          errorDiv.classList.add('animate-slide-out');
          setTimeout(() => errorDiv.remove(), 300);
        }, 5000);
      });
    }

    // Función para cargar microredes según la red seleccionada
    async function cargarMicroredesSolicitud(codigoRed, microredSeleccionada = null) {
      const microredSelect = document.getElementById('codigoMicroredSolicitud');
      if (!microredSelect) return;

      microredSelect.innerHTML = '<option value="">Cargando...</option>';
      microredSelect.disabled = true;

      try {
        // Usar el objeto data del archivo formulario-selec-de-EESS.js si está disponible
        if (typeof data !== 'undefined' && data[codigoRed]) {
          const microredes = Object.keys(data[codigoRed]);
          microredSelect.innerHTML = '<option value="">Seleccione una Microred</option>';
          
          microredes.forEach(microred => {
            const option = document.createElement('option');
            option.value = microred;
            option.textContent = microred;
            if (microredSeleccionada && microred === microredSeleccionada) {
              option.selected = true;
            }
            microredSelect.appendChild(option);
          });
          
          microredSelect.disabled = false;
        } else {
          microredSelect.innerHTML = '<option value="">No hay microredes disponibles</option>';
        }
      } catch (error) {
        console.error('Error al cargar microredes:', error);
        microredSelect.innerHTML = '<option value="">Error al cargar</option>';
      }
    }

    // Función para cargar establecimientos según la red y microred seleccionadas
    async function cargarEstablecimientosSolicitud(codigoRed, codigoMicrored, establecimientoSeleccionado = null) {
      const establecimientoSelect = document.getElementById('idEstablecimientoSolicitud');
      if (!establecimientoSelect) return;

      establecimientoSelect.innerHTML = '<option value="">Cargando...</option>';
      establecimientoSelect.disabled = true;

      try {
        // Usar el objeto data del archivo formulario-selec-de-EESS.js si está disponible
        if (typeof data !== 'undefined' && data[codigoRed] && data[codigoRed][codigoMicrored]) {
          const establecimientos = data[codigoRed][codigoMicrored];
          establecimientoSelect.innerHTML = '<option value="">Seleccione un Establecimiento</option>';
          
          establecimientos.forEach(est => {
            const option = document.createElement('option');
            option.value = est.value;
            option.textContent = est.text;
            if (establecimientoSeleccionado && est.value === establecimientoSeleccionado) {
              option.selected = true;
            }
            establecimientoSelect.appendChild(option);
          });
          
          establecimientoSelect.disabled = false;
        } else {
          establecimientoSelect.innerHTML = '<option value="">No hay establecimientos disponibles</option>';
        }
      } catch (error) {
        console.error('Error al cargar establecimientos:', error);
        establecimientoSelect.innerHTML = '<option value="">Error al cargar</option>';
      }
    }

    // Cargar solicitudes al iniciar
    document.addEventListener('DOMContentLoaded', function() {
      cargarSolicitudes();
    });
  </script>
</body>

</html>
