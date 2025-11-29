<!-- Tab Visitas Domiciliarias -->
<div id="tab-visitas" class="tab-content" style="display: none;">
  <div class="control-section">
    <div class="section-header">
      <h3>
        <span class="section-icon">
        </span>
        VISITAS DOMICILIARIAS
      </h3>
      <p>Registro de visitas domiciliarias realizadas al niño durante el primer año de vida</p>
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
        <div style="font-size: 13px; font-weight: 600; color: #0f172a;" id="fecha-nacimiento-visitas">-</div>
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
          <div style="font-size: 13px; font-weight: 700; color: #065f46; margin-bottom: 6px;">¿Cómo saber si una visita cumple?</div>
          <div style="font-size: 12px; color: #1e293b; line-height: 1.6;">
            <strong>CUMPLE:</strong> Si la visita se realizó dentro del rango de edad establecido (ver columna "Rango Estimado")<br>
            <strong>NO CUMPLE:</strong> Si la visita se realizó fuera del rango establecido<br>
            <strong>SEGUIMIENTO:</strong> Si la visita aún no ha sido registrada
          </div>
        </div>
      </div>
    </div>

    <!-- Tabla de Visitas Domiciliarias -->
    <div style="margin-top: 24px; overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
          <thead>
            <tr style="background: linear-gradient(to right, #3b82f6, #2563eb); color: white;">
              <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Período</th>
              <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Rango Estimado</th>
              <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Edad en Días</th>
              <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Fecha de Visita</th>
              <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Estado</th>
            </tr>
          </thead>
          <tbody>
            <!-- Visita 28 días -->
            <tr style="border-bottom: 1px solid #e5e7eb;">
              <td style="padding: 12px; color: #1e293b; font-weight: 600;">28 días</td>
              <td style="padding: 12px; color: #64748b;">28 días</td>
              <td style="padding: 12px; color: #64748b;" id="visita-edad-28d">-</td>
              <td style="padding: 12px; color: #64748b;" id="visita-fecha-28d">-</td>
              <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="visita-estado-28d">SEGUIMIENTO</span></td>
            </tr>
            <!-- Visita 2-5 meses -->
            <tr style="border-bottom: 1px solid #e5e7eb;">
              <td style="padding: 12px; color: #1e293b; font-weight: 600;">2-5 meses</td>
              <td style="padding: 12px; color: #64748b;">60 a 150 días</td>
              <td style="padding: 12px; color: #64748b;" id="visita-edad-2-5m">-</td>
              <td style="padding: 12px; color: #64748b;" id="visita-fecha-2-5m">-</td>
              <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="visita-estado-2-5m">SEGUIMIENTO</span></td>
            </tr>
            <!-- Visita 6-8 meses -->
            <tr style="border-bottom: 1px solid #e5e7eb;">
              <td style="padding: 12px; color: #1e293b; font-weight: 600;">6-8 meses</td>
              <td style="padding: 12px; color: #64748b;">180 a 240 días</td>
              <td style="padding: 12px; color: #64748b;" id="visita-edad-6-8m">-</td>
              <td style="padding: 12px; color: #64748b;" id="visita-fecha-6-8m">-</td>
              <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="visita-estado-6-8m">SEGUIMIENTO</span></td>
            </tr>
            <!-- Visita 9-11 meses -->
            <tr style="border-bottom: 1px solid #e5e7eb;">
              <td style="padding: 12px; color: #1e293b; font-weight: 600;">9-11 meses</td>
              <td style="padding: 12px; color: #64748b;">270 a 330 días</td>
              <td style="padding: 12px; color: #64748b;" id="visita-edad-9-11m">-</td>
              <td style="padding: 12px; color: #64748b;" id="visita-fecha-9-11m">-</td>
              <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="visita-estado-9-11m">SEGUIMIENTO</span></td>
            </tr>
          </tbody>
        </table>
    </div>
  </div>
</div>
