<!-- Tab CRED Mensual -->
<div id="tab-cred" class="tab-content">
  <div class="control-section">
    <div class="section-header">
      <h3>
        <span class="section-icon">👶</span>
        ANÁLISIS DE LA ETAPA DEL NIÑO
      </h3>
      <p>Durante el primer año de vida, los niños deben pasar por 11 controles mensuales de salud (CRED)</p>
    </div>

    <!-- Información sobre Fecha de Nacimiento -->
    <div style="margin-top: 16px; padding: 12px; background: #fef3c7; border-radius: 8px; border-left: 4px solid #f59e0b;">
      <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
        <span style="font-size: 16px;">📅</span>
        <h4 style="margin: 0; font-size: 14px; font-weight: 700; color: #92400e;">Fecha de Nacimiento:</h4>
        <span style="font-size: 14px; font-weight: 600; color: #78350f;" id="fecha-nacimiento-cred-mensual">-</span>
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
            <th style="padding: 12px; text-align: center; font-weight: 600; font-size: 13px; text-transform: uppercase;">Acción</th>
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
            <td style="padding: 12px; text-align: center;">
              <button class="btn-registrar" onclick="abrirModalCredMensual(1)">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M5 12h14"></path>
                  <path d="M12 5v14"></path>
                </svg>
                Registrar
              </button>
            </td>
          </tr>
          <!-- Mes 2 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 2</td>
            <td style="padding: 12px; color: #64748b;">60 a 89 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_2">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_2">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_2">SEGUIMIENTO</span></td>
            <td style="padding: 12px; text-align: center;">
              <button class="btn-registrar" onclick="abrirModalCredMensual(2)">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M5 12h14"></path>
                  <path d="M12 5v14"></path>
                </svg>
                Registrar
              </button>
            </td>
          </tr>
          <!-- Mes 3 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 3</td>
            <td style="padding: 12px; color: #64748b;">90 a 119 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_3">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_3">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_3">SEGUIMIENTO</span></td>
            <td style="padding: 12px; text-align: center;">
              <button class="btn-registrar" onclick="abrirModalCredMensual(3)">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M5 12h14"></path>
                  <path d="M12 5v14"></path>
                </svg>
                Registrar
              </button>
            </td>
          </tr>
          <!-- Mes 4 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 4</td>
            <td style="padding: 12px; color: #64748b;">120 a 149 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_4">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_4">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_4">SEGUIMIENTO</span></td>
            <td style="padding: 12px; text-align: center;">
              <button class="btn-registrar" onclick="abrirModalCredMensual(4)">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M5 12h14"></path>
                  <path d="M12 5v14"></path>
                </svg>
                Registrar
              </button>
            </td>
          </tr>
          <!-- Mes 5 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 5</td>
            <td style="padding: 12px; color: #64748b;">150 a 179 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_5">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_5">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_5">SEGUIMIENTO</span></td>
            <td style="padding: 12px; text-align: center;">
              <button class="btn-registrar" onclick="abrirModalCredMensual(5)">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M5 12h14"></path>
                  <path d="M12 5v14"></path>
                </svg>
                Registrar
              </button>
            </td>
          </tr>
          <!-- Mes 6 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 6</td>
            <td style="padding: 12px; color: #64748b;">180 a 209 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_6">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_6">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_6">SEGUIMIENTO</span></td>
            <td style="padding: 12px; text-align: center;">
              <button class="btn-registrar" onclick="abrirModalCredMensual(6)">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M5 12h14"></path>
                  <path d="M12 5v14"></path>
                </svg>
                Registrar
              </button>
            </td>
          </tr>
          <!-- Mes 7 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 7</td>
            <td style="padding: 12px; color: #64748b;">210 a 239 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_7">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_7">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_7">SEGUIMIENTO</span></td>
            <td style="padding: 12px; text-align: center;">
              <button class="btn-registrar" onclick="abrirModalCredMensual(7)">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M5 12h14"></path>
                  <path d="M12 5v14"></path>
                </svg>
                Registrar
              </button>
            </td>
          </tr>
          <!-- Mes 8 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 8</td>
            <td style="padding: 12px; color: #64748b;">240 a 269 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_8">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_8">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_8">SEGUIMIENTO</span></td>
            <td style="padding: 12px; text-align: center;">
              <button class="btn-registrar" onclick="abrirModalCredMensual(8)">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M5 12h14"></path>
                  <path d="M12 5v14"></path>
                </svg>
                Registrar
              </button>
            </td>
          </tr>
          <!-- Mes 9 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 9</td>
            <td style="padding: 12px; color: #64748b;">270 a 299 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_9">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_9">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_9">SEGUIMIENTO</span></td>
            <td style="padding: 12px; text-align: center;">
              <button class="btn-registrar" onclick="abrirModalCredMensual(9)">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M5 12h14"></path>
                  <path d="M12 5v14"></path>
                </svg>
                Registrar
              </button>
            </td>
          </tr>
          <!-- Mes 10 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 10</td>
            <td style="padding: 12px; color: #64748b;">300 a 329 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_10">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_10">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_10">SEGUIMIENTO</span></td>
            <td style="padding: 12px; text-align: center;">
              <button class="btn-registrar" onclick="abrirModalCredMensual(10)">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M5 12h14"></path>
                  <path d="M12 5v14"></path>
                </svg>
                Registrar
              </button>
            </td>
          </tr>
          <!-- Mes 11 -->
          <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; color: #1e293b;">Control 11</td>
            <td style="padding: 12px; color: #64748b;">330 a 359 días</td>
            <td style="padding: 12px; color: #64748b;" id="fo_cred_11">-</td>
            <td style="padding: 12px; color: #64748b;" id="edad_cred_11">-</td>
            <td style="padding: 12px;"><span class="estado-badge estado-seguimiento" id="estado_cred_11">SEGUIMIENTO</span></td>
            <td style="padding: 12px; text-align: center;">
              <button class="btn-registrar" onclick="abrirModalCredMensual(11)">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M5 12h14"></path>
                  <path d="M12 5v14"></path>
                </svg>
                Registrar
              </button>
            </td>
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
          <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Cumple CRED</div>
          <div style="font-size: 18px; font-weight: 700; color: #10b981;" id="cumple-cred">-</div>
          <div style="font-size: 11px; color: #64748b; margin-top: 4px;">11 controles dentro del rango</div>
        </div>
        <div style="padding: 16px; background: white; border-radius: 8px; border-left: 4px solid #f59e0b;">
          <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Seguimiento</div>
          <div style="font-size: 18px; font-weight: 700; color: #f59e0b;" id="seguimiento-cred">-</div>
          <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
            Aún no cumple con el rango de todos los controles CRED (no ha completado el primer año).
          </div>
        </div>
        <div style="padding: 16px; background: white; border-radius: 8px; border-left: 4px solid #ef4444;">
          <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">No Cumple</div>
        <div style="font-size: 18px; font-weight: 700; color: #ef4444;" id="no-cumple-cred">-</div>
          <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
            No cumple con uno o varios controles dentro de los rangos establecidos.
          </div>
        </div>
      </div>
      <div style="margin-top: 16px; padding: 12px; background: white; border-radius: 8px;">
        <div style="font-size: 13px; color: #1e293b; font-weight: 600; margin-bottom: 8px;">Estado General:</div>
        <div style="font-size: 18px; font-weight: 700;">
          <span class="estado-badge estado-seguimiento" id="estado-general-cred">SEGUIMIENTO</span>
        </div>
      </div>
    </div>
  </div>
</div>


