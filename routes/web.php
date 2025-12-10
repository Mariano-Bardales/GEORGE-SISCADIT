<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FormularioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ControlCredController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RegistroControlesController;
use App\Http\Controllers\ImportControlesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Formulario de solicitud
Route::get('/formulario-solicitud', [FormularioController::class, 'show'])->name('formulario');
Route::post('/formulario-solicitud', [FormularioController::class, 'submit'])->name('formulario.submit');

// Consulta de solicitud (placeholder - implementar después)
Route::get('/consultar-solicitud', function () {
    return redirect()->route('login')->with('info', 'La funcionalidad de consulta de solicitudes estará disponible próximamente.');
})->name('consultar-solicitud');

// Dashboard y rutas protegidas
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/controles-cred', [ControlCredController::class, 'index'])->name('controles-cred');
    Route::post('/controles-cred', [ControlCredController::class, 'store'])->name('controles-cred.store');
    // Páginas independientes para registrar controles
    Route::get('/controles-cred/cred-mensual/registrar', [ControlCredController::class, 'formCredMensual'])->name('controles-cred.cred-mensual.form');
    Route::get('/controles-cred/recien-nacido/registrar', [ControlCredController::class, 'formRecienNacido'])->name('controles-cred.recien-nacido.form');
    Route::get('/controles-cred/tamizaje/registrar', [ControlCredController::class, 'formTamizaje'])->name('controles-cred.tamizaje.form');
    Route::get('/controles-cred/cnv/registrar', [ControlCredController::class, 'formCNV'])->name('controles-cred.cnv.form');
    Route::get('/controles-cred/visitas/registrar', [ControlCredController::class, 'formVisita'])->name('controles-cred.visitas.form');
    Route::get('/controles-cred/vacunas/registrar', [ControlCredController::class, 'formVacuna'])->name('controles-cred.vacunas.form');
    Route::get('/alertas-cred', function () { return view('dashboard.alertas-cred'); })->name('alertas-cred');
    
    // Importar controles desde Excel (solo admin)
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::post('/importar-controles', [ImportControlesController::class, 'import'])->name('importar-controles.import');
    });
    
    // Solicitudes (solo admin y jefe_red) - CRUD completo
    Route::middleware(['auth', 'role:admin,jefe_red'])->group(function () {
        Route::get('/solicitudes', [SolicitudController::class, 'index'])->name('solicitudes');
        Route::post('/solicitudes', [SolicitudController::class, 'store'])->name('solicitudes.store');
        Route::get('/solicitudes/{id}', [SolicitudController::class, 'show'])->name('solicitudes.show');
        Route::put('/solicitudes/{id}', [SolicitudController::class, 'update'])->name('solicitudes.update');
        Route::delete('/solicitudes/{id}', [SolicitudController::class, 'destroy'])->name('solicitudes.destroy');
        Route::post('/solicitudes/{id}/crear-usuario', [SolicitudController::class, 'crearUsuario'])->name('solicitudes.crear-usuario');
    });
    
    // Usuarios (solo admin)
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{id}', [UsuarioController::class, 'show'])->name('usuarios.show');
        Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    });
    
    // API Routes (requieren autenticación)
    Route::prefix('api')->middleware(['auth'])->group(function () {
        Route::get('/dashboard/stats', [ApiController::class, 'dashboardStats'])->name('api.dashboard.stats');
        Route::get('/reportes/estadisticas', [ApiController::class, 'reportesEstadisticas'])->name('api.reportes.estadisticas');
        Route::get('/ninos', [ApiController::class, 'ninos'])->name('api.ninos');
        Route::get('/nino/datos-extras', [ApiController::class, 'datosExtras'])->name('api.nino.datos-extras');
        Route::get('/nino/{id}/controles', [ApiController::class, 'obtenerTodosControles'])->name('api.nino.controles');
        Route::delete('/nino/{id}', [ApiController::class, 'eliminarNino'])->name('api.nino.eliminar');
        
        // Controles Recién Nacido
        Route::get('/controles-recien-nacido', [ApiController::class, 'controlesRecienNacido'])->name('api.controles-recien-nacido');
        Route::post('/controles-recien-nacido/registrar', [ApiController::class, 'registrarControlRecienNacido'])->name('api.controles-recien-nacido.registrar');
        Route::post('/controles-recien-nacido/{id}/update', [ApiController::class, 'actualizarControlRecienNacido'])->name('api.controles-recien-nacido.update');
        Route::delete('/controles-recien-nacido/{id}', [ApiController::class, 'eliminarControlRecienNacido'])->name('api.controles-recien-nacido.delete');
        
        // Controles CRED Mensual
        Route::get('/controles-cred-mensual', [ApiController::class, 'controlesCredMensual'])->name('api.controles-cred-mensual');
        Route::post('/controles-cred-mensual/registrar', [ApiController::class, 'registrarCredMensual'])->name('api.controles-cred-mensual.registrar');
        Route::post('/controles-cred-mensual/registrar/{id}', [ApiController::class, 'registrarCredMensual'])->name('api.controles-cred-mensual.registrar.update');
        Route::delete('/controles-cred-mensual/{id}', [ApiController::class, 'eliminarControlCredMensual'])->name('api.controles-cred-mensual.delete');
        
        // Tamizaje Neonatal
        Route::get('/tamizaje', [ApiController::class, 'tamizaje'])->name('api.tamizaje');
        Route::post('/tamizaje/registrar', [ApiController::class, 'registrarTamizaje'])->name('api.tamizaje.registrar');
        
        // CNV (Carné de Nacido Vivo)
        Route::get('/cnv', [ApiController::class, 'cnv'])->name('api.cnv');
        Route::post('/cnv/registrar', [ApiController::class, 'registrarCNV'])->name('api.cnv.registrar');
        
        // Visitas Domiciliarias
        Route::get('/visitas', [ApiController::class, 'visitas'])->name('api.visitas');
        Route::post('/visitas/registrar', [ApiController::class, 'registrarVisita'])->name('api.visitas.registrar');
        
        // Vacunas
        Route::get('/vacunas', [ApiController::class, 'vacunas'])->name('api.vacunas');
        Route::post('/vacunas/registrar', [ApiController::class, 'registrarVacuna'])->name('api.vacunas.registrar');
        
        Route::get('/alertas/total', [ApiController::class, 'totalAlertas'])->name('api.alertas.total');
        Route::get('/alertas', [ApiController::class, 'obtenerAlertas'])->name('api.alertas');
        
        // Solicitudes API (solo admin y jefe_red)
        Route::middleware(['role:admin,jefe_red'])->group(function () {
            Route::get('/solicitudes', [SolicitudController::class, 'index'])->name('api.solicitudes');
            Route::delete('/solicitudes/{id}', [SolicitudController::class, 'destroy'])->name('api.solicitudes.destroy');
        });
        
        // Usuarios API (solo admin)
        Route::middleware(['role:admin'])->group(function () {
            Route::get('/usuarios', [UsuarioController::class, 'index'])->name('api.usuarios');
            Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('api.usuarios.update');
            Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('api.usuarios.destroy');
        });
        
        // RENIEC API
        Route::get('/consultar-reniec', [UsuarioController::class, 'consultarReniec'])->name('api.consultar-reniec');
        Route::post('/consultar-reniec', [UsuarioController::class, 'consultarReniec'])->name('api.consultar-reniec.post');
        
        // Crear usuario desde solicitud (API)
        Route::post('/crear-usuario-solicitud', [SolicitudController::class, 'crearUsuarioDesdeSolicitud'])->name('api.crear-usuario-solicitud');
    });
    
});

