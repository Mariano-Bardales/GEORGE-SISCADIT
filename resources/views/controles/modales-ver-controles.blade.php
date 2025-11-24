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
      <button class="btn-close" data-testid="btn-close-modal" onclick="closeVerControlesModal()">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x" aria-hidden="true">
          <path d="M18 6 6 18"></path>
          <path d="m6 6 12 12"></path>
        </svg>
      </button>
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

