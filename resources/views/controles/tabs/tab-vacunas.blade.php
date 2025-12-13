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

    <!-- Información sobre rangos y estados -->
    <div style="margin-top: 16px; padding: 14px 16px; background: #f8fafc; border-radius: 10px; border-left: 4px solid #10b981;">
      <div style="display: flex; align-items: start; gap: 12px;">
        <div style="width: 24px; height: 24px; border-radius: 50%; background: #10b981; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
          </svg>
        </div>
        <div style="flex: 1;">
          <div style="font-size: 13px; font-weight: 700; color: #065f46; margin-bottom: 6px;">¿Cómo saber si una vacuna cumple?</div>
          <div style="font-size: 12px; color: #1e293b; line-height: 1.6;">
            <strong>CUMPLE:</strong> Si la vacuna fue aplicada entre 0 y 2 días de vida<br>
            <strong>NO CUMPLE:</strong> Si la vacuna fue aplicada después de los 2 días de vida<br>
            <strong>PENDIENTE:</strong> Si la vacuna aún no ha sido aplicada
          </div>
        </div>
      </div>
    </div>

    <!-- Tabla de Vacunas -->
    <div style="margin-top: 24px; overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <thead>
          <tr style="background: linear-gradient(to right, #3b82f6, #2563eb); color: white;">
            <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Vacuna</th>
            <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Rango</th>
            <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Fecha de Aplicación</th>
            <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Edad en Días</th>
            <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Estado</th>
          </tr>
        </thead>
        <tbody>
          <!-- Vacuna BCG -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b; font-weight: 600;">BCG</td>
            <td style="padding: 12px; color: #64748b;">0 a 2 días</td>
            <td style="padding: 12px; color: #64748b;" id="fecha-bcg">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad-bcg">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado-bcg">PENDIENTE</span></td>
          </tr>
          <!-- Vacuna HVB -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b; font-weight: 600;">HVB (Hepatitis B)</td>
            <td style="padding: 12px; color: #64748b;">0 a 2 días</td>
            <td style="padding: 12px; color: #64748b;" id="fecha-hvb">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad-hvb">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado-hvb">PENDIENTE</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
