<!-- Modal para Registrar Vacuna del Recién Nacido -->
<div id="modalVacuna" class="modal-registro-overlay modal-tipo-vacuna hidden" onclick="closeModalVacuna(event)">
  <div class="modal-registro-content modal-tipo-vacuna" onclick="event.stopPropagation()">
    <div class="modal-registro-header modal-vacuna-header">
      <div class="modal-vacuna-header-left">
        <div class="modal-vacuna-icon-circle">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
          </svg>
        </div>
        <h3 class="modal-registro-title modal-vacuna-title">Vacunas del Recién Nacido</h3>
      </div>
      <button class="modal-registro-close modal-vacuna-close" onclick="closeModalVacuna()" type="button">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 6 6 18"></path>
          <path d="m6 6 12 12"></path>
        </svg>
      </button>
    </div>
    <div class="modal-registro-body">
      <form id="formVacuna" class="modal-registro-form" onsubmit="registrarVacuna(event)">
        <input type="hidden" id="vacunaNinoId" name="nino_id">
        
        <!-- Sección Información del Control -->
        <div style="margin-bottom: 1.5rem; padding: 1.25rem; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 0.75rem; color: white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
          <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
            <div style="background: rgba(255, 255, 255, 0.2); padding: 0.5rem; border-radius: 0.5rem;">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white;">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
            </div>
            <div>
              <h4 style="margin: 0; font-size: 1.125rem; font-weight: 700; color: white;">Vacunas del Recién Nacido</h4>
              <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; opacity: 0.9;">Registro de vacunas BCG y HVB (0-30 días)</p>
            </div>
          </div>
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.2);">
            <div>
              <p style="margin: 0; font-size: 0.75rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Paciente</p>
              <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; font-weight: 600;" id="vacunaInfoPaciente">-</p>
            </div>
            <div>
              <p style="margin: 0; font-size: 0.75rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Documento</p>
              <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; font-weight: 600;" id="vacunaInfoDocumento">-</p>
            </div>
            <div>
              <p style="margin: 0; font-size: 0.75rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Establecimiento</p>
              <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; font-weight: 600;" id="vacunaInfoEstablecimiento">-</p>
            </div>
            <div>
              <p style="margin: 0; font-size: 0.75rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Edad Actual</p>
              <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; font-weight: 600;" id="vacunaInfoEdad">-</p>
            </div>
          </div>
        </div>
        
        <!-- El contenido del formulario se cargará dinámicamente -->
        <div class="modal-registro-footer">
          <button type="button" onclick="closeModalVacuna()" class="modal-registro-btn modal-registro-btn-cancel">
            Cancelar
          </button>
          <button type="submit" class="modal-registro-btn modal-registro-btn-submit modal-vacuna-btn-submit">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
              <polyline points="17 21 17 13 7 13 7 21"></polyline>
              <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            Guardar Vacuna
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

