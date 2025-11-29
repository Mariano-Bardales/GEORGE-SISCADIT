<!-- Tab CRED Mensual -->
<div id="tab-cred" class="tab-content" style="display: none;">
  <div class="control-section">
    <div class="section-header">
      <h3>
        <span class="section-icon">
        </span>
        ANÁLISIS DE LA ETAPA DEL NIÑO
      </h3>
      <p>Durante el primer año de vida, los niños deben pasar por 11 controles mensuales de salud (CRED)</p>
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
        <div style="font-size: 13px; font-weight: 600; color: #0f172a;" id="fecha-nacimiento-cred-mensual">-</div>
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
          <div style="font-size: 12px; color: #1e293b; line-height: 1.6;">
            <strong>CUMPLE:</strong> Control registrado dentro del rango permitido<br>
            <strong>NO CUMPLE:</strong> Control fuera del rango o control faltante que ya venció<br>
            <strong>SEGUIMIENTO:</strong> Control no registrado pero aún dentro del plazo
          </div>
        </div>
      </div>
    </div>

    <!-- Tabla de Controles CRED Mensuales -->
    <div style="margin-top: 24px; overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <thead>
          <tr style="background: linear-gradient(to right, #3b82f6, #2563eb); color: white;">
            <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Control Estimado</th>
            <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Rango Estimado</th>
            <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Fecha del Control</th>
            <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Edad en Días</th>
            <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Estado</th>
          </tr>
        </thead>
        <tbody>
          <!-- Mes 1 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 1</td>
            <td style="padding: 12px; color: #64748b;">29 a 59 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_1">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_1">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_1">SEGUIMIENTO</span></td>
          </tr>
          <!-- Mes 2 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 2</td>
            <td style="padding: 12px; color: #64748b;">60 a 89 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_2">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_2">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_2">SEGUIMIENTO</span></td>
          </tr>
          <!-- Mes 3 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 3</td>
            <td style="padding: 12px; color: #64748b;">90 a 119 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_3">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_3">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_3">SEGUIMIENTO</span></td>
          </tr>
          <!-- Mes 4 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 4</td>
            <td style="padding: 12px; color: #64748b;">120 a 149 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_4">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_4">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_4">SEGUIMIENTO</span></td>
          </tr>
          <!-- Mes 5 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 5</td>
            <td style="padding: 12px; color: #64748b;">150 a 179 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_5">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_5">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_5">SEGUIMIENTO</span></td>
          </tr>
          <!-- Mes 6 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 6</td>
            <td style="padding: 12px; color: #64748b;">180 a 209 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_6">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_6">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_6">SEGUIMIENTO</span></td>
          </tr>
          <!-- Mes 7 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 7</td>
            <td style="padding: 12px; color: #64748b;">210 a 239 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_7">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_7">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_7">SEGUIMIENTO</span></td>
          </tr>
          <!-- Mes 8 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 8</td>
            <td style="padding: 12px; color: #64748b;">240 a 269 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_8">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_8">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_8">SEGUIMIENTO</span></td>
          </tr>
          <!-- Mes 9 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 9</td>
            <td style="padding: 12px; color: #64748b;">270 a 299 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_9">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_9">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_9">SEGUIMIENTO</span></td>
          </tr>
          <!-- Mes 10 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 10</td>
            <td style="padding: 12px; color: #64748b;">300 a 329 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_10">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_10">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_10">SEGUIMIENTO</span></td>
          </tr>
          <!-- Mes 11 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 11</td>
            <td style="padding: 12px; color: #64748b;">330 a 359 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_11">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_11">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_11">SEGUIMIENTO</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>