<!-- Tab Vacunas del Recién Nacido -->
<div id="tab-vacunas" class="tab-content" style="display: none;">
  <div class="control-section">
    <div class="section-header">
      <h3>
        <span class="section-icon">
        </span>
        VACUNAS DEL RECIÉN NACIDO
      </h3>
      <p>Registro de vacunas aplicadas al recién nacido durante el primer mes de vida</p>
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
        <div style="font-size: 13px; font-weight: 600; color: #0f172a;" id="fecha-nacimiento-vacunas">-</div>
      </div>
    </div>

    <!-- Tabla de Vacunas -->
    <div style="margin-top: 24px; overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <thead>
          <tr style="background: linear-gradient(to right, #3b82f6, #2563eb); color: white;">
            <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Vacuna</th>
            <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Fecha de Aplicación</th>
            <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Edad en Días</th>
            <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Estado</th>
          </tr>
        </thead>
        <tbody>
          <!-- Vacuna BCG -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b; font-weight: 600;">BCG</td>
            <td style="padding: 12px; color: #64748b;" id="fecha-bcg">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad-bcg">-</td>
            <td style="padding: 12px;"><span class="estado-badge pendiente" id="estado-bcg">-</span></td>
          </tr>
          <!-- Vacuna HVB -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b; font-weight: 600;">HVB (Hepatitis B)</td>
            <td style="padding: 12px; color: #64748b;" id="fecha-hvb">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad-hvb">-</td>
            <td style="padding: 12px;"><span class="estado-badge pendiente" id="estado-hvb">-</span></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Evaluación del Cumplimiento -->
    <div style="margin-top: 24px; padding: 20px; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 12px; border: 1px solid #bae6fd;">
      <h4 style="margin: 0 0 16px 0; font-size: 16px; font-weight: 700; color: #0c4a6e; display: flex; align-items: center; gap: 8px;">
        <span>✅</span>
        Evaluación del Cumplimiento
      </h4>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-top: 16px;">
        <div style="padding: 16px; background: white; border-radius: 8px; border-left: 4px solid #10b981;">
          <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Vacunas Aplicadas</div>
          <div style="font-size: 18px; font-weight: 700; color: #10b981;" id="vacunas-aplicadas">0</div>
          <div style="font-size: 11px; color: #64748b; margin-top: 4px;">de 2 vacunas del recién nacido</div>
        </div>
        <div style="padding: 16px; background: white; border-radius: 8px; border-left: 4px solid #f59e0b;">
          <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Pendientes</div>
          <div style="font-size: 18px; font-weight: 700; color: #f59e0b;" id="vacunas-pendientes">-</div>
          <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Vacunas aún no aplicadas</div>
        </div>
        <div style="padding: 16px; background: white; border-radius: 8px; border-left: 4px solid #ef4444;">
          <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">No Cumple</div>
          <div style="font-size: 18px; font-weight: 700; color: #ef4444;" id="vacunas-no-cumple">-</div>
          <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Vacunas fuera del período recomendado</div>
        </div>
      </div>
      <div style="margin-top: 16px; padding: 12px; background: white; border-radius: 8px;">
        <div style="font-size: 13px; color: #1e293b; font-weight: 600; margin-bottom: 8px;">Estado General:</div>
        <div style="font-size: 18px; font-weight: 700;">
          <span class="estado-badge estado-seguimiento" id="estado-general-vacunas">SEGUIMIENTO</span>
        </div>
      </div>
    </div>
  </div>
</div>
