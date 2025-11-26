<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#000000">
  <meta name="description" content="Sistema de Control y Alerta de Etapas de Vida del Niño - SISCADIT">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>SISCADIT - Logs</title>
  <link rel="stylesheet" href="{{ asset('Css/Dashboard.css') }}">
  @stack('styles')
</head>
<body>
  <noscript>You need to enable JavaScript to run this app.</noscript>
  <div id="root">
    <div class="flex h-screen bg-slate-50 relative">
      <x-sidebar-main activeRoute="logs" />
      <main class="flex-1 overflow-auto">
        <div class="p-8">
          <div class="space-y-8">
            <div>
              <h1 class="text-4xl font-bold text-slate-800">Logs del Sistema</h1>
              <p class="text-slate-600 mt-2">Registro de actividades del sistema</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
              <p class="text-slate-600">Contenido de logs en desarrollo...</p>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>
</html>




