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
                            <option value="1" selected>DNI</option>
                        </select>
                    </div>
                    <div class="form-input-icon">
                        <i class="bi bi-file-earmark-person-fill"></i>
                        <input type="text" id="Numero_Documento" name="Numero_Documento" class="form-input"
                            placeholder="Ingrese su DNI" maxlength="8" pattern="[0-9]{8}" required />
                    </div>
                    <div id="dniHint" class="validation-message">
                        <div class="validation-icon">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        <div class="validation-text">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
                                <div style="display: flex; flex-direction: column; gap: 0.25rem; flex: 1;">
                                    <span class="validation-label">Formato requerido:</span>
                                    <span class="validation-value">8 dígitos numéricos</span>
                                </div>
                                <div id="dniCounter" class="validation-counter-compact">0/8</div>
                            </div>
                        </div>
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
                            placeholder="Ingrese su número de celular" maxlength="9" pattern="[0-9]{1,9}" required />
                    </div>
                    <div id="celularHint" class="validation-message">
                        <div class="validation-icon">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        <div class="validation-text">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
                                <div style="display: flex; flex-direction: column; gap: 0.25rem; flex: 1;">
                                    <span class="validation-label">Formato requerido:</span>
                                    <span class="validation-value">Máximo 9 dígitos numéricos</span>
                                </div>
                                <div id="celularCounter" class="validation-counter-compact">0/9</div>
                            </div>
                        </div>
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

    <!-- Modal de Éxito -->
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

    <!-- Modal de Errores de Validación -->
    <div id="errorModal" class="error-modal-overlay">
        <div class="error-modal-content">
            <div class="error-modal-header">
                <div class="error-icon-wrapper">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h2>Datos Faltantes</h2>
                <button class="error-modal-close" onclick="closeErrorModal()" aria-label="Cerrar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <p class="error-modal-subtitle">
                Por favor, complete los siguientes campos requeridos:
            </p>
            <ul id="errorList" class="error-list">
                <!-- Los errores se agregarán aquí dinámicamente -->
            </ul>
            <button onclick="closeErrorModal()" class="error-modal-button">
                <i class="bi bi-check-circle"></i> Entendido
            </button>
        </div>
    </div>

    <style>
        /* Estilos del Modal de Errores */
        .error-modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            animation: fadeInOverlay 0.3s ease;
        }

        .error-modal-overlay.show {
            display: flex;
        }

        @keyframes fadeInOverlay {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .error-modal-content {
            background: white;
            border-radius: 20px;
            padding: 0;
            max-width: 550px;
            width: 90%;
            max-height: 85vh;
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.4);
            animation: slideUpModal 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
        }

        @keyframes slideUpModal {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .error-modal-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.75rem 2rem;
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-bottom: 2px solid #fca5a5;
            position: relative;
        }

        .error-icon-wrapper {
            background: #dc2626;
            padding: 0.875rem;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
            animation: pulseError 2s ease-in-out infinite;
        }

        @keyframes pulseError {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .error-icon-wrapper i {
            color: white;
            font-size: 1.5rem;
        }

        .error-modal-header h2 {
            margin: 0;
            color: #dc2626;
            font-size: 1.625rem;
            font-weight: 700;
            flex: 1;
        }

        .error-modal-close {
            background: rgba(220, 38, 38, 0.1);
            border: none;
            border-radius: 8px;
            padding: 0.5rem;
            cursor: pointer;
            color: #dc2626;
            font-size: 1.125rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-modal-close:hover {
            background: rgba(220, 38, 38, 0.2);
            transform: rotate(90deg);
        }

        .error-modal-subtitle {
            color: #64748b;
            margin: 1.5rem 2rem 1rem;
            font-size: 0.9375rem;
            line-height: 1.6;
        }

        .error-list {
            list-style: none;
            padding: 0 2rem;
            margin: 0 0 1.5rem;
            max-height: 300px;
            overflow-y: auto;
        }

        .error-list::-webkit-scrollbar {
            width: 6px;
        }

        .error-list::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .error-list::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .error-list::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .error-list li {
            padding: 1rem;
            margin-bottom: 0.75rem;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-left: 4px solid #dc2626;
            border-radius: 10px;
            display: flex;
            align-items: flex-start;
            gap: 0.875rem;
            animation: slideInError 0.3s ease;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.1);
            transition: all 0.2s ease;
        }

        .error-list li:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
        }

        @keyframes slideInError {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .error-list li .error-number {
            color: #dc2626;
            font-weight: 700;
            font-size: 1rem;
            min-width: 24px;
            background: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(220, 38, 38, 0.2);
        }

        .error-list li .error-text {
            color: #991b1b;
            font-size: 0.9375rem;
            line-height: 1.5;
            flex: 1;
        }

        .error-modal-button {
            width: calc(100% - 4rem);
            margin: 0 2rem 2rem;
            padding: 1rem;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .error-modal-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        .error-modal-button:active {
            transform: translateY(0);
        }

        .error-modal-button i {
            font-size: 1.125rem;
        }

        /* Mensajes de validación mejorados */
        .validation-message {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-top: 0.75rem;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            animation: fadeInHint 0.3s ease;
        }

        @keyframes fadeInHint {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .validation-message.valid {
            background: rgba(34, 197, 94, 0.15);
            border-color: rgba(34, 197, 94, 0.3);
        }

        .validation-message.error {
            background: rgba(239, 68, 68, 0.15);
            border-color: rgba(239, 68, 68, 0.3);
        }

        .validation-message.warning {
            background: rgba(251, 191, 36, 0.15);
            border-color: rgba(251, 191, 36, 0.3);
        }

        .validation-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(148, 163, 184, 0.2);
            color: #94a3b8;
            font-size: 0.875rem;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .validation-message.valid .validation-icon {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
        }

        .validation-message.error .validation-icon {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .validation-message.warning .validation-icon {
            background: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
        }

        .validation-text {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .validation-label {
            font-size: 0.75rem;
            color: #cbd5e1;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .validation-message.valid .validation-label {
            color: #86efac;
        }

        .validation-message.error .validation-label {
            color: #fca5a5;
        }

        .validation-message.warning .validation-label {
            color: #fde68a;
        }

        .validation-value {
            font-size: 0.875rem;
            color: #e2e8f0;
            font-weight: 600;
        }

        .validation-message.valid .validation-value {
            color: #22c55e;
        }

        .validation-message.error .validation-value {
            color: #ef4444;
        }

        .validation-message.warning .validation-value {
            color: #fbbf24;
        }

        /* Contador compacto integrado en el mensaje de validación */
        .validation-counter-compact {
            font-size: 1rem;
            font-weight: 700;
            padding: 0.5rem 0.875rem;
            border-radius: 8px;
            background: rgba(148, 163, 184, 0.15);
            color: #94a3b8;
            text-align: center;
            min-width: 60px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .validation-message.valid .validation-counter-compact {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border-color: rgba(34, 197, 94, 0.3);
        }

        .validation-message.error .validation-counter-compact {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .validation-message.warning .validation-counter-compact {
            background: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
            border-color: rgba(251, 191, 36, 0.3);
        }

        /* Animación de pulso para el contador */
        @keyframes pulseCounter {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .validation-counter-compact.updating {
            animation: pulseCounter 0.3s ease;
        }

        @media (max-width: 768px) {
            .error-modal-content {
                width: 95%;
                max-height: 90vh;
            }

            .error-modal-header {
                padding: 1.5rem 1.5rem;
            }

            .error-modal-header h2 {
                font-size: 1.375rem;
            }

            .error-modal-subtitle {
                margin: 1.25rem 1.5rem 1rem;
            }

            .error-list {
                padding: 0 1.5rem;
            }

            .error-modal-button {
                width: calc(100% - 3rem);
                margin: 0 1.5rem 1.5rem;
            }
        }
    </style>

    <script src="{{ asset('JS/formulario-selec-de-EESS.js') }}"></script>
    <script src="{{ asset('JS/Envio-de-solicitud.js') }}"></script>
    <script>
        // Hacer la función closeErrorModal disponible globalmente
        window.closeErrorModal = function() {
            const modal = document.getElementById('errorModal');
            if (modal) {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }
        };
    </script>
</body>
</html>

