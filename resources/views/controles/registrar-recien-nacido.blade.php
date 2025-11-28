<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#000000">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="Registro de control recién nacido">
  <title>SISCADIT - {{ $control ? 'Editar' : 'Registrar' }} Control Recién Nacido</title>
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
        <!-- Botón Volver -->
        <a href="{{ route('controles-cred') }}" class="inline-flex items-center text-sm text-slate-600 hover:text-slate-900 mb-6 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
          Volver a Controles CRED
        </a>

        <!-- Card Principal -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; padding: 32px;">
          <!-- Header -->
          <div style="border-bottom: 1px solid #e5e7eb; padding-bottom: 24px; margin-bottom: 24px;">
            <h1 style="font-size: 28px; font-weight: 700; color: #1e293b; margin: 0 0 16px 0;">
              {{ $control ? 'Editar' : 'Registrar' }} Control Recién Nacido
            </h1>
            <div style="display: flex; flex-wrap: wrap; gap: 16px; font-size: 14px; color: #64748b;">
              <div>
                <span style="font-weight: 600; color: #475569;">Niño:</span> 
                <span style="margin-left: 4px;">{{ $nino->apellidos_nombres }}</span>
              </div>
              <div>
                <span style="font-weight: 600; color: #475569;">Documento:</span> 
                <span style="margin-left: 4px;">{{ $nino->numero_doc }}</span>
              </div>
              <div>
                <span style="font-weight: 600; color: #475569;">Control:</span> 
                <span style="margin-left: 4px; font-weight: 700; color: #667eea;">CRN{{ $numeroControl }}</span>
              </div>
              @if($rango)
              <div>
                <span style="font-weight: 600; color: #475569;">Rango:</span> 
                <span style="margin-left: 4px; color: #667eea;">{{ $rango['min'] }} - {{ $rango['max'] }} días</span>
              </div>
              @endif
            </div>
          </div>

          <!-- Formulario -->
          <form id="formControlRNPage">
            @csrf
            <input type="hidden" name="nino_id" value="{{ $nino->id_niño }}">
            <input type="hidden" name="numero_control" value="{{ $numeroControl }}">
            @if($control)
              <input type="hidden" name="control_id" value="{{ $control->id }}">
            @endif

            <!-- Información sobre Fecha de Nacimiento -->
            <div style="margin-bottom: 24px; padding: 10px 14px; background: #eef2ff; border-radius: 10px; border-left: 4px solid #667eea; display: flex; align-items: center; gap: 10px;">
              <div style="width: 28px; height: 28px; border-radius: 999px; background: #667eea; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="8" r="4"></circle>
                  <path d="M5 21c1.5-3 4-5 7-5s5.5 2 7 5"></path>
                </svg>
              </div>
              <div>
                <div style="font-size: 12px; font-weight: 600; color: #667eea; text-transform: uppercase; letter-spacing: 0.05em;">Fecha de nacimiento</div>
                <div style="font-size: 13px; font-weight: 600; color: #0f172a;">{{ $fechaNacimiento ? \Carbon\Carbon::parse($fechaNacimiento)->format('d/m/Y') : 'No disponible' }}</div>
              </div>
            </div>

            <!-- Fecha del Control -->
            <div style="margin-bottom: 24px;">
              <label style="display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                Fecha del Control <span style="color: #ef4444;">*</span>
              </label>
              <input 
                type="date" 
                id="fecha_control"
                name="fecha_control" 
                value="{{ $control && $control->fecha ? \Carbon\Carbon::parse($control->fecha)->format('Y-m-d') : '' }}" 
                style="width: 100%; max-width: 300px; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #1e293b; background: white;" 
                required>
            </div>

            <!-- Antropometría -->
            <div style="margin-bottom: 32px;">
              <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">
                Antropometría
              </h3>
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px;">
                <div>
                  <label style="display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                    Peso (g)
                  </label>
                  <input 
                    type="number" 
                    name="peso" 
                    step="0.01" 
                    min="0"
                    value="{{ $control && isset($control->peso) ? $control->peso : '' }}"
                    placeholder="3200"
                    style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #1e293b; background: white;">
                </div>
                <div>
                  <label style="display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                    Talla (cm)
                  </label>
                  <input 
                    type="number" 
                    name="talla" 
                    step="0.1" 
                    min="0"
                    value="{{ $control && isset($control->talla) ? $control->talla : '' }}"
                    placeholder="50.5"
                    style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #1e293b; background: white;">
                </div>
                <div>
                  <label style="display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                    Perímetro Cefálico (cm)
                  </label>
                  <input 
                    type="number" 
                    name="perimetro_cefalico" 
                    step="0.1" 
                    min="0"
                    value="{{ $control && isset($control->perimetro_cefalico) ? $control->perimetro_cefalico : '' }}"
                    placeholder="35.0"
                    style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #1e293b; background: white;">
                </div>
              </div>
            </div>

            <!-- Botones -->
            <div style="display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid #e5e7eb;">
              <button 
                type="submit"
                style="flex: 1; padding: 12px 24px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.2s;"
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.4)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                {{ $control ? 'Actualizar' : 'Registrar' }} Control
              </button>
              <a 
                href="{{ route('controles-cred') }}"
                style="padding: 12px 24px; background: white; color: #64748b; border: 1px solid #cbd5e1; border-radius: 8px; font-weight: 600; font-size: 14px; text-decoration: none; transition: all 0.2s;"
                onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#94a3b8';"
                onmouseout="this.style.background='white'; this.style.borderColor='#cbd5e1';">
                Cancelar
              </a>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>

  <script>
    document.getElementById('formControlRNPage').addEventListener('submit', async function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.textContent;
      
      submitBtn.disabled = true;
      submitBtn.textContent = 'Guardando...';
      
      try {
        const response = await fetch('/api/controles-recien-nacido/registrar', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
          },
          body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
          alert('Control registrado exitosamente');
          window.location.href = '{{ route("controles-cred") }}';
        } else {
          alert('Error: ' + (data.message || 'Error al registrar el control'));
          submitBtn.disabled = false;
          submitBtn.textContent = originalText;
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Error al procesar la solicitud');
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
      }
    });
  </script>
</body>
</html>
