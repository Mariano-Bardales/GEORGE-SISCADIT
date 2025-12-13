<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#000000">
  <meta name="description" content="Sistema de Control y Alerta de Etapas de Vida del Niño - SISCADIT">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>SISCADIT - Alertas CRED</title>
  <link rel="stylesheet" href="{{ asset('Css/dashbord.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/dashboard-main.css') }}">
  <style>
    .alertas-container {
      padding: 1.5rem 2rem;
    }
    .alertas-header {
      background: linear-gradient(135deg, rgb(102, 126, 234) 0%, rgb(118, 75, 162) 100%);
      border-radius: 12px;
      padding: 1.5rem 2rem;
      margin-bottom: 1.5rem;
      color: white;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.25);
    }
    .alertas-header h1 {
      font-size: 1.875rem;
      font-weight: 800;
      margin: 0 0 0.5rem 0;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }
    .alertas-header p {
      font-size: 0.9375rem;
      margin: 0;
      opacity: 0.95;
    }
    .filtros-container {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
      border: 1px solid #e5e7eb;
    }
    .filtros-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 1rem;
      align-items: end;
    }
    .filtro-group {
      display: flex;
      flex-direction: column;
    }
    .filtro-label {
      font-size: 0.8125rem;
      font-weight: 600;
      color: #475569;
      margin-bottom: 0.5rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .filtro-input {
      padding: 0.75rem 1rem;
      border: 2px solid #e2e8f0;
      border-radius: 8px;
      font-size: 0.875rem;
      transition: all 0.2s ease;
    }
    .filtro-input:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .filtro-select {
      padding: 0.75rem 1rem;
      border: 2px solid #e2e8f0;
      border-radius: 8px;
      font-size: 0.875rem;
      background: white;
      cursor: pointer;
      transition: all 0.2s ease;
    }
    .filtro-select:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .alertas-table-container {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
      border: 1px solid #e5e7eb;
    }
    .alertas-table {
      width: 100%;
      border-collapse: collapse;
    }
    .alertas-table thead {
      background: linear-gradient(to right, rgb(102, 126, 234), rgb(118, 75, 162));
      color: white;
    }
    .alertas-table th {
      padding: 1rem 1.25rem;
      text-align: left;
      font-weight: 600;
      font-size: 0.8125rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .alertas-table td {
      padding: 1rem 1.25rem;
      border-bottom: 1px solid #e5e7eb;
      font-size: 0.875rem;
      color: #1e293b;
      vertical-align: top;
    }
    .alertas-table tbody tr:hover {
      background: #f8fafc;
    }
    .badge-prioridad {
      padding: 0.375rem 0.75rem;
      border-radius: 999px;
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      display: inline-block;
    }
    .badge-prioridad.alta {
      background: rgba(102, 126, 234, 0.15);
      color: rgb(79, 70, 229);
    }
    .badge-prioridad.media {
      background: #fef3c7;
      color: #92400e;
    }
    .badge-tipo {
      padding: 0.375rem 0.75rem;
      border-radius: 6px;
      font-size: 0.75rem;
      font-weight: 600;
      display: inline-block;
    }
    .badge-tipo.control_recien_nacido {
      background: #dbeafe;
      color: #1e40af;
    }
    .badge-tipo.control_cred_mensual {
      background: #e0e7ff;
      color: #3730a3;
    }
    .badge-tipo.tamizaje {
      background: #fce7f3;
      color: #9f1239;
    }
    .badge-tipo.vacuna {
      background: #dcfce7;
      color: #166534;
    }
    .badge-tipo.cnv {
      background: #fef3c7;
      color: #92400e;
    }
    .badge-tipo.visita {
      background: #e0e7ff;
      color: #3730a3;
    }
    .mensaje-alerta {
      background: rgba(102, 126, 234, 0.1);
      border-left: 4px solid rgb(102, 126, 234);
      padding: 0.75rem 1rem;
      border-radius: 6px;
      font-size: 0.8125rem;
      color: rgb(79, 70, 229);
      margin-top: 0.5rem;
      line-height: 1.5;
    }
    .rango-info {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.8125rem;
    }
    .rango-info .edad-actual {
      font-weight: 700;
      color: rgb(102, 126, 234);
    }
    .rango-info .rango-esperado {
      color: #64748b;
    }
    .btn-ver-controles {
      background: linear-gradient(to right, #9333ea, #7c3aed);
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      border: none;
      font-weight: 600;
      font-size: 0.8125rem;
      cursor: pointer;
      transition: all 0.2s ease;
    }
    .btn-ver-controles:hover {
      background: linear-gradient(to right, #7c3aed, #6d28d9);
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(147, 51, 234, 0.3);
    }
    @media (max-width: 1200px) {
      .filtros-grid {
        grid-template-columns: 1fr 1fr !important;
      }
    }
    @media (max-width: 768px) {
      .filtros-grid {
        grid-template-columns: 1fr !important;
      }
    }
  </style>
</head>
<body>
  <noscript>You need to enable JavaScript to run this app.</noscript>
  <div id="root">
    <div class="flex h-screen bg-slate-50 relative">
      <x-sidebar-main activeRoute="alertas-cred" />
      <main class="flex-1 overflow-auto">
        <div class="alertas-container">
          <div class="alertas-header">
            <h1>
              <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
              </svg>
              Alertas CRED
            </h1>
            <p>Niños con controles pendientes o fuera del rango establecido</p>
            <div style="margin-top: 1rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
              <button onclick="limpiarCacheYRecargar()" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-size: 0.875rem; font-weight: 600; transition: all 0.2s;">
                🔄 Limpiar Caché y Recargar
              </button>
              <button onclick="window.open('/alertas-cred/explicacion-pdf?print=true', '_blank')" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-size: 0.875rem; font-weight: 600; transition: all 0.2s;">
                📄 Ver Guía de Alertas (PDF)
              </button>
            </div>
          </div>

          <!-- Filtros -->
          <div class="filtros-container">
            <div class="filtros-grid" style="grid-template-columns: 2fr 1fr; gap: 1rem;">
              <div class="filtro-group">
                <label class="filtro-label">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 0.5rem;">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                  </svg>
                  Buscar por DNI o Nombre
                </label>
                <input type="text" id="filtroBuscar" class="filtro-input" placeholder="Ingrese DNI o nombre del niño..." onkeyup="filtrarAlertas()">
              </div>
              <div class="filtro-group">
                <label class="filtro-label">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 0.5rem;">
                    <path d="M3 3h18v18H3zM3 9h18M9 3v18"></path>
                  </svg>
                  Tipo de Control
                </label>
                <select id="filtroTipo" class="filtro-select" onchange="filtrarAlertas()">
                  <option value="">Todos los tipos</option>
                  <option value="datos_faltantes_nino">Datos Faltantes del Niño</option>
                  <option value="datos_faltantes_madre">Datos Faltantes de la Madre</option>
                  <option value="datos_faltantes_extras">Datos Faltantes Extras</option>
                  <option value="control_recien_nacido">Control Recién Nacido</option>
                  <option value="control_cred_mensual">CRED Mensual</option>
                  <option value="tamizaje">Tamizaje</option>
                  <option value="cnv">CNV (Carné de Nacido Vivo)</option>
                  <option value="visita">Visitas Domiciliarias</option>
                  <option value="vacuna">Vacuna</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Tabla de Alertas -->
          <div class="alertas-table-container">
            <table class="alertas-table">
              <thead>
                <tr>
                  <th>Niño</th>
                  <th>DNI</th>
                  <th>Control</th>
                  <th>Problema Detectado</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody id="tablaAlertas">
                <tr>
                  <td colspan="5" style="text-align: center; padding: 2rem; color: #64748b;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 1rem; display: block; opacity: 0.5;">
                      <circle cx="12" cy="12" r="10"></circle>
                      <line x1="12" y1="8" x2="12" y2="12"></line>
                      <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    Cargando alertas...
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    let todasLasAlertas = [];

    // Función para limpiar caché del navegador
    function limpiarCache() {
      try {
        // Limpiar localStorage relacionado con alertas
        const keys = Object.keys(localStorage);
        keys.forEach(key => {
          if (key.includes('alerta') || key.includes('control') || key.includes('cache')) {
            localStorage.removeItem(key);
          }
        });
        console.log('✅ Caché del navegador limpiado');
      } catch (e) {
        console.warn('⚠️ No se pudo limpiar localStorage:', e);
      }
    }

    async function cargarAlertas(forzarRecarga = false) {
      try {
        // Limpiar caché si se fuerza la recarga
        if (forzarRecarga) {
          limpiarCache();
        }
        
        // Agregar timestamp para evitar caché del navegador
        const timestamp = new Date().getTime();
        const response = await fetch(`/api/alertas?t=${timestamp}`, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Cache-Control': 'no-cache, no-store, must-revalidate',
            'Pragma': 'no-cache',
            'Expires': '0'
          },
          cache: 'no-store'
        });
        const data = await response.json();
        
        if (data.success && data.data) {
          todasLasAlertas = data.data;
          filtrarAlertas();
        } else {
          document.getElementById('tablaAlertas').innerHTML = `
            <tr>
              <td colspan="5" style="text-align: center; padding: 2rem; color: #64748b;">
                No se pudieron cargar las alertas
              </td>
            </tr>
          `;
        }
      } catch (error) {
        console.error('Error al cargar alertas:', error);
        document.getElementById('tablaAlertas').innerHTML = `
          <tr>
            <td colspan="5" style="text-align: center; padding: 2rem; color: rgb(102, 126, 234);">
              Error al cargar las alertas. Por favor, recarga la página.
            </td>
          </tr>
        `;
      }
    }

    function filtrarAlertas() {
      const filtroBuscar = document.getElementById('filtroBuscar').value.toLowerCase();
      const filtroTipo = document.getElementById('filtroTipo').value;
      
      let alertasFiltradas = todasLasAlertas.filter(alerta => {
        // Filtro por búsqueda (DNI o nombre)
        const coincideBuscar = !filtroBuscar || 
          (alerta.nino_nombre && alerta.nino_nombre.toLowerCase().includes(filtroBuscar)) ||
          (alerta.nino_dni && alerta.nino_dni.toString().includes(filtroBuscar));
        
        // Filtro por tipo de control
        const coincideTipo = !filtroTipo || alerta.tipo === filtroTipo;
        
        return coincideBuscar && coincideTipo;
      });
      
      renderizarAlertas(alertasFiltradas);
    }

    function renderizarAlertas(alertas) {
      const tbody = document.getElementById('tablaAlertas');
      
      if (alertas.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="5" style="text-align: center; padding: 2rem; color: #10b981;">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 1rem; display: block;">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
              <p style="font-weight: 600; font-size: 1.125rem; margin: 0.5rem 0;">¡Excelente!</p>
              <p style="margin: 0;">No hay alertas que coincidan con los filtros seleccionados.</p>
            </td>
          </tr>
        `;
        return;
      }

      tbody.innerHTML = alertas.map(alerta => {
        const tipoClass = alerta.tipo.replace(/_/g, '-');
        const prioridadClass = alerta.prioridad;
        const diasFuera = alerta.dias_fuera || 0;
        
        return `
          <tr>
            <td style="font-weight: 600;">${alerta.nino_nombre || '-'}</td>
            <td>${alerta.nino_dni || '-'}</td>
            <td style="font-weight: 600;">${alerta.control || '-'}</td>
            <td style="max-width: 400px;">
              <div class="mensaje-alerta">
                ${alerta.mensaje || 'Control pendiente de realizar'}
              </div>
              ${diasFuera > 0 ? `<div style="margin-top: 0.5rem; font-size: 0.75rem; color: rgb(118, 75, 162); font-weight: 600;">
                ⚠️ Fuera del rango por ${diasFuera} día(s)
              </div>` : ''}
            </td>
            <td>
              <button class="btn-ver-controles" onclick="window.location.href='/controles-cred'">
                Ver Controles
              </button>
            </td>
          </tr>
        `;
      }).join('');
    }

    // Escuchar eventos de control registrado para actualizar alertas
    window.addEventListener('controlRegistrado', function(event) {
      console.log('🔄 Control registrado detectado, actualizando alertas...');
      // Recargar alertas después de un breve delay
      setTimeout(() => {
        cargarAlertas();
      }, 1000);
    });
    
    // Usar localStorage para sincronizar entre pestañas
    window.addEventListener('storage', function(event) {
      if (event.key === 'controlRegistrado') {
        try {
          const data = JSON.parse(event.newValue);
          if (data && data.ninoId) {
            console.log('🔄 Control registrado en otra pestaña, actualizando alertas...');
            setTimeout(() => {
              cargarAlertas();
            }, 500);
          }
        } catch (e) {
          console.error('Error al procesar evento de storage:', e);
        }
      }
    });
    
    // Actualizar alertas periódicamente cada 30 segundos
    setInterval(() => {
      cargarAlertas();
    }, 30000);

    // Función para limpiar caché y recargar alertas
    function limpiarCacheYRecargar() {
      limpiarCache();
      cargarAlertas(true);
      // Mostrar mensaje de confirmación
      const tbody = document.getElementById('tablaAlertas');
      tbody.innerHTML = `
        <tr>
          <td colspan="8" style="text-align: center; padding: 2rem; color: #3b82f6;">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 1rem; display: block; animation: spin 1s linear infinite;">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            Limpiando caché y recargando alertas...
          </td>
        </tr>
      `;
    }

    // Cargar alertas al iniciar
    document.addEventListener('DOMContentLoaded', function() {
      // Limpiar caché al cargar la página
      limpiarCache();
      cargarAlertas(true);
      
      // Recargar cada 5 minutos (sin forzar, para no limpiar caché constantemente)
      setInterval(() => cargarAlertas(false), 300000);
    });
  </script>
</body>
</html>
