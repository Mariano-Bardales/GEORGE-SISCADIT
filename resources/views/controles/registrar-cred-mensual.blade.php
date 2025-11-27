<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#000000">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="Registro de control CRED mensual">
  <title>SISCADIT - {{ $control ? 'Editar' : 'Registrar' }} Control CRED Mensual</title>
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
              {{ $control ? 'Editar' : 'Registrar' }} Control CRED Mensual
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
                <span style="margin-left: 4px; font-weight: 700; color: #3b82f6;">Control {{ $mes }}</span>
              </div>
              @if($rango)
              <div>
                <span style="font-weight: 600; color: #475569;">Rango:</span> 
                <span style="margin-left: 4px; color: #3b82f6;">{{ $rango['min'] }} - {{ $rango['max'] }} días</span>
              </div>
              @endif
            </div>
          </div>

          <!-- Formulario -->
          <form id="formCredMensualPage">
            @csrf
            <input type="hidden" name="nino_id" value="{{ $nino->id_niño }}">
            <input type="hidden" name="mes" value="{{ $mes }}">
            @if($control)
              <input type="hidden" name="control_id" value="{{ $control->id_cred ?? $control->id }}">
            @endif

            <!-- Información sobre Fecha de Nacimiento (mismo estilo que tab-recien-nacido) -->
            <div style="margin-bottom: 24px; padding: 10px 14px; background: #eef2ff; border-radius: 10px; border-left: 4px solid #3b82f6; display: flex; align-items: center; gap: 10px;">
              <div style="width: 28px; height: 28px; border-radius: 999px; background: #3b82f6; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="8" r="4"></circle>
                  <path d="M5 21c1.5-3 4-5 7-5s5.5 2 7 5"></path>
                </svg>
              </div>
              <div>
                <div style="font-size: 12px; font-weight: 600; color: #2563eb; text-transform: uppercase; letter-spacing: 0.05em;">Fecha de nacimiento</div>
                <div style="font-size: 13px; font-weight: 600; color: #0f172a;" id="fecha-nacimiento-display">{{ $fechaNacimiento ? \Carbon\Carbon::parse($fechaNacimiento)->format('d/m/Y') : 'No disponible' }}</div>
              </div>
            </div>

            <!-- Fecha y Edad -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 32px;">
              <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                  Fecha del Control <span style="color: #ef4444;">*</span>
                </label>
                <input 
                  type="date" 
                  id="fecha_control"
                  name="fecha_control" 
                  value="{{ $control && $control->fecha ? \Carbon\Carbon::parse($control->fecha)->format('Y-m-d') : '' }}" 
                  style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #1e293b; background: white; transition: all 0.2s; outline: none;" 
                  onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
                  onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none';"
                  required>
                <p style="margin-top: 6px; font-size: 11px; color: #64748b; display: flex; align-items: center; gap: 4px;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                  </svg>
                  Selecciona la fecha en que se realizó el control
                </p>
              </div>
              <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                  Edad en días
                </label>
                <div style="position: relative;">
                  <input 
                    type="number" 
                    id="edad_dias"
                    name="edad" 
                    readonly
                    value="{{ $control ? ($control->edad_dias ?? $control->edad ?? '') : '' }}" 
                    style="width: 100%; padding: 10px 14px; padding-right: 40px; border: 1px solid #c3ddfd; border-radius: 8px; font-size: 14px; font-weight: 600; color: #1e40af; background: #eff6ff; transition: all 0.2s; outline: none; cursor: not-allowed;" 
                    placeholder="Se calculará automáticamente">
                  <div style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                    </svg>
                  </div>
                </div>
                <p style="margin-top: 6px; font-size: 11px; color: #3b82f6; font-weight: 500; display: flex; align-items: center; gap: 4px;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                  </svg>
                  Calculado automáticamente según la fecha del control
                </p>
              </div>
            </div>

            <!-- Botones de Acción -->
            <div style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 24px; border-top: 1px solid #e5e7eb;">
              <a 
                href="{{ route('controles-cred') }}" 
                style="padding: 10px 20px; font-size: 14px; font-weight: 600; color: #475569; background: #f1f5f9; border-radius: 8px; text-decoration: none; transition: all 0.2s; display: inline-flex; align-items: center;"
                onmouseover="this.style.background='#e2e8f0';"
                onmouseout="this.style.background='#f1f5f9';">
                Cancelar
              </a>
              <button 
                type="submit" 
                style="padding: 10px 20px; font-size: 14px; font-weight: 600; color: white; background: linear-gradient(to right, #3b82f6, #2563eb); border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.1);"
                onmouseover="this.style.boxShadow='0 4px 6px rgba(0,0,0,0.15)'; this.style.transform='translateY(-1px)';"
                onmouseout="this.style.boxShadow='0 1px 2px rgba(0,0,0,0.1)'; this.style.transform='translateY(0)';">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                  <polyline points="17 21 17 13 7 13 7 21"></polyline>
                  <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                {{ $control ? 'Actualizar' : 'Guardar' }} Control
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>

  <script>
    // Fecha de nacimiento del niño (pasada desde el backend)
    const fechaNacimiento = '{{ $fechaNacimiento }}';
    
    // Función para calcular la edad en días
    function calcularEdadDias() {
      const fechaControlInput = document.getElementById('fecha_control');
      const edadDiasInput = document.getElementById('edad_dias');
      
      if (!fechaNacimiento || !fechaControlInput.value) {
        edadDiasInput.value = '';
        return;
      }
      
      const fechaNac = new Date(fechaNacimiento);
      const fechaControl = new Date(fechaControlInput.value);
      
      if (fechaControl < fechaNac) {
        edadDiasInput.value = '';
        edadDiasInput.style.borderColor = '#fca5a5';
        edadDiasInput.style.background = '#fef2f2';
        edadDiasInput.style.color = '#991b1b';
        return;
      }
      
      // Calcular diferencia en días
      const diffTime = Math.abs(fechaControl - fechaNac);
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      
      edadDiasInput.value = diffDays;
      edadDiasInput.style.borderColor = '#c3ddfd';
      edadDiasInput.style.background = '#eff6ff';
      edadDiasInput.style.color = '#1e40af';
    }
    
    // Event listener para calcular edad cuando cambia la fecha
    document.addEventListener('DOMContentLoaded', function() {
      const fechaControlInput = document.getElementById('fecha_control');
      if (fechaControlInput) {
        fechaControlInput.addEventListener('change', calcularEdadDias);
        // Calcular edad inicial si ya hay una fecha
        if (fechaControlInput.value) {
          calcularEdadDias();
        }
      }
    });
    
    document.getElementById('formCredMensualPage').addEventListener('submit', function (e) {
      e.preventDefault();
      const form = e.target;
      const formData = new FormData(form);
      
      // Asegurar que nino_id use el valor correcto
      const ninoIdInput = form.querySelector('input[name="nino_id"]');
      if (ninoIdInput) {
        formData.set('nino_id', ninoIdInput.value);
      }

      const controlId = form.querySelector('input[name="control_id"]');
      const url = controlId && controlId.value 
        ? '{{ route("api.controles-cred-mensual.registrar", ":id") }}'.replace(':id', controlId.value)
        : '{{ route("api.controles-cred-mensual.registrar") }}';

      // Mostrar loading en el botón
      const submitBtn = form.querySelector('button[type="submit"]');
      const originalBtnText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = `
        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Guardando...
      `;

      fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      .then(async res => {
        const contentType = res.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
          const text = await res.text();
          throw new Error('Respuesta no válida del servidor');
        }
        return res.json();
      })
      .then(data => {
        // Restaurar botón
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;

        if (data.success) {
          // Mostrar mensaje de éxito
          const successMsg = document.createElement('div');
          successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 flex items-center gap-3';
          successMsg.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span>${controlId && controlId.value ? 'Control actualizado' : 'Control registrado'} correctamente</span>
          `;
          document.body.appendChild(successMsg);
          
          setTimeout(() => {
            successMsg.style.opacity = '0';
            successMsg.style.transition = 'opacity 0.3s';
            setTimeout(() => successMsg.remove(), 300);
            window.location.href = '{{ route('controles-cred') }}';
          }, 2000);
        } else {
          // Mostrar mensaje de error
          alert(data.message || 'No se pudo registrar el control');
          if (data.errors) {
            console.error('Errores de validación:', data.errors);
          }
        }
      })
      .catch(err => {
        // Restaurar botón
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        
        console.error('Error completo:', err);
        alert('Ocurrió un error al registrar el control: ' + err.message);
      });
    });
  </script>
</body>
</html>

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
              {{ $control ? 'Editar' : 'Registrar' }} Control CRED Mensual
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
                <span style="margin-left: 4px; font-weight: 700; color: #3b82f6;">Control {{ $mes }}</span>
              </div>
              @if($rango)
              <div>
                <span style="font-weight: 600; color: #475569;">Rango:</span> 
                <span style="margin-left: 4px; color: #3b82f6;">{{ $rango['min'] }} - {{ $rango['max'] }} días</span>
              </div>
              @endif
            </div>
          </div>

          <!-- Formulario -->
          <form id="formCredMensualPage">
            @csrf
            <input type="hidden" name="nino_id" value="{{ $nino->id_niño }}">
            <input type="hidden" name="mes" value="{{ $mes }}">
            @if($control)
              <input type="hidden" name="control_id" value="{{ $control->id_cred ?? $control->id }}">
            @endif

            <!-- Información sobre Fecha de Nacimiento (mismo estilo que tab-recien-nacido) -->
            <div style="margin-bottom: 24px; padding: 10px 14px; background: #eef2ff; border-radius: 10px; border-left: 4px solid #3b82f6; display: flex; align-items: center; gap: 10px;">
              <div style="width: 28px; height: 28px; border-radius: 999px; background: #3b82f6; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="8" r="4"></circle>
                  <path d="M5 21c1.5-3 4-5 7-5s5.5 2 7 5"></path>
                </svg>
              </div>
              <div>
                <div style="font-size: 12px; font-weight: 600; color: #2563eb; text-transform: uppercase; letter-spacing: 0.05em;">Fecha de nacimiento</div>
                <div style="font-size: 13px; font-weight: 600; color: #0f172a;" id="fecha-nacimiento-display">{{ $fechaNacimiento ? \Carbon\Carbon::parse($fechaNacimiento)->format('d/m/Y') : 'No disponible' }}</div>
              </div>
            </div>

            <!-- Fecha y Edad -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 32px;">
              <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                  Fecha del Control <span style="color: #ef4444;">*</span>
                </label>
                <input 
                  type="date" 
                  id="fecha_control"
                  name="fecha_control" 
                  value="{{ $control && $control->fecha ? \Carbon\Carbon::parse($control->fecha)->format('Y-m-d') : '' }}" 
                  style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #1e293b; background: white; transition: all 0.2s; outline: none;" 
                  onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
                  onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none';"
                  required>
                <p style="margin-top: 6px; font-size: 11px; color: #64748b; display: flex; align-items: center; gap: 4px;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                  </svg>
                  Selecciona la fecha en que se realizó el control
                </p>
              </div>
              <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                  Edad en días
                </label>
                <div style="position: relative;">
                  <input 
                    type="number" 
                    id="edad_dias"
                    name="edad" 
                    readonly
                    value="{{ $control ? ($control->edad_dias ?? $control->edad ?? '') : '' }}" 
                    style="width: 100%; padding: 10px 14px; padding-right: 40px; border: 1px solid #c3ddfd; border-radius: 8px; font-size: 14px; font-weight: 600; color: #1e40af; background: #eff6ff; transition: all 0.2s; outline: none; cursor: not-allowed;" 
                    placeholder="Se calculará automáticamente">
                  <div style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                    </svg>
                  </div>
                </div>
                <p style="margin-top: 6px; font-size: 11px; color: #3b82f6; font-weight: 500; display: flex; align-items: center; gap: 4px;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                  </svg>
                  Calculado automáticamente según la fecha del control
                </p>
              </div>
            </div>

            <!-- Botones de Acción -->
            <div style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 24px; border-top: 1px solid #e5e7eb;">
              <a 
                href="{{ route('controles-cred') }}" 
                style="padding: 10px 20px; font-size: 14px; font-weight: 600; color: #475569; background: #f1f5f9; border-radius: 8px; text-decoration: none; transition: all 0.2s; display: inline-flex; align-items: center;"
                onmouseover="this.style.background='#e2e8f0';"
                onmouseout="this.style.background='#f1f5f9';">
                Cancelar
              </a>
              <button 
                type="submit" 
                style="padding: 10px 20px; font-size: 14px; font-weight: 600; color: white; background: linear-gradient(to right, #3b82f6, #2563eb); border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.1);"
                onmouseover="this.style.boxShadow='0 4px 6px rgba(0,0,0,0.15)'; this.style.transform='translateY(-1px)';"
                onmouseout="this.style.boxShadow='0 1px 2px rgba(0,0,0,0.1)'; this.style.transform='translateY(0)';">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                  <polyline points="17 21 17 13 7 13 7 21"></polyline>
                  <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                {{ $control ? 'Actualizar' : 'Guardar' }} Control
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>

  <script>
    // Fecha de nacimiento del niño (pasada desde el backend)
    const fechaNacimiento = '{{ $fechaNacimiento }}';
    
    // Función para calcular la edad en días
    function calcularEdadDias() {
      const fechaControlInput = document.getElementById('fecha_control');
      const edadDiasInput = document.getElementById('edad_dias');
      
      if (!fechaNacimiento || !fechaControlInput.value) {
        edadDiasInput.value = '';
        return;
      }
      
      const fechaNac = new Date(fechaNacimiento);
      const fechaControl = new Date(fechaControlInput.value);
      
      if (fechaControl < fechaNac) {
        edadDiasInput.value = '';
        edadDiasInput.style.borderColor = '#fca5a5';
        edadDiasInput.style.background = '#fef2f2';
        edadDiasInput.style.color = '#991b1b';
        return;
      }
      
      // Calcular diferencia en días
      const diffTime = Math.abs(fechaControl - fechaNac);
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      
      edadDiasInput.value = diffDays;
      edadDiasInput.style.borderColor = '#c3ddfd';
      edadDiasInput.style.background = '#eff6ff';
      edadDiasInput.style.color = '#1e40af';
    }
    
    // Event listener para calcular edad cuando cambia la fecha
    document.addEventListener('DOMContentLoaded', function() {
      const fechaControlInput = document.getElementById('fecha_control');
      if (fechaControlInput) {
        fechaControlInput.addEventListener('change', calcularEdadDias);
        // Calcular edad inicial si ya hay una fecha
        if (fechaControlInput.value) {
          calcularEdadDias();
        }
      }
    });
    
    document.getElementById('formCredMensualPage').addEventListener('submit', function (e) {
      e.preventDefault();
      const form = e.target;
      const formData = new FormData(form);
      
      // Asegurar que nino_id use el valor correcto
      const ninoIdInput = form.querySelector('input[name="nino_id"]');
      if (ninoIdInput) {
        formData.set('nino_id', ninoIdInput.value);
      }

      const controlId = form.querySelector('input[name="control_id"]');
      const url = controlId && controlId.value 
        ? '{{ route("api.controles-cred-mensual.registrar", ":id") }}'.replace(':id', controlId.value)
        : '{{ route("api.controles-cred-mensual.registrar") }}';

      // Mostrar loading en el botón
      const submitBtn = form.querySelector('button[type="submit"]');
      const originalBtnText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = `
        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Guardando...
      `;

      fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      .then(async res => {
        const contentType = res.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
          const text = await res.text();
          throw new Error('Respuesta no válida del servidor');
        }
        return res.json();
      })
      .then(data => {
        // Restaurar botón
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;

        if (data.success) {
          // Mostrar mensaje de éxito
          const successMsg = document.createElement('div');
          successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 flex items-center gap-3';
          successMsg.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span>${controlId && controlId.value ? 'Control actualizado' : 'Control registrado'} correctamente</span>
          `;
          document.body.appendChild(successMsg);
          
          setTimeout(() => {
            successMsg.style.opacity = '0';
            successMsg.style.transition = 'opacity 0.3s';
            setTimeout(() => successMsg.remove(), 300);
            window.location.href = '{{ route('controles-cred') }}';
          }, 2000);
        } else {
          // Mostrar mensaje de error
          alert(data.message || 'No se pudo registrar el control');
          if (data.errors) {
            console.error('Errores de validación:', data.errors);
          }
        }
      })
      .catch(err => {
        // Restaurar botón
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        
        console.error('Error completo:', err);
        alert('Ocurrió un error al registrar el control: ' + err.message);
      });
    });
  </script>
</body>
</html>
