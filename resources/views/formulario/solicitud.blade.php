<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Formulario de Solicitud</title>
    <link rel="stylesheet" href="{{ asset('Css/Formulario.css') }}">
    @stack('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
</head>
<body>
    <!-- ===== FIGURAS DECORATIVAS ===== -->
    <svg class="decor-icon decor-icon--left" viewBox="0 0 64 64">
        <path fill="none" stroke="currentColor" stroke-width="4" d="M28 8h8v16h16v8H36v16h-8V32H12v-8h16z" />
    </svg>
    <svg class="decor-icon decor-icon--center" viewBox="0 0 64 64">
        <circle cx="32" cy="32" r="26" stroke="currentColor" stroke-width="4" fill="none" />
        <path d="M20 32h8l4 8 4-16 4 8h8" stroke="currentColor" stroke-width="3" fill="none" />
    </svg>
    <svg class="decor-icon decor-icon--right" viewBox="0 0 64 64">
        <path fill="none" stroke="currentColor" stroke-width="4" d="M2 32h12l6 10 6-20 6 20 6-10h18" />
    </svg>

    <!-- ===== FORMULARIO ===== -->
    <form id="yourFormId" action="{{ route('formulario.submit') }}" method="post" novalidate>
        @csrf
        <div class="form-wrapper">
            <!-- Botón Volver -->
            <button type="button" onclick="window.location.href='{{ route('login') }}'" class="btn-volver">
                <i class="bi bi-arrow-left"></i> Volver
            </button>

            <h1 class="form-title">📋 Formulario de Solicitud</h1>
            <p class="form-subtitle">
                Ingrese los datos solicitados para procesar su solicitud
                correctamente.
            </p>

            <div class="form-grid">
                <div class="form-card">
                    <h2 class="form-card-title">Información del Documento</h2>
                    <div class="form-input-icon">
                        <i class="bi bi-person-vcard-fill"></i>
                        <select id="Id_Tipo_Documento" name="Id_Tipo_Documento" class="form-input" required>
                            <option value="">Seleccione</option>
                            <option value="1">DNI</option>
                            <option value="2">CE</option>
                            <option value="3">PASS</option>
                            <option value="4">DIE</option>
                            <option value="5">S/ DOCUMENTO</option>
                            <option value="6">CNV</option>
                        </select>
                    </div>
                    <div class="form-input-icon">
                        <i class="bi bi-file-earmark-person-fill"></i>
                        <input type="text" id="Numero_Documento" name="Numero_Documento" class="form-input"
                            placeholder="Número de documento" required />
                    </div>
                </div>

                <div class="form-card">
                    <h2 class="form-card-title">Información del Establecimiento</h2>
                    <div class="form-input-icon">
                        <i class="bi bi-globe"></i>
                        <select id="codigoRed" name="Codigo_Red" class="form-input" required>
                            <option value="">Seleccione una Red</option>
                            <option value="1">AGUAYTIA</option>
                            <option value="2">ATALAYA</option>
                            <option value="3">BAP-CURARAY</option>
                            <option value="4">CORONEL PORTILLO</option>
                            <option value="5">ESSALUD</option>
                            <option value="6">FEDERICO BASADRE - YARINACOCHA</option>
                            <option value="7">HOSPITAL AMAZONICO - YARINACOCHA</option>
                            <option value="8">HOSPITAL REGIONAL DE PUCALLPA</option>
                            <option value="9">NO PERTENECE A NINGUNA RED</option>
                        </select>
                    </div>
                    <div class="form-input-icon">
                        <i class="bi bi-map"></i>
                        <select id="codigoMicrored" name="Codigo_Microred" class="form-input" required disabled>
                            <option value="">Seleccione una Microred</option>
                        </select>
                    </div>
                    <div class="form-input-icon">
                        <i class="bi bi-building"></i>
                        <select id="idEstablecimiento" name="Id_Establecimiento" class="form-input" required disabled>
                            <option value="">Seleccione un Establecimiento</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-card">
                    <h2 class="form-card-title">Información Adicional</h2>
                    <div class="form-input-icon">
                        <i class="bi bi-pencil"></i>
                        <input type="text" id="Motivo" name="Motivo" class="form-input" placeholder="Motivo"
                            required />
                    </div>
                    <div class="form-input-icon">
                        <i class="bi bi-briefcase"></i>
                        <input type="text" id="Cargo" name="Cargo" class="form-input" placeholder="Cargo"
                            required />
                    </div>
                </div>

                <div class="form-card">
                    <h2 class="form-card-title">Contacto</h2>
                    <div class="form-input-icon">
                        <i class="bi bi-telephone"></i>
                        <input type="text" id="Celular" name="Celular" class="form-input"
                            placeholder="Celular" required />
                    </div>
                    <div class="form-input-icon">
                        <i class="bi bi-envelope"></i>
                        <input type="email" id="Correo" name="Correo" class="form-input"
                            placeholder="Correo Electrónico" required />
                    </div>
                </div>
            </div>

            <div class="form-check-modern">
                <input type="checkbox" id="acceptTerms" name="acceptTerms" value="1" required />
                <label for="acceptTerms">Acepto la
                    <a href="https://drive.google.com/file/d/1shyY0IkjjzGsP_vh-cb1d9yIIkR2Zfet/view?pli=1" target="_blank">confidencialidad de datos</a>.</label>
            </div>

            <div class="form-actions">
                <button type="submit" class="auth-submit-btn" id="submitBtn">
                    <i class="bi bi-send-fill"></i> Solicitar
                </button>
            </div>
        </div>
    </form>

    <div id="successModal">
        <div class="modal-content">
            <div class="success-icon-wrapper">
                <i class="bi bi-check-circle-fill success-icon"></i>
            </div>
            <h2>¡Solicitud Enviada!</h2>
            <p>
                Tu solicitud ha sido registrada correctamente.<br /><br />
                Serás redirigido al inicio de sesión en <span class="countdown-number">3</span> segundos...
            </p>
            <div class="loader"></div>
        </div>
    </div>

    <script src="{{ asset('JS/formulario-selec-de-EESS.js') }}"></script>
    <script src="{{ asset('JS/Envio-de-solicitud.js') }}"></script>
</body>
</html>

