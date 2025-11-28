<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>SISCADIT - {{ $tamizaje ? 'Editar' : 'Registrar' }} Tamizaje Neonatal</title>
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
            {{ $tamizaje ? 'Editar' : 'Registrar' }} Tamizaje Neonatal
          </h1>
          <div style="margin-bottom: 24px; font-size: 14px; color: #64748b;">
            <strong>Niño:</strong> {{ $nino->apellidos_nombres }} | <strong>DNI:</strong> {{ $nino->numero_doc }}
          </div>
          <form id="formTamizajePage">
            @csrf
            <input type="hidden" name="nino_id" value="{{ $nino->id_niño }}">
            <div style="margin-bottom: 24px;">
              <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1e293b;">
                Fecha de Tamizaje <span style="color: #ef4444;">*</span>
              </label>
              <input type="date" name="fecha_tam_neo" value="{{ $tamizaje && $tamizaje->fecha_tam_neo ? \Carbon\Carbon::parse($tamizaje->fecha_tam_neo)->format('Y-m-d') : '' }}" 
                     style="width: 100%; max-width: 300px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;" required>
            </div>
            <div style="display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid #e5e7eb;">
              <button type="submit" style="flex: 1; padding: 12px 24px; background: linear-gradient(135deg, #4facfe, #00f2fe); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                {{ $tamizaje ? 'Actualizar' : 'Registrar' }} Tamizaje
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
    document.getElementById('formTamizajePage').addEventListener('submit', async function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      const response = await fetch('/api/tamizaje/registrar', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        },
        body: formData
      });
      const data = await response.json();
      if (data.success) {
        alert('Tamizaje registrado exitosamente');
        window.location.href = '{{ route("controles-cred") }}';
      } else {
        alert('Error: ' + (data.message || 'Error al registrar'));
      }
    });
  </script>
</body>
</html>


