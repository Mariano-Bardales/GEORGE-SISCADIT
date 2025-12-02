<!-- Modal para Ver/Editar Datos Extras -->
<div id="datosExtrasModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 overflow-y-auto" onclick="closeDatosExtrasModal(event)">
  <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-4xl w-full mx-4 my-8 transform transition-all" onclick="event.stopPropagation()">
    <div class="flex items-center justify-between mb-6">
      <h3 class="text-2xl font-bold text-slate-800" id="datosExtrasModalTitulo">Datos Extras</h3>
      <button onclick="closeDatosExtrasModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
    </div>
    <div class="space-y-6 max-h-[70vh] overflow-y-auto pr-2">
      <!-- Selección de Red y Establecimiento -->
      <div class="border-b border-slate-200 pb-4">
        <h4 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
            <circle cx="12" cy="10" r="3"></circle>
          </svg>
          Selección de Red y Establecimiento
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Red</label>
            <p id="datosExtras-red" class="text-sm text-slate-900 font-medium">-</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">MicroRed</label>
            <p id="datosExtras-microred" class="text-sm text-slate-900 font-medium">-</p>
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-600 mb-1">Establecimiento</label>
            <p id="datosExtras-establecimiento" class="text-sm text-slate-900 font-medium">-</p>
          </div>
        </div>
      </div>
      <!-- Más Datos del Niño -->
      <div class="border-b border-slate-200 pb-4">
        <h4 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M12 6v6l4 2"></path>
          </svg>
          Más Datos del Niño
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Distrito</label>
            <p id="datosExtras-distrito" class="text-sm text-slate-900">-</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Provincia</label>
            <p id="datosExtras-provincia" class="text-sm text-slate-900">-</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Departamento</label>
            <p id="datosExtras-departamento" class="text-sm text-slate-900">-</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Seguro</label>
            <p id="datosExtras-seguro" class="text-sm text-slate-900">-</p>
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-600 mb-1">Programa</label>
            <p id="datosExtras-programa" class="text-sm text-slate-900">-</p>
          </div>
        </div>
      </div>
      <!-- Datos de la Madre del Niño -->
      <div>
        <h4 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
          Datos de la Madre del Niño
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">DNI</label>
            <p id="datosExtras-dni-madre" class="text-sm text-slate-900">-</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Apellidos y Nombres</label>
            <p id="datosExtras-nombre-madre" class="text-sm text-slate-900">-</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Celular</label>
            <p id="datosExtras-celular-madre" class="text-sm text-slate-900">-</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Domicilio</label>
            <p id="datosExtras-domicilio-madre" class="text-sm text-slate-900">-</p>
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-600 mb-1">Referencia Dirección</label>
            <p id="datosExtras-referencia-madre" class="text-sm text-slate-900">-</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Botones del Modal -->
    <div class="flex justify-end items-center gap-3 mt-6 pt-6 border-t border-slate-200">
      <!-- Botón Cerrar -->
      <button type="button" onclick="closeDatosExtrasModal()" class="btn-cerrar-datos-extras">
        Cerrar
      </button>
    </div>
  </div>
</div>




