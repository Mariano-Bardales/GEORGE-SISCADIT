<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#000000">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="Registro de control CRED mensual">
  <title>SISCADIT - Registrar CRED Mensual</title>
  <link rel="stylesheet" href="{{ asset('Css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/dashbord.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/dashboard-main.css') }}">
</head>
<body>
  <div class="flex h-screen bg-slate-50">
    <x-sidebar-main activeRoute="controles-cred" />
    <main class="flex-1 overflow-auto">
      <div class="p-8 max-w-4xl mx-auto">
        <a href="{{ route('controles-cred') }}" class="inline-flex items-center text-sm text-slate-600 hover:text-slate-900 mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
          Volver a Controles CRED
        </a>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-6">
          <div>
            <h1 class="text-2xl font-bold text-slate-800 mb-1">Registrar Control CRED Mensual</h1>
            <p class="text-slate-500 text-sm">Niño: <span class="font-semibold">{{ $nino->apellidos_nombres }}</span> ({{ $nino->numero_doc }})</p>
            <p class="text-slate-500 text-sm">
              Control estimado: <span class="font-semibold">Control {{ $mes }}</span>
              @if($rango)
                · Rango: <span class="font-semibold">{{ $rango['min'] }} - {{ $rango['max'] }} días</span>
              @endif
            </p>
          </div>

          <form id="formCredMensualPage" class="space-y-6">
            @csrf
            <input type="hidden" name="nino_id" value="{{ $nino->id_niño ?? $nino->id }}">
            <input type="hidden" name="mes" value="{{ $mes }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha del Control *</label>
                <input type="date" name="fecha_control" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Edad en días (opcional)</label>
                <input type="number" name="edad" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Se calcula automáticamente si lo dejas vacío">
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Peso (kg)</label>
                <input type="number" step="0.01" min="0" name="peso" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Ej: 6.5">
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Talla (cm)</label>
                <input type="number" step="0.1" min="0" name="talla" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Ej: 65.5">
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">PC (cm)</label>
                <input type="number" step="0.1" min="0" name="perimetro_cefalico" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Ej: 42.0">
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Desarrollo (opcional)</label>
              <textarea name="desarrollo" rows="2" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Observaciones sobre desarrollo psicomotor"></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Observaciones</label>
              <textarea name="observaciones" rows="3" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Notas adicionales sobre el control"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
              <a href="{{ route('controles-cred') }}" class="px-4 py-2 text-sm font-semibold text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200">Cancelar</a>
              <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M5 12h14"></path>
                  <path d="M12 5v14"></path>
                </svg>
                Guardar Control
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>

  <script>
    document.getElementById('formCredMensualPage').addEventListener('submit', function (e) {
      e.preventDefault();
      const form = e.target;
      const formData = new FormData(form);

      fetch('{{ route("api.controles-cred-mensual.registrar") }}', {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert('Control CRED mensual registrado correctamente');
          window.location.href = '{{ route('controles-cred') }}';
        } else {
          alert(data.message || 'No se pudo registrar el control');
        }
      })
      .catch(err => {
        console.error(err);
        alert('Ocurrió un error al registrar el control');
      });
    });
  </script>
</body>
</html>