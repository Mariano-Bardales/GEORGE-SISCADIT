<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>SISCADIT - {{ $visita ? 'Editar' : 'Registrar' }} Visita Domiciliaria</title>
  <link rel="stylesheet" href="{{ asset('Css/dashbord.css') }}">
  <link rel="stylesheet" href="{{ asset('Css/sidebar.css') }}">
</head>
<body>
  <div class="flex h-screen bg-slate-50">
    <x-sidebar-main activeRoute="controles-cred" />
    <main class="flex-1 overflow-auto">
      <div class="p-8 max-w-4xl mx-auto">
        <a href="{{ route('controles-cred') }}" class="inline-flex items-center text-sm text-slate-600 hover:text-slate-900 mb-6">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
          Volver
        </a>
        <div style="background: white; border-radius: 12px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
          <h1 style="font-size: 28px; font-weight: 700; color: #1e293b; margin-bottom: 24px;">
            {{ $visita ? 'Editar' : 'Registrar' }} Visita Domiciliaria
          </h1>
          <div style="margin-bottom: 24px; font-size: 14px; color: #64748b;">
            <strong>Niño:</strong> {{ $nino->apellidos_nombres }} | <strong>DNI:</strong> {{ $nino->numero_doc }} | 
            <strong>Período:</strong> {{ $periodoTexto }} @if($rango) ({{ $rango['min'] }}-{{ $rango['max'] }} días) @endif
          </div>
          <form id="formVisitaPage">
            @csrf
            <input type="hidden" name="nino_id" value="{{ $nino->id_niño }}">
            <input type="hidden" name="periodo" value="{{ $periodo }}">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 24px;">
              <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1e293b;">
                  Fecha de Visita <span style="color: #ef4444;">*</span>
                </label>
                <input type="date" name="fecha_visita" value="{{ $visita && $visita->fecha_visita ? \Carbon\Carbon::parse($visita->fecha_visita)->format('Y-m-d') : '' }}" 
                       style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;" required>
              </div>
              <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1e293b;">Tipo de Visita</label>
                <input type="text" name="tipo_visita" value="{{ $visita && $visita->tipo_visita ? $visita->tipo_visita : '' }}" 
                       placeholder="Opcional" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
              </div>
            </div>
            <div style="display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid #e5e7eb;">
              <button type="submit" style="flex: 1; padding: 12px 24px; background: linear-gradient(135deg, #4facfe, #00f2fe); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                {{ $visita ? 'Actualizar' : 'Registrar' }} Visita
              </button>
              <a href="{{ route('controles-cred') }}" style="padding: 12px 24px; background: white; color: #64748b; border: 1px solid #cbd5e1; border-radius: 8px; text-decoration: none; font-weight: 600;">
                Cancelar
              </a>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
  <script>
    document.getElementById('formVisitaPage').addEventListener('submit', async function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      const response = await fetch('/api/visitas/registrar', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        },
        body: formData
      });
      const data = await response.json();
      if (data.success) {
        alert('Visita registrada exitosamente');
        window.location.href = '{{ route("controles-cred") }}';
      } else {
        alert('Error: ' + (data.message || 'Error al registrar'));
      }
    });
  </script>
</body>
</html>


