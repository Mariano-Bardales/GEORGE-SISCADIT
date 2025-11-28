<!-- Tab CNV (Carné de Nacido Vivo) -->
<div id="tab-cnv" class="tab-content" style="display: none;">
  <div class="control-section">
    <div class="section-header">
      <h3>
        <span class="section-icon">
        </span>
        RECIÉN NACIDO (CNV)
      </h3>
      <p>Información del Carné de Nacido Vivo y datos del recién nacido</p>
    </div>

    <!-- Información sobre Fecha de Nacimiento (estilo unificado) -->
    <div style="margin-top: 16px; padding: 10px 14px; background: #eef2ff; border-radius: 10px; border-left: 4px solid #3b82f6; display: flex; align-items: center; gap: 10px;">
      <div style="width: 28px; height: 28px; border-radius: 999px; background: #3b82f6; display: flex; align-items: center; justify-content: center;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="8" r="4"></circle>
          <path d="M5 21c1.5-3 4-5 7-5s5.5 2 7 5"></path>
        </svg>
      </div>
      <div>
        <div style="font-size: 12px; font-weight: 600; color: #2563eb; text-transform: uppercase; letter-spacing: 0.05em;">Fecha de nacimiento</div>
        <div style="font-size: 13px; font-weight: 600; color: #0f172a;" id="fecha-nacimiento-cnv">-</div>
      </div>
    </div>

    <!-- Tabla de Información del CNV -->
    <div id="cnv-container" style="margin-top: 24px;">
      <div class="info-card" style="padding: 0; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="background: linear-gradient(to right, #3b82f6, #2563eb); color: white; padding: 16px 20px;">
          <h4 style="margin: 0; font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
              <polyline points="14 2 14 8 20 8"></polyline>
              <line x1="16" x2="8" y1="13" y2="13"></line>
              <line x1="16" x2="8" y1="17" y2="17"></line>
            </svg>
            Datos del Recién Nacido
          </h4>
        </div>
        <div style="padding: 20px;">
          <table style="width: 100%; border-collapse: collapse;">
            <tbody>
              <!-- Peso al Nacer -->
              <tr class="info-row" style="border-bottom: 1px solid #e5e7eb;">
                <td style="padding: 16px 12px; width: 200px; font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: 600; vertical-align: middle;">
                  <div style="display: flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                      <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                      <path d="M4 22h16"></path>
                      <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                      <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                      <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                    </svg>
                    Peso al Nacer
                  </div>
                </td>
                <td style="padding: 16px 12px;">
                  <span style="font-size: 16px; font-weight: 600; color: #1e293b;">No registrado</span>
                </td>
              </tr>
              <!-- Edad Gestacional -->
              <tr class="info-row" style="border-bottom: 1px solid #e5e7eb;">
                <td style="padding: 16px 12px; width: 200px; font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: 600; vertical-align: middle;">
                  <div style="display: flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="12" cy="12" r="10"></circle>
                      <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    Edad Gestacional
                  </div>
                </td>
                <td style="padding: 16px 12px;">
                  <span style="font-size: 16px; font-weight: 600; color: #1e293b;">-</span>
                </td>
              </tr>
              <!-- Clasificación -->
              <tr class="info-row">
                <td style="padding: 16px 12px; width: 200px; font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: 600; vertical-align: middle;">
                  <div style="display: flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                      <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    Clasificación
                  </div>
                </td>
                <td style="padding: 16px 12px;">
                  <span class="estado-badge pendiente" style="display: inline-block; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;">PENDIENTE</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Evaluación del Cumplimiento -->
    <div style="margin-top: 24px; padding: 20px; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 12px; border: 1px solid #bae6fd;">
      <h4 style="margin: 0 0 16px 0; font-size: 16px; font-weight: 700; color: #0c4a6e; display: flex; align-items: center; gap: 8px;">
        <span>✅</span>
        Estado del Registro
      </h4>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-top: 16px;">
        <div style="padding: 16px; background: white; border-radius: 8px; border-left: 4px solid #10b981;">
          <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">CNV Registrado</div>
          <div style="font-size: 18px; font-weight: 700; color: #10b981;" id="cnv-registrado">-</div>
          <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Datos del recién nacido completos</div>
        </div>
        <div style="padding: 16px; background: white; border-radius: 8px; border-left: 4px solid #f59e0b;">
          <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Pendiente</div>
          <div style="font-size: 18px; font-weight: 700; color: #f59e0b;" id="cnv-pendiente">-</div>
          <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Faltan datos por registrar</div>
        </div>
        <div style="padding: 16px; background: white; border-radius: 8px; border-left: 4px solid #ef4444;">
          <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Incompleto</div>
          <div style="font-size: 18px; font-weight: 700; color: #ef4444;" id="cnv-incompleto">-</div>
          <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Información insuficiente</div>
        </div>
      </div>
      <div style="margin-top: 16px; padding: 12px; background: white; border-radius: 8px;">
        <div style="font-size: 13px; color: #1e293b; font-weight: 600; margin-bottom: 8px;">Estado General:</div>
        <div style="font-size: 18px; font-weight: 700;">
          <span class="estado-badge estado-seguimiento" id="estado-general-cnv">SEGUIMIENTO</span>
        </div>
      </div>
    </div>
  </div>
</div>
