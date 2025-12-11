<!-- Modal para Ver Controles de Salud -->
<div id="verControlesModal" class="modal-overlay hidden" onclick="closeVerControlesModal(event)">
  <div class="modal-controles" data-testid="modal-controles" onclick="event.stopPropagation()">
    <div class="modal-header">
      <div class="modal-title-section">
        <h2 class="modal-title">Controles de Salud</h2>
        <div class="nino-info">
          <p id="modalPatientName"><strong>-</strong></p>
          <p id="modalPatientInfo">-</p>
          <p id="modalPatientFechaNacimiento" style="margin-top: 4px; font-size: 13px; color: #64748b;"><strong>Fecha de Nacimiento:</strong> <span id="fechaNacimientoValue">-</span></p>
        </div>
      </div>
      <div class="flex items-center gap-2">
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'ADMIN')
          <button 
            id="btnEliminarNino" 
            class="btn-eliminar-nino" 
            onclick="confirmarEliminarNino()"
            title="Eliminar todos los datos del niño (solo admin)"
            style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; border: none; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 600; transition: all 0.2s; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);"
            onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 8px rgba(239, 68, 68, 0.3)'"
            onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 4px rgba(239, 68, 68, 0.2)'">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 6h18"></path>
              <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
              <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
            </svg>
            Eliminar
          </button>
        @endif
        <button class="btn-close" data-testid="btn-close-modal" onclick="closeVerControlesModal()">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x" aria-hidden="true">
            <path d="M18 6 6 18"></path>
            <path d="m6 6 12 12"></path>
          </svg>
        </button>
      </div>
    </div>
    <!-- Sección de Alertas -->
    <div id="alertasSection" class="alertas-container" style="display: none;">
      <div class="alertas-header">
        <h3 class="alertas-title">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle">
            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
            <path d="M12 9v4"></path>
            <path d="M12 17h.01"></path>
          </svg>
          Análisis de Cumplimiento
        </h3>
      </div>
      <div class="alertas-content">
        <div class="alerta-item" id="alertaRecienNacido" style="display: none;">
          <div class="alerta-header">
            <span class="alerta-icon">🍼</span>
            <span class="alerta-titulo">Control del Recién Nacido (0-28 días)</span>
            <span class="alerta-estado" id="estadoRecienNacido"></span>
          </div>
          <div class="alerta-body">
            <div class="alerta-datos">
              <p><strong>Datos del paciente:</strong></p>
              <p id="datosPacienteRecienNacido">-</p>
            </div>
            <div class="alerta-error">
              <p><strong>Situación detectada:</strong></p>
              <p id="errorRecienNacido" class="error-message">-</p>
            </div>
            <div class="alerta-sugerencia">
              <p><strong>💡 Sugerencia:</strong></p>
              <p id="sugerenciaRecienNacido" class="sugerencia-text">-</p>
            </div>
          </div>
        </div>
        <div class="alerta-item" id="alertaCredMensual" style="display: none;">
          <div class="alerta-header">
            <span class="alerta-icon">👶</span>
            <span class="alerta-titulo">CRED Mensual (1-11 meses)</span>
            <span class="alerta-estado" id="estadoCredMensual"></span>
          </div>
          <div class="alerta-body">
            <div class="alerta-datos">
              <p><strong>Datos del paciente:</strong></p>
              <p id="datosPacienteCred">-</p>
            </div>
            <div class="alerta-error">
              <p><strong>Situación detectada:</strong></p>
              <p id="errorCredMensual" class="error-message">-</p>
            </div>
            <div class="alerta-sugerencia">
              <p><strong>💡 Sugerencia:</strong></p>
              <p id="sugerenciaCredMensual" class="sugerencia-text">-</p>
            </div>
          </div>
        </div>
        <div class="alerta-item" id="alertaTamizaje" style="display: none;">
          <div class="alerta-header">
            <span class="alerta-icon">🧬</span>
            <span class="alerta-titulo">Tamizaje Neonatal</span>
            <span class="alerta-estado" id="estadoTamizaje"></span>
          </div>
          <div class="alerta-body">
            <div class="alerta-datos">
              <p><strong>Datos del paciente:</strong></p>
              <p id="datosPacienteTamizaje">-</p>
            </div>
            <div class="alerta-error">
              <p><strong>Situación detectada:</strong></p>
              <p id="errorTamizaje" class="error-message">-</p>
            </div>
            <div class="alerta-sugerencia">
              <p><strong>💡 Sugerencia:</strong></p>
              <p id="sugerenciaTamizaje" class="sugerencia-text">-</p>
            </div>
          </div>
        </div>
        <div class="alerta-item" id="alertaVisitas" style="display: none;">
          <div class="alerta-header">
            <span class="alerta-icon">🏠</span>
            <span class="alerta-titulo">Visitas Domiciliarias</span>
            <span class="alerta-estado" id="estadoVisitas"></span>
          </div>
          <div class="alerta-body">
            <div class="alerta-datos">
              <p><strong>Datos del paciente:</strong></p>
              <p id="datosPacienteVisitas">-</p>
            </div>
            <div class="alerta-error">
              <p><strong>Situación detectada:</strong></p>
              <p id="errorVisitas" class="error-message">-</p>
            </div>
            <div class="alerta-sugerencia">
              <p><strong>💡 Sugerencia:</strong></p>
              <p id="sugerenciaVisitas" class="sugerencia-text">-</p>
            </div>
          </div>
        </div>
        <div class="alerta-item" id="alertaVacunas" style="display: none;">
          <div class="alerta-header">
            <span class="alerta-icon">💉</span>
            <span class="alerta-titulo">Vacunas del Recién Nacido</span>
            <span class="alerta-estado" id="estadoVacunas"></span>
          </div>
          <div class="alerta-body">
            <div class="alerta-datos">
              <p><strong>Datos del paciente:</strong></p>
              <p id="datosPacienteVacunas">-</p>
            </div>
            <div class="alerta-error">
              <p><strong>Situación detectada:</strong></p>
              <p id="errorVacunas" class="error-message">-</p>
            </div>
            <div class="alerta-sugerencia">
              <p><strong>💡 Sugerencia:</strong></p>
              <p id="sugerenciaVacunas" class="sugerencia-text">-</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-tabs">
      <button class="tab-scroll-btn tab-scroll-left" onclick="scrollTabs('left')" aria-label="Scroll left">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left">
          <path d="m15 18-6-6 6-6"></path>
        </svg>
      </button>
      <div class="tabs-container">
        <button class="tab-button" data-testid="tab-cred-mensual" onclick="cambiarTab('cred', this)">
          <span class="tab-icon">👶</span>
          CRED Mensual (1-11 meses)
        </button>
        <button class="tab-button active" data-testid="tab-recien-nacido" onclick="cambiarTab('recien-nacido', this)">
          <span class="tab-icon">🍼</span>
          Control Recién Nacido (0-28 días)
        </button>
        <button class="tab-button" data-testid="tab-tamizaje" onclick="cambiarTab('tamizaje', this)">
          <span class="tab-icon">🧬</span>
          Tamizaje Neonatal
        </button>
        <button class="tab-button" data-testid="tab-cnv" onclick="cambiarTab('cnv', this)">
          <span class="tab-icon">👶</span>
          Recién Nacido (CNV)
        </button>
        <button class="tab-button" data-testid="tab-visitas" onclick="cambiarTab('visitas', this)">
          <span class="tab-icon">🏠</span>
          Visitas Domiciliarias
        </button>
        <button class="tab-button" data-testid="tab-vacunas" onclick="cambiarTab('vacunas', this)">
          <span class="tab-icon">💉</span>
          Vacunas del Recién Nacido
        </button>
      </div>
      
      <!-- Modal de Confirmación para Eliminar Niño -->
      <div id="modalConfirmarEliminarNino" class="modal-eliminar-nino-overlay" onclick="closeModalConfirmarEliminarNino(event)">
        <div class="modal-eliminar-nino-container" onclick="event.stopPropagation()">
          <!-- Header del Modal -->
          <div class="modal-eliminar-nino-header">
            <div class="modal-eliminar-nino-header-content">
              <div class="modal-eliminar-nino-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M3 6h18"></path>
                  <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                  <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                  <line x1="10" y1="11" x2="10" y2="17"></line>
                  <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
              </div>
              <div>
                <h3 class="modal-eliminar-nino-title">Confirmar Eliminación</h3>
                <p class="modal-eliminar-nino-subtitle">Esta acción no se puede deshacer</p>
              </div>
            </div>
            <button onclick="closeModalConfirmarEliminarNino()" class="modal-eliminar-nino-close">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
            </button>
          </div>
          
          <!-- Contenido del Modal -->
          <div class="modal-eliminar-nino-content">
            <div class="modal-eliminar-nino-icon-large">
              <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18"></path>
                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                <line x1="10" y1="11" x2="10" y2="17"></line>
                <line x1="14" y1="11" x2="14" y2="17"></line>
              </svg>
            </div>
            <h4 class="modal-eliminar-nino-question">¿Está seguro de eliminar todos los datos de este niño?</h4>
            <p class="modal-eliminar-nino-name" id="nombreNinoEliminar">-</p>
            
            <div class="modal-eliminar-nino-warning">
              <div class="modal-eliminar-nino-warning-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                  <path d="M12 9v4"></path>
                  <path d="M12 17h.01"></path>
                </svg>
                <span>Se eliminarán permanentemente:</span>
              </div>
              <ul class="modal-eliminar-nino-list">
                <li>
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                  </svg>
                  <span>Datos del niño</span>
                </li>
                <li>
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                  </svg>
                  <span>Datos de la madre</span>
                </li>
                <li>
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="9" y1="3" x2="9" y2="21"></line>
                    <line x1="3" y1="9" x2="21" y2="9"></line>
                  </svg>
                  <span>Datos extra</span>
                </li>
                <li>
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                  </svg>
                  <span>Todos los controles CRED</span>
                </li>
                <li>
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                  </svg>
                  <span>Todos los controles RN</span>
                </li>
                <li>
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 6v6l4 2"></path>
                  </svg>
                  <span>Tamizaje neonatal</span>
                </li>
                <li>
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                  </svg>
                  <span>Vacunas</span>
                </li>
                <li>
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                  </svg>
                  <span>CNV (Recién Nacido)</span>
                </li>
                <li>
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                  </svg>
                  <span>Visitas domiciliarias</span>
                </li>
              </ul>
            </div>
            
            <div class="modal-eliminar-nino-confirm-input">
              <label for="confirmacionEliminarNino" class="modal-eliminar-nino-label">
                Para confirmar, escribe <strong>"ELIMINAR"</strong> (en mayúsculas):
              </label>
              <input 
                type="text" 
                id="confirmacionEliminarNino" 
                class="modal-eliminar-nino-input" 
                placeholder="ESCRIBE ELIMINAR AQUÍ"
                autocomplete="off"
                onkeyup="validarConfirmacionEliminarNino()"
              />
            </div>
          </div>
          
          <!-- Footer del Modal -->
          <div class="modal-eliminar-nino-footer">
            <button type="button" onclick="closeModalConfirmarEliminarNino()" class="modal-eliminar-nino-btn modal-eliminar-nino-btn-cancel">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
              Cancelar
            </button>
            <button 
              type="button" 
              id="btnConfirmarEliminarNino" 
              onclick="ejecutarEliminarNino()" 
              class="modal-eliminar-nino-btn modal-eliminar-nino-btn-delete"
              disabled
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18"></path>
                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
              </svg>
              Eliminar Permanentemente
            </button>
          </div>
        </div>
      </div>
      <button class="tab-scroll-btn tab-scroll-right" onclick="scrollTabs('right')" aria-label="Scroll right">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right">
          <path d="m9 18 6-6-6-6"></path>
        </svg>
      </button>
    </div>
    <div class="modal-body">
      @include('controles.tabs.tab-cred-mensual')
      @include('controles.tabs.tab-recien-nacido')
      @include('controles.tabs.tab-tamizaje')
      @include('controles.tabs.tab-cnv')
      @include('controles.tabs.tab-visitas')
      @include('controles.tabs.tab-vacunas')
    </div>
  </div>
</div>

