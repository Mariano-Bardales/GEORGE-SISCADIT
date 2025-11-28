<!-- Tab Control Recién Nacido -->
<div id="tab-recien-nacido" class="tab-content active" style="display: block;">
  <div class="control-section">
    <div class="section-header">
      <h3>
        <span class="section-icon">
        </span>
        CONTROLES DEL RECIÉN NACIDO (0-28 DÍAS)
      </h3>
      <p>Durante el primer mes de vida, los recién nacidos deben pasar por 4 controles de salud</p>
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
        <div style="font-size: 13px; font-weight: 600; color: #0f172a;" id="fecha-nacimiento-control-recien-nacido">-</div>
      </div>
    </div>

    <!-- Tabla de Controles Recién Nacido -->
    <div style="margin-top: 24px; overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <thead>
          <tr style="background: linear-gradient(to right, #3b82f6, #2563eb); color: white;">
            <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Control</th>
            <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Rango de Días</th>
            <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Fecha del Control</th>
            <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Edad en Días</th>
            <th style="padding: 12px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase;">Estado</th>
          </tr>
        </thead>
        <tbody>
          <!-- Control 1 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 1</td>
            <td style="padding: 12px; color: #64748b;">2 a 6 días</td>
            <td style="padding: 12px; color: #64748b;" id="control-1-fecha">-</td>
            <td style="padding: 12px; color: #64748b;" id="control-1-edad">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="control-1-estado">SEGUIMIENTO</span></td>
          </tr>
          <!-- Control 2 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 2</td>
            <td style="padding: 12px; color: #64748b;">7 a 13 días</td>
            <td style="padding: 12px; color: #64748b;" id="control-2-fecha">-</td>
            <td style="padding: 12px; color: #64748b;" id="control-2-edad">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="control-2-estado">SEGUIMIENTO</span></td>
          </tr>
          <!-- Control 3 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 3</td>
            <td style="padding: 12px; color: #64748b;">14 a 20 días</td>
            <td style="padding: 12px; color: #64748b;" id="control-3-fecha">-</td>
            <td style="padding: 12px; color: #64748b;" id="control-3-edad">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="control-3-estado">SEGUIMIENTO</span></td>
          </tr>
          <!-- Control 4 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 4</td>
            <td style="padding: 12px; color: #64748b;">21 a 28 días</td>
            <td style="padding: 12px; color: #64748b;" id="control-4-fecha">-</td>
            <td style="padding: 12px; color: #64748b;" id="control-4-edad">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="control-4-estado">SEGUIMIENTO</span></td>
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
          <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Controles Registrados</div>
          <div style="font-size: 18px; font-weight: 700; color: #10b981;" id="controles-registrados">0</div>
          <div style="font-size: 11px; color: #64748b; margin-top: 4px;">de 4 controles completados</div>
        </div>
        <div style="padding: 16px; background: white; border-radius: 8px; border-left: 4px solid #f59e0b;">
          <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Seguimiento</div>
          <div style="font-size: 18px; font-weight: 700; color: #f59e0b;" id="seguimiento-recien-nacido">-</div>
          <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Controles pendientes de registro</div>
        </div>
        <div style="padding: 16px; background: white; border-radius: 8px; border-left: 4px solid #ef4444;">
          <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">No Cumple</div>
          <div style="font-size: 18px; font-weight: 700; color: #ef4444;" id="no-cumple-recien-nacido">-</div>
          <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Controles fuera del rango establecido</div>
        </div>
      </div>
      <div style="margin-top: 16px; padding: 12px; background: white; border-radius: 8px;">
        <div style="font-size: 13px; color: #1e293b; font-weight: 600; margin-bottom: 8px;">Estado General:</div>
        <div style="font-size: 18px; font-weight: 700;">
          <span class="estado-badge estado-seguimiento" id="estado-general-control">SEGUIMIENTO</span>
        </div>
      </div>
    </div>
  </div>
</div>
