/**
 * ============================================
 * MODAL DE IMPORTAR CONTROLES - JAVASCRIPT
 * ============================================
 * Maneja toda la funcionalidad del modal de importación
 */

(function() {
  'use strict';

  // Variables globales
  const MODAL_ID = 'importarControlesModal';
  const FORM_ID = 'formImportarControles';
  const FILE_INPUT_ID = 'archivo_excel_modal';
  const FILE_NAME_ID = 'fileName';
  const BTN_IMPORTAR_ID = 'btnImportar';
  const DROP_ZONE_ID = 'fileDropZone';
  const PROGRESS_CONTAINER_ID = 'progressContainer';
  const PROGRESS_BAR_ID = 'progressBar';
  const PROGRESS_TEXT_ID = 'progressText';

  /**
   * Abrir el modal de importación
   */
  function openImportarControlesModal() {
    const modal = document.getElementById(MODAL_ID);
    if (!modal) {
      console.error('❌ Modal importarControlesModal no encontrado');
      return;
    }

    modal.classList.remove('hidden');
    modal.classList.add('active');
    modal.style.display = 'flex';
    
    // Inicializar drag & drop
    initDragAndDrop();
    
    // Prevenir scroll del body
    document.body.style.overflow = 'hidden';
  }

  /**
   * Cerrar el modal de importación
   */
  function closeImportarControlesModal(event) {
    if (event && event.target !== event.currentTarget && event.currentTarget) {
      return;
    }

    const modal = document.getElementById(MODAL_ID);
    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('active');
    modal.style.display = 'none';

    // Restaurar scroll del body
    document.body.style.overflow = '';

    // Limpiar el formulario
    resetForm();
  }

  /**
   * Resetear el formulario
   */
  function resetForm() {
    const form = document.getElementById(FORM_ID);
    if (form) {
      form.reset();
    }

    const fileName = document.getElementById(FILE_NAME_ID);
    if (fileName) {
      fileName.textContent = 'Ningún archivo seleccionado';
      fileName.classList.remove('has-file', 'text-green-600', 'font-semibold');
      fileName.classList.add('text-slate-500');
    }

    const btnImportar = document.getElementById(BTN_IMPORTAR_ID);
    if (btnImportar) {
      btnImportar.disabled = true;
      btnImportar.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
          <polyline points="17 8 12 3 7 8"></polyline>
          <line x1="12" x2="12" y1="3" y2="15"></line>
        </svg>
        Importar Controles
      `;
    }

    // Ocultar barra de progreso
    hideProgress();
  }

  /**
   * Manejar la selección de archivo
   */
  function handleFileSelect(input) {
    const file = input.files[0];
    const fileName = document.getElementById(FILE_NAME_ID);
    const btnImportar = document.getElementById(BTN_IMPORTAR_ID);

    if (file) {
      // Validar tamaño (10MB)
      const maxSize = 10 * 1024 * 1024; // 10MB en bytes
      if (file.size > maxSize) {
        alert('El archivo excede el tamaño máximo permitido de 10MB');
        input.value = '';
        return;
      }

      // Validar tipo de archivo
      const validTypes = ['.xlsx', '.xls', '.csv'];
      const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
      if (!validTypes.includes(fileExtension)) {
        alert('Tipo de archivo no válido. Solo se permiten archivos .xlsx, .xls o .csv');
        input.value = '';
        return;
      }

      // Actualizar UI
      if (fileName) {
        const fileSizeKB = (file.size / 1024).toFixed(2);
        fileName.textContent = `${file.name} (${fileSizeKB} KB)`;
        fileName.classList.add('has-file');
      }

      if (btnImportar) {
        btnImportar.disabled = false;
      }
    } else {
      // Resetear si no hay archivo
      if (fileName) {
        fileName.textContent = 'Ningún archivo seleccionado';
        fileName.classList.remove('has-file');
      }

      if (btnImportar) {
        btnImportar.disabled = true;
      }
    }
  }

  /**
   * Inicializar drag & drop
   */
  function initDragAndDrop() {
    const dropZone = document.getElementById(DROP_ZONE_ID);
    const fileInput = document.getElementById(FILE_INPUT_ID);

    if (!dropZone || !fileInput) return;

    // Prevenir comportamientos por defecto
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      dropZone.addEventListener(eventName, preventDefaults, false);
      document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
      e.preventDefault();
      e.stopPropagation();
    }

    // Efectos visuales al arrastrar
    ['dragenter', 'dragover'].forEach(eventName => {
      dropZone.addEventListener(eventName, () => {
        dropZone.classList.add('drag-over');
      }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
      dropZone.addEventListener(eventName, () => {
        dropZone.classList.remove('drag-over');
      }, false);
    });

    // Manejar drop
    dropZone.addEventListener('drop', (e) => {
      const dt = e.dataTransfer;
      const files = dt.files;

      if (files.length > 0) {
        fileInput.files = files;
        handleFileSelect(fileInput);
      }
    }, false);
  }

  /**
   * Mostrar barra de progreso
   */
  function showProgress() {
    const progressContainer = document.getElementById(PROGRESS_CONTAINER_ID);
    const progressBar = document.getElementById(PROGRESS_BAR_ID);
    const progressText = document.getElementById(PROGRESS_TEXT_ID);

    if (progressContainer) {
      progressContainer.classList.add('active');
      progressContainer.style.display = 'block';
    }

    // Simular progreso
    let progress = 0;
    const interval = setInterval(() => {
      progress += 10;
      if (progressBar) {
        progressBar.style.width = progress + '%';
      }
      if (progressText) {
        progressText.textContent = `Procesando... ${progress}%`;
      }
      if (progress >= 90) {
        clearInterval(interval);
      }
    }, 200);
  }

  /**
   * Ocultar barra de progreso
   */
  function hideProgress() {
    const progressContainer = document.getElementById(PROGRESS_CONTAINER_ID);
    const progressBar = document.getElementById(PROGRESS_BAR_ID);
    const progressText = document.getElementById(PROGRESS_TEXT_ID);

    if (progressContainer) {
      progressContainer.classList.remove('active');
      progressContainer.style.display = 'none';
    }

    if (progressBar) {
      progressBar.style.width = '0%';
    }

    if (progressText) {
      progressText.textContent = 'Procesando...';
    }
  }

  /**
   * Manejar envío del formulario
   */
  function handleFormSubmit(e) {
    const fileInput = document.getElementById(FILE_INPUT_ID);
    const btnImportar = document.getElementById(BTN_IMPORTAR_ID);

    if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
      e.preventDefault();
      alert('Por favor, selecciona un archivo para importar.');
      return false;
    }

    // Mostrar barra de progreso
    showProgress();

    // Deshabilitar botón y mostrar spinner
    if (btnImportar) {
      btnImportar.disabled = true;
      btnImportar.innerHTML = `
        <svg class="modal-importar-spinner" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.25" stroke-width="4"></circle>
          <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Importando...
      `;
    }
  }

  /**
   * Manejar mensajes de sesión después de importar
   */
  function handleSessionMessages() {
    // Esto se ejecutará desde el blade template con datos de Laravel
    // Solo definimos la función aquí para que esté disponible
  }

  /**
   * Inicializar cuando el DOM esté listo
   */
  function init() {
    // Event listeners
    const fileInput = document.getElementById(FILE_INPUT_ID);
    if (fileInput) {
      fileInput.addEventListener('change', function() {
        handleFileSelect(this);
      });
    }

    const form = document.getElementById(FORM_ID);
    if (form) {
      form.addEventListener('submit', handleFormSubmit);
    }

    // Cerrar modal con Escape
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        const modal = document.getElementById(MODAL_ID);
        if (modal && !modal.classList.contains('hidden')) {
          closeImportarControlesModal();
        }
      }
    });

    // Cerrar modal al hacer clic fuera
    const modal = document.getElementById(MODAL_ID);
    if (modal) {
      modal.addEventListener('click', function(e) {
        if (e.target === modal) {
          closeImportarControlesModal(e);
        }
      });
    }
  }

  // Exportar funciones globalmente
  window.openImportarControlesModal = openImportarControlesModal;
  window.closeImportarControlesModal = closeImportarControlesModal;
  window.handleFileSelect = handleFileSelect;
  window.initDragAndDrop = initDragAndDrop;

  // Inicializar cuando el DOM esté listo
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  // Esta función será llamada desde el blade template
  window.handleImportSessionMessages = function() {
    // Esta función se define aquí pero se llama desde el blade
  };

})();

