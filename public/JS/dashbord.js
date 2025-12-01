// Dashboard JavaScript - SISCADIT

let chartGenero = null;
let chartCalidadDatos = null;

// Función auxiliar para obtener el token CSRF
function getCsrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

// Cargar datos al iniciar
document.addEventListener('DOMContentLoaded', function() {
  if (typeof window.dashboardRoutes === 'undefined') {
    console.error('Las rutas del dashboard no están definidas. Asegúrate de que window.dashboardRoutes esté configurado.');
    return;
  }
  
  cargarDashboardStats();
  cargarGraficos();
  cargarTablaControles();
  // cargarResumenAlertas(); // Sección eliminada
  cargarTopEstablecimientos();
  
  // Escuchar eventos de control registrado para actualizar el dashboard
  window.addEventListener('controlRegistrado', function(event) {
    console.log('🔄 Control registrado detectado, actualizando dashboard...');
    // Recargar estadísticas y alertas después de un breve delay
    setTimeout(() => {
      cargarDashboardStats();
      // cargarResumenAlertas(); // Sección eliminada
      cargarTablaControles();
    }, 1000);
  });
  
  // Usar localStorage para sincronizar entre pestañas
  window.addEventListener('storage', function(event) {
    if (event.key === 'controlRegistrado') {
      try {
        const data = JSON.parse(event.newValue);
        if (data && data.ninoId) {
          console.log('🔄 Control registrado en otra pestaña, actualizando dashboard...');
          setTimeout(() => {
            cargarDashboardStats();
            // cargarResumenAlertas(); // Sección eliminada
            cargarTablaControles();
          }, 500);
        }
      } catch (e) {
        console.error('Error al procesar evento de storage:', e);
      }
    }
  });
  
  // Actualizar dashboard periódicamente cada 30 segundos
  setInterval(() => {
    cargarDashboardStats();
    // cargarResumenAlertas(); // Sección eliminada
  }, 30000);
});

// Cargar estadísticas del dashboard
function cargarDashboardStats() {
  if (!window.dashboardRoutes || !window.dashboardRoutes.stats) {
    console.error('Ruta de estadísticas no definida');
    return;
  }
  
  fetch(window.dashboardRoutes.stats, {
    method: 'GET',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': getCsrfToken()
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success && data.data) {
      const totalRegistradosEl = document.querySelector('[data-testid="stat-card-total-registrados"] h3');
      const totalUsuariosEl = document.querySelector('[data-testid="stat-card-total-usuarios"] h3');
      const totalAlertasEl = document.querySelector('[data-testid="stat-card-alertas-detectadas"] h3');
      
      if (totalRegistradosEl) totalRegistradosEl.textContent = data.data.total_ninos || 0;
      if (totalUsuariosEl) totalUsuariosEl.textContent = data.data.total_usuarios || 0;
      if (totalAlertasEl) totalAlertasEl.textContent = data.data.total_alertas || 0;
    }
  })
  .catch(error => console.error('Error al cargar estadísticas del dashboard:', error));
}

// Cargar gráficos
function cargarGraficos() {
  if (!window.dashboardRoutes || !window.dashboardRoutes.reportes) {
    console.error('Ruta de reportes no definida');
    return;
  }
  
  fetch(window.dashboardRoutes.reportes, {
    method: 'GET',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': getCsrfToken()
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success && data.data) {
      // Gráfico de género
      const ctx1 = document.getElementById('chartGenero');
      if (ctx1) {
        if (chartGenero) {
          chartGenero.destroy();
        }
        const generoData = data.data.genero || { masculino: 0, femenino: 0 };
        chartGenero = new Chart(ctx1, {
          type: 'doughnut',
          data: {
            labels: ['Masculino', 'Femenino'],
            datasets: [{
              data: [
                generoData.masculino || 0,
                generoData.femenino || 0
              ],
              backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(236, 72, 153, 0.8)'
              ],
              borderColor: [
                'rgba(59, 130, 246, 1)',
                'rgba(236, 72, 153, 1)'
              ],
              borderWidth: 2
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: true,
                position: 'bottom'
              },
              tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                callbacks: {
                  label: function(context) {
                    const label = context.label || '';
                    const value = context.parsed || 0;
                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                    return `${label}: ${value} (${percentage}%)`;
                  }
                }
              }
            }
          }
        });
      }

      // Gráfico de calidad de datos
      const ctx2 = document.getElementById('chartCalidadDatos');
      if (ctx2) {
        if (chartCalidadDatos) {
          chartCalidadDatos.destroy();
        }
        const calidadData = data.data.calidad_datos || { perfectos: 0, con_errores: 0 };
        chartCalidadDatos = new Chart(ctx2, {
          type: 'doughnut',
          data: {
            labels: ['Datos Perfectos', 'Datos con Errores'],
            datasets: [{
              data: [
                calidadData.perfectos || 0,
                calidadData.con_errores || 0
              ],
              backgroundColor: [
                'rgba(16, 185, 129, 0.8)',
                'rgba(239, 68, 68, 0.8)'
              ],
              borderColor: [
                'rgba(16, 185, 129, 1)',
                'rgba(239, 68, 68, 1)'
              ],
              borderWidth: 2
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: true,
                position: 'bottom'
              },
              tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                callbacks: {
                  label: function(context) {
                    const label = context.label || '';
                    const value = context.parsed || 0;
                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                    return `${label}: ${value} (${percentage}%)`;
                  }
                }
              }
            }
          }
        });
      }
    }
  })
  .catch(error => console.error('Error al cargar gráficos:', error));
}

// Cargar tabla de controles CRED
async function cargarTablaControles() {
  try {
    const tablaBody = document.getElementById('tablaControlesBody');
    const totalControles = document.getElementById('totalControlesTabla');

    if (!tablaBody) return;
    
    if (!window.dashboardRoutes || !window.dashboardRoutes.ninos) {
      console.error('Ruta de niños no definida');
      return;
    }

    const response = await fetch(window.dashboardRoutes.ninos, {
      method: 'GET',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': getCsrfToken()
      }
    });

    const data = await response.json();

    if (data.success && data.data && data.data.length > 0) {
      const ninos = data.data.slice(0, 10);
      let html = '';

      const promesasControles = ninos.map(async (nino) => {
        let totalControlesNino = 0;

        try {
          const [responseRN, responseCred] = await Promise.all([
            fetch(`${window.dashboardRoutes.controlesRn}?nino_id=${nino.id_niño || nino.id}`, {
              method: 'GET',
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCsrfToken()
              }
            }),
            fetch(`${window.dashboardRoutes.controlesCred}?nino_id=${nino.id_niño || nino.id}`, {
              method: 'GET',
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCsrfToken()
              }
            })
          ]);

          const dataRN = await responseRN.json();
          const dataCred = await responseCred.json();

          if (dataRN.success && dataRN.data) {
            totalControlesNino += dataRN.data.length;
          }
          if (dataCred.success && dataCred.data) {
            totalControlesNino += dataCred.data.length;
          }
        } catch (error) {
          console.error('Error al cargar controles del niño:', error);
        }

        return { nino, totalControlesNino };
      });

      const resultados = await Promise.all(promesasControles);

      resultados.forEach(({ nino, totalControlesNino }) => {
        const fechaNacimiento = new Date(nino.fecha_nacimiento);
        const fechaFormateada = fechaNacimiento.toLocaleDateString('es-PE', {
          year: 'numeric',
          month: '2-digit',
          day: '2-digit'
        });

        const generoColor = nino.genero === 'M' ? '#3b82f6' : '#f43f5e';
        const generoBg = nino.genero === 'M' ? '#dbeafe' : '#fce7f3';

        html += `
          <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s;">
            <td style="padding: 12px; color: #1e293b; font-size: 0.875rem;">${nino.establecimiento || 'No registrado'}</td>
            <td style="padding: 12px; color: #64748b; font-size: 0.875rem; font-family: monospace;">${nino.numero_doc || 'Sin DNI'}</td>
            <td style="padding: 12px; color: #1e293b; font-weight: 500; font-size: 0.875rem;">${nino.apellidos_nombres || 'Sin nombre'}</td>
            <td style="padding: 12px; color: #64748b; font-size: 0.875rem;">${fechaFormateada}</td>
            <td style="padding: 12px;">
              <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: ${generoBg}; color: ${generoColor}; font-weight: 600; font-size: 0.75rem;">
                ${nino.genero || '-'}
              </span>
            </td>
            <td style="padding: 12px; text-align: center;">
              <span style="display: inline-flex; align-items: center; justify-content: center; padding: 4px 12px; border-radius: 12px; background: #10b981; color: white; font-weight: 600; font-size: 0.75rem;">
                ${totalControlesNino} control${totalControlesNino !== 1 ? 'es' : ''}
              </span>
            </td>
          </tr>
        `;
      });

      if (html === '') {
        tablaBody.innerHTML = `
          <tr>
            <td colspan="6" style="padding: 24px; text-align: center; color: #64748b;">
              <p>No hay datos disponibles.</p>
            </td>
          </tr>
        `;
      } else {
        tablaBody.innerHTML = html;
      }

      if (totalControles) {
        totalControles.textContent = data.data.length;
      }
    } else {
      tablaBody.innerHTML = `
        <tr>
          <td colspan="6" style="padding: 24px; text-align: center; color: #64748b;">
            <p>No hay niños registrados en el sistema.</p>
          </td>
        </tr>
      `;
      if (totalControles) {
        totalControles.textContent = '0';
      }
    }
  } catch (error) {
    console.error('Error al cargar tabla de controles:', error);
    const tablaBody = document.getElementById('tablaControlesBody');
    if (tablaBody) {
      tablaBody.innerHTML = `
        <tr>
          <td colspan="6" style="padding: 24px; text-align: center; color: #ef4444;">
            <p>Error al cargar los datos de controles.</p>
          </td>
        </tr>
      `;
    }
  }
}

// Cargar resumen de alertas
async function cargarResumenAlertas() {
  try {
    const alertasResumen = document.getElementById('alertasResumen');
    if (!alertasResumen) {
      console.warn('Elemento alertasResumen no encontrado');
      return;
    }
    
    if (!window.dashboardRoutes || !window.dashboardRoutes.ninos) {
      console.error('Ruta de niños no definida');
      return;
    }

    const response = await fetch(window.dashboardRoutes.ninos, {
      method: 'GET',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': getCsrfToken()
      }
    });

    if (!response.ok) {
      throw new Error(`Error HTTP: ${response.status}`);
    }

    const data = await response.json();
    
    if (!data.success) {
      throw new Error(data.message || 'Error al obtener datos');
    }

    if (data.data && data.data.length > 0) {
      const ninos = data.data;
      let todasLasAlertas = [];
      const maxAlertas = 10;

      for (const nino of ninos) {
        try {
          const fechaNacimiento = new Date(nino.fecha_nacimiento);
          const hoy = new Date();
          const edadDias = Math.floor((hoy - fechaNacimiento) / (1000 * 60 * 60 * 24));

          const alertasNino = await generarResumenAlertasParaNino(nino, edadDias);
          todasLasAlertas = todasLasAlertas.concat(alertasNino);
          
          if (todasLasAlertas.length >= maxAlertas) {
            break;
          }
        } catch (error) {
          console.error(`Error al procesar alertas para el niño ${nino.id}:`, error);
        }
      }

      todasLasAlertas = todasLasAlertas.slice(0, maxAlertas);

      if (todasLasAlertas.length === 0) {
        alertasResumen.innerHTML = `
          <div class="text-center py-8 text-green-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 1rem;">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
              <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">¡Excelente!</p>
            <p>No hay alertas críticas en este momento.</p>
          </div>
        `;
      } else {
        let html = '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">';
        html += todasLasAlertas.join('');
        html += '</div>';
        alertasResumen.innerHTML = html;
      }
    } else {
      alertasResumen.innerHTML = `
        <div class="text-center py-8 text-slate-500">
          <p>No hay niños registrados en el sistema.</p>
        </div>
      `;
    }
  } catch (error) {
    console.error('Error al cargar resumen de alertas:', error);
    const alertasResumen = document.getElementById('alertasResumen');
    if (alertasResumen) {
      alertasResumen.innerHTML = `
        <div class="text-center py-8 text-red-500">
          <p style="font-weight: 600; margin-bottom: 0.5rem;">Error al cargar el resumen de alertas</p>
          <p style="font-size: 0.875rem;">${error.message || 'Por favor, recarga la página e intenta nuevamente.'}</p>
        </div>
      `;
    }
  }
}

async function generarResumenAlertasParaNino(nino, edadDias) {
  const alertas = [];
  const nombre = nino.apellidos_nombres || 'Sin nombre';
  const documento = nino.numero_doc || 'Sin DNI';
  const establecimiento = nino.establecimiento || 'No registrado';
  const ninoId = nino.id_niño || nino.id;

  // Verificar alertas de recién nacido (0-28 días)
  if (edadDias <= 28) {
    try {
      const response = await fetch(`${window.dashboardRoutes.controlesRn}?nino_id=${ninoId}`, {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': getCsrfToken()
        }
      });
      const data = await response.json();
      const controles = data.success && data.data ? data.data : [];
      
      const rangosRN = {
        1: { min: 2, max: 6 },
        2: { min: 7, max: 13 },
        3: { min: 14, max: 20 },
        4: { min: 21, max: 28 }
      };
      
      let controlesEsperados = 0;
      for (const num in rangosRN) {
        const rango = rangosRN[num];
        if (edadDias >= rango.min && edadDias <= rango.max) {
          controlesEsperados++;
        } else if (edadDias > rango.max) {
          controlesEsperados++;
        }
      }

      if (controles.length < controlesEsperados) {
        alertas.push(`
          <div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 1rem; border-radius: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
              <span style="font-size: 1.5rem;">🍼</span>
              <h4 style="font-weight: 600; color: #1e293b; margin: 0;">Control Recién Nacido</h4>
            </div>
            <p style="font-size: 0.875rem; color: #64748b; margin: 0.25rem 0;"><strong>${nombre}</strong></p>
            <p style="font-size: 0.875rem; color: #64748b; margin: 0.25rem 0;">DNI: ${documento}</p>
            <p style="font-size: 0.875rem; color: #64748b; margin: 0.25rem 0;">${establecimiento}</p>
            <p style="font-size: 0.875rem; color: #dc2626; margin-top: 0.5rem;">Faltan ${controlesEsperados - controles.length} control(es) del recién nacido</p>
          </div>
        `);
      }
    } catch (error) {
      console.error('Error al verificar controles recién nacido:', error);
    }
  }

  // Verificar alertas de CRED mensual (29-359 días)
  if (edadDias >= 29 && edadDias <= 359) {
    try {
      const response = await fetch(`${window.dashboardRoutes.controlesCred}?nino_id=${ninoId}`, {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': getCsrfToken()
        }
      });
      const data = await response.json();
      const controles = data.success && data.data ? data.data : [];
      
      const rangosCred = {
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
      
      let controlesEsperados = 0;
      for (const num in rangosCred) {
        const rango = rangosCred[num];
        if (edadDias > rango.max) {
          controlesEsperados++;
        } else if (edadDias >= rango.min && edadDias <= rango.max) {
          controlesEsperados++;
        }
      }

      if (controles.length < controlesEsperados) {
        alertas.push(`
          <div style="background: #fffbeb; border-left: 4px solid #f59e0b; padding: 1rem; border-radius: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
              <span style="font-size: 1.5rem;">👶</span>
              <h4 style="font-weight: 600; color: #1e293b; margin: 0;">CRED Mensual</h4>
            </div>
            <p style="font-size: 0.875rem; color: #64748b; margin: 0.25rem 0;"><strong>${nombre}</strong></p>
            <p style="font-size: 0.875rem; color: #64748b; margin: 0.25rem 0;">DNI: ${documento}</p>
            <p style="font-size: 0.875rem; color: #d97706; margin-top: 0.5rem;">Faltan ${controlesEsperados - controles.length} control(es) CRED mensual(es)</p>
          </div>
        `);
      }
    } catch (error) {
      console.error('Error al verificar controles CRED mensual:', error);
    }
  }

  // Verificar tamizaje neonatal (1-29 días)
  if (edadDias >= 1 && edadDias <= 29) {
    try {
      const response = await fetch(`${window.dashboardRoutes.tamizaje}?nino_id=${ninoId}`, {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': getCsrfToken()
        }
      });
      const data = await response.json();
      const tamizaje = data.success && data.data && data.data.length > 0 ? data.data[0] : null;

      if (!tamizaje) {
        alertas.push(`
          <div style="background: #eff6ff; border-left: 4px solid #3b82f6; padding: 1rem; border-radius: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
              <span style="font-size: 1.5rem;">🧬</span>
              <h4 style="font-weight: 600; color: #1e293b; margin: 0;">Tamizaje Neonatal</h4>
            </div>
            <p style="font-size: 0.875rem; color: #64748b; margin: 0.25rem 0;"><strong>${nombre}</strong></p>
            <p style="font-size: 0.875rem; color: #64748b; margin: 0.25rem 0;">DNI: ${documento}</p>
            <p style="font-size: 0.875rem; color: #2563eb; margin-top: 0.5rem;">Falta registrar el tamizaje neonatal</p>
          </div>
        `);
      }
    } catch (error) {
      console.error('Error al verificar tamizaje:', error);
    }
  }

  // Verificar vacunas del recién nacido (0-30 días)
  if (edadDias <= 30) {
    try {
      const response = await fetch(`${window.dashboardRoutes.vacunas}?nino_id=${ninoId}`, {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': getCsrfToken()
        }
      });
      const data = await response.json();
      const vacunas = data.success && data.data ? data.data : [];
      
      // Verificar si hay registro de vacunas y si tiene BCG y HVB
      let tieneBCG = false;
      let tieneHVB = false;
      
      if (vacunas.length > 0) {
        const vacuna = vacunas[0]; // Solo hay un registro por niño
        tieneBCG = vacuna.fecha_bcg && vacuna.estado_bcg === 'SI';
        tieneHVB = vacuna.fecha_hvb && vacuna.estado_hvb === 'SI';
      }

      if (!tieneBCG || !tieneHVB) {
        alertas.push(`
          <div style="background: #f0fdf4; border-left: 4px solid #10b981; padding: 1rem; border-radius: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
              <span style="font-size: 1.5rem;">💉</span>
              <h4 style="font-weight: 600; color: #1e293b; margin: 0;">Vacunas del Recién Nacido</h4>
            </div>
            <p style="font-size: 0.875rem; color: #64748b; margin: 0.25rem 0;"><strong>${nombre}</strong></p>
            <p style="font-size: 0.875rem; color: #64748b; margin: 0.25rem 0;">DNI: ${documento}</p>
            <p style="font-size: 0.875rem; color: #059669; margin-top: 0.5rem;">Faltan ${(!tieneBCG ? 1 : 0) + (!tieneHVB ? 1 : 0)} vacuna(s) del recién nacido (${!tieneBCG ? 'BCG' : ''}${!tieneBCG && !tieneHVB ? ', ' : ''}${!tieneHVB ? 'HVB' : ''})</p>
          </div>
        `);
      }
    } catch (error) {
      console.error('Error al verificar vacunas:', error);
    }
  }

  return alertas;
}

// Cargar top establecimientos
function cargarTopEstablecimientos() {
  if (!window.dashboardRoutes || !window.dashboardRoutes.topEstablecimientos) {
    console.error('Ruta de top establecimientos no definida');
    return;
  }
  
  fetch(window.dashboardRoutes.topEstablecimientos, {
    method: 'GET',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': getCsrfToken()
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success && data.data) {
      // Renderizar top establecimientos
      const topContainer = document.getElementById('topEstablecimientosContainer');
      if (topContainer) {
        if (data.data.top_establecimientos && data.data.top_establecimientos.length > 0) {
          let html = '';
          data.data.top_establecimientos.forEach((est, index) => {
            const posicion = index + 1;
            html += `
              <div data-testid="top-establishment-${index}"
                class="flex items-center gap-4 p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                <div
                  class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0"
                  style="background: linear-gradient(135deg, rgb(16, 185, 129), rgb(5, 150, 105));">${posicion}</div>
                <div class="flex-1">
                  <p class="font-semibold text-slate-800">${est.establecimiento || 'Sin nombre'}</p>
                  <div class="flex items-center gap-4 mt-1">
                    <p class="text-sm text-slate-500">${est.total_controles} control${est.total_controles !== 1 ? 'es' : ''}</p>
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium">${est.calidad_porcentaje}% calidad</span>
                  </div>
                </div>
              </div>
            `;
          });
          topContainer.innerHTML = html;
        } else {
          topContainer.innerHTML = `
            <div class="text-center py-8 text-slate-500">
              <p>No hay establecimientos con datos suficientes para mostrar.</p>
            </div>
          `;
        }
      }

      // Renderizar establecimientos que necesitan mejora
      const mejoraContainer = document.getElementById('necesitanMejoraContainer');
      if (mejoraContainer) {
        if (data.data.necesitan_mejora && data.data.necesitan_mejora.length > 0) {
          let html = '';
          data.data.necesitan_mejora.forEach((est, index) => {
            const posicion = index + 1;
            html += `
              <div data-testid="improvement-establishment-${index}"
                class="flex items-center gap-4 p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                <div
                  class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0"
                  style="background: linear-gradient(135deg, rgb(245, 158, 11), rgb(217, 119, 6));">${posicion}</div>
                <div class="flex-1">
                  <p class="font-semibold text-slate-800">${est.establecimiento || 'Sin nombre'}</p>
                  <div class="flex items-center gap-4 mt-1">
                    <p class="text-sm text-slate-500">${est.total_controles} control${est.total_controles !== 1 ? 'es' : ''}</p>
                    <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-medium">${est.calidad_porcentaje}% calidad</span>
                  </div>
                </div>
              </div>
            `;
          });
          mejoraContainer.innerHTML = html;
        } else {
          mejoraContainer.innerHTML = `
            <div class="text-center py-8 text-green-600">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 1rem;">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
              <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">¡Excelente!</p>
              <p>Todos los establecimientos tienen buena calidad de datos.</p>
            </div>
          `;
        }
      }
    }
  })
  .catch(error => {
    console.error('Error al cargar top establecimientos:', error);
    const topContainer = document.getElementById('topEstablecimientosContainer');
    const mejoraContainer = document.getElementById('necesitanMejoraContainer');
    
    if (topContainer) {
      topContainer.innerHTML = `
        <div class="text-center py-8 text-red-500">
          <p style="font-weight: 600; margin-bottom: 0.5rem;">Error al cargar datos</p>
          <p style="font-size: 0.875rem;">Por favor, recarga la página e intenta nuevamente.</p>
        </div>
      `;
    }
    
    if (mejoraContainer) {
      mejoraContainer.innerHTML = `
        <div class="text-center py-8 text-red-500">
          <p style="font-weight: 600; margin-bottom: 0.5rem;">Error al cargar datos</p>
          <p style="font-size: 0.875rem;">Por favor, recarga la página e intenta nuevamente.</p>
        </div>
      `;
    }
  });
}

