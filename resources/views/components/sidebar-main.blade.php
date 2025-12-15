@props(['activeRoute' => ''])

<aside id="sidebar" class="relative text-white transition-all duration-300 flex flex-col w-72" data-testid="sidebar"
  style="background: linear-gradient(rgb(102, 126, 234) 0%, rgb(118, 75, 162) 100%); box-shadow: rgba(102, 126, 234, 0.15) 4px 0px 24px; height: 100vh; overflow: hidden; flex-shrink: 0;">
  <button id="toggleSidebarBtn"
    class="absolute -right-3 top-6 bg-white text-purple-600 rounded-full p-1.5 shadow-lg hover:shadow-xl transition-all z-50 hover:bg-purple-50"
    data-testid="toggle-sidebar-button">
    <svg id="toggleIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
      viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
      stroke-linejoin="round" class="w-4 h-4 transition-transform duration-300" aria-hidden="true">
      <path d="m15 18-6-6 6-6"></path>
    </svg>
  </button>
  <div class="p-6 border-b border-white/10">
    <div class="flex items-center gap-3">
      <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
        style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="lucide lucide-file-text w-7 h-7" aria-hidden="true">
          <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
          <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
          <path d="M10 9H8"></path>
          <path d="M16 13H8"></path>
          <path d="M16 17H8"></path>
        </svg>
      </div>
      <div>
        <h1 class="text-2xl font-bold tracking-tight">SISCADIT</h1>
        <p class="text-xs text-white/80 mt-0.5">Sistema de Control CRED</p>
      </div>
    </div>
  </div>
  <div class="p-4 border-b border-white/10">
    <div class="flex items-center gap-3">
      <div
        class="w-11 h-11 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-base font-bold border-2 border-white/30">
        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
      </div>
      <div class="flex-1 min-w-0">
        <p class="font-semibold text-sm truncate">{{ auth()->user()->name ?? 'Usuario' }}</p>
        <span class="text-xs bg-white/20 backdrop-blur-sm text-white px-2.5 py-1 rounded-full inline-block mt-1">
          @php
            $role = strtolower(auth()->user()->role ?? '');
          @endphp
          @if($role === 'admin')
            DIRESA
          @elseif($role === 'jefe_red' || $role === 'jefedered' || $role === 'jefe_microred')
            Jefe de Red
          @elseif($role === 'coordinador_microred' || $role === 'coordinadordemicrored' || $role === 'coordinador_red')
            Coordinador de Micro Red
          @else
            {{ ucfirst(auth()->user()->role ?? 'Usuario') }}
          @endif
        </span>
      </div>
    </div>
  </div>
  <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto" style="min-height: 0;">
    <a data-testid="menu-dashboard"
      class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all group relative {{ $activeRoute === 'dashboard' ? 'bg-white/25 backdrop-blur-sm text-white shadow-lg' : 'text-white/80 hover:bg-white/15 hover:text-white' }}"
      href="{{ route('dashboard') }}" data-discover="true" style="{{ $activeRoute === 'dashboard' ? 'box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;' : '' }}">
      <div class="relative flex-shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard w-5 h-5" aria-hidden="true">
          <rect width="7" height="9" x="3" y="3" rx="1"></rect>
          <rect width="7" height="5" x="14" y="3" rx="1"></rect>
          <rect width="7" height="9" x="14" y="12" rx="1"></rect>
          <rect width="7" height="5" x="3" y="16" rx="1"></rect>
        </svg>
      </div>
      <span class="font-medium text-[15px]">Dashboard</span>
      @if($activeRoute === 'dashboard')
        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-white rounded-r-full"></div>
      @endif
    </a>
    <a data-testid="menu-controles-cred"
      class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all group relative {{ $activeRoute === 'controles-cred' ? 'bg-white/25 backdrop-blur-sm text-white shadow-lg' : 'text-white/80 hover:bg-white/15 hover:text-white' }}"
      href="{{ route('controles-cred') }}" data-discover="true" style="{{ $activeRoute === 'controles-cred' ? 'box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;' : '' }}">
      <div class="relative flex-shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text w-5 h-5" aria-hidden="true">
          <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
          <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
          <path d="M10 9H8"></path>
          <path d="M16 13H8"></path>
          <path d="M16 17H8"></path>
        </svg>
      </div>
      <span class="font-medium text-[15px]"> CRED</span>
      @if($activeRoute === 'controles-cred')
        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-white rounded-r-full"></div>
      @endif
    </a>
    <a data-testid="menu-alertas"
      class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all group relative {{ $activeRoute === 'alertas-cred' ? 'bg-white/25 backdrop-blur-sm text-white shadow-lg' : 'text-white/80 hover:bg-white/15 hover:text-white' }}"
      href="{{ route('alertas-cred') }}" data-discover="true" style="{{ $activeRoute === 'alertas-cred' ? 'box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;' : '' }}">
      <div class="relative flex-shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert w-5 h-5" aria-hidden="true">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" x2="12" y1="8" y2="12"></line>
          <line x1="12" x2="12.01" y1="16" y2="16"></line>
        </svg>
        <span class="absolute -top-1 -right-1 min-w-[20px] h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-semibold px-1.5 {{ $activeRoute === 'alertas-cred' ? 'hidden' : '' }}" id="contadorAlertas">0</span>
      </div>
      <span class="font-medium text-[15px]">Alertas</span>
      @if($activeRoute === 'alertas-cred')
        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-white rounded-r-full"></div>
      @endif
    </a>
    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'ADMIN'))
    <a data-testid="menu-usuario"
      class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all group relative {{ $activeRoute === 'usuarios' ? 'bg-white/25 backdrop-blur-sm text-white shadow-lg' : 'text-white/80 hover:bg-white/15 hover:text-white' }}"
      href="{{ route('usuarios') }}" data-discover="true" style="{{ $activeRoute === 'usuarios' ? 'box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;' : '' }}">
      <div class="relative flex-shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-5 h-5" aria-hidden="true">
          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
          <path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
          <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
          <circle cx="9" cy="7" r="4"></circle>
        </svg>
      </div>
      <span class="font-medium text-[15px]">Usuarios</span>
      @if($activeRoute === 'usuarios')
        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-white rounded-r-full"></div>
      @endif
    </a>
    @endif
  </nav>
  <div class="p-4 border-t border-white/10">
    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit" data-testid="logout-button"
        class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-white/80 hover:bg-red-500/20 hover:text-white transition-all w-full group">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="lucide lucide-log-out w-5 h-5 group-hover:scale-110 transition-transform" aria-hidden="true">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
          <polyline points="16 17 21 12 16 7"></polyline>
          <line x1="21" x2="9" y1="12" y2="12"></line>
        </svg>
        <span class="font-medium text-[15px]">Cerrar Sesión</span>
      </button>
    </form>
  </div>
</aside>

<style>
/* Estilos para el sidebar colapsado */
#sidebar.collapsed {
  width: 80px !important;
  min-width: 80px;
}

#sidebar.collapsed nav a span,
#sidebar.collapsed .p-6 > div > div:last-child,
#sidebar.collapsed .p-4 > div > div:last-child,
#sidebar.collapsed .p-4 form button span {
  display: none;
}

#sidebar.collapsed nav a {
  justify-content: center;
  padding: 0.875rem;
}

#sidebar.collapsed .p-6 > div,
#sidebar.collapsed .p-4 > div {
  justify-content: center;
}

#sidebar.collapsed .p-6,
#sidebar.collapsed .p-4 {
  padding: 1rem 0.5rem;
}

#sidebar.collapsed nav a > div {
  margin: 0 auto;
}

#sidebar.collapsed #contadorAlertas {
  display: none;
}
</style>

<script>
// Cargar contador de alertas al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    cargarContadorAlertas();
    
    // Actualizar cada 5 minutos
    setInterval(cargarContadorAlertas, 300000);
    
    // Toggle sidebar completo
    const toggleBtn = document.getElementById('toggleSidebarBtn');
    const sidebar = document.getElementById('sidebar');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            if (sidebar.classList.contains('collapsed')) {
                toggleIcon.style.transform = 'rotate(180deg)';
            } else {
                toggleIcon.style.transform = 'rotate(0deg)';
            }
        });
    }
});

function cargarContadorAlertas() {
    const contadorElement = document.getElementById('contadorAlertas');
    if (!contadorElement) return;
    
    // Obtener la URL de la ruta de forma segura
    const url = '{{ route("api.alertas.total") }}';
    
    fetch(url, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.total !== undefined) {
            const total = data.total;
            contadorElement.textContent = total > 99 ? '99+' : total;
            
            // Mostrar u ocultar según el total
            if (total > 0) {
                contadorElement.classList.remove('hidden');
                contadorElement.classList.add('animate-pulse');
            } else {
                contadorElement.classList.add('hidden');
                contadorElement.classList.remove('animate-pulse');
            }
        } else {
            contadorElement.textContent = '0';
            contadorElement.classList.add('hidden');
        }
    })
    .catch(error => {
        console.error('Error al cargar contador de alertas:', error);
        // En caso de error, ocultar el contador
        contadorElement.textContent = '0';
        contadorElement.classList.add('hidden');
    });
}
</script>

