document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById("yourFormId");
    const btn = document.getElementById("submitBtn");
    const modal = document.getElementById("successModal");

    if (!form || !btn || !modal) {
        console.error('Elementos del formulario no encontrados');
        return;
    }

    // Función para validar email
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Función para validar DNI (exactamente 8 dígitos)
    function isValidDNI(dni) {
        const dniRegex = /^[0-9]{8}$/;
        return dniRegex.test(dni.trim());
    }

    // Función para validar teléfono (solo números, máximo 9 dígitos)
    function isValidPhone(phone) {
        const phoneRegex = /^[0-9]{1,9}$/;
        return phoneRegex.test(phone.trim());
    }

    // Función para mostrar modal de errores
    function showErrorModal(errors) {
        const modal = document.getElementById('errorModal');
        const errorList = document.getElementById('errorList');
        
        if (!modal || !errorList) return;
        
        // Limpiar lista anterior
        errorList.innerHTML = '';
        
        // Agregar cada error a la lista con animación escalonada
        errors.forEach((error, index) => {
            const li = document.createElement('li');
            li.style.animationDelay = `${index * 0.05}s`;
            li.innerHTML = `
                <span class="error-number">${index + 1}</span>
                <span class="error-text">${error}</span>
            `;
            errorList.appendChild(li);
        });
        
        // Mostrar modal con animación
        modal.classList.add('show');
        document.body.style.overflow = 'hidden'; // Prevenir scroll del body
    }

    // Función para cerrar modal de errores
    function closeErrorModal() {
        const modal = document.getElementById('errorModal');
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = ''; // Restaurar scroll del body
        }
    }

    // Cerrar modal al hacer clic fuera de él
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('errorModal');
        if (modal && e.target === modal) {
            closeErrorModal();
        }
    });

    // Cerrar modal con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeErrorModal();
        }
    });

    // Función para mostrar error en un campo
    function showFieldError(input) {
        input.style.boxShadow = "0 0 0 3px rgba(239,68,68,0.4)";
        input.style.borderColor = "#ef4444";
        
        // Remover clase de error anterior
        input.classList.remove('field-valid');
        input.classList.add('field-error');
    }

    // Función para limpiar error de un campo
    function clearFieldError(input) {
        input.style.boxShadow = "none";
        input.style.borderColor = "";
        input.classList.remove('field-error');
        input.classList.add('field-valid');
    }

        // Validar campos en tiempo real
    const inputs = form.querySelectorAll("input, select");
    const dniInput = document.getElementById('Numero_Documento');
    const celularInput = document.getElementById('Celular');
    const dniHint = document.getElementById('dniHint');
    const celularHint = document.getElementById('celularHint');

    // Validar solo números en DNI con contador mejorado
    if (dniInput) {
        dniInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            const length = this.value.length;
            const remaining = 8 - length;
            
            const counter = document.getElementById('dniCounter');
            
            // Actualizar contador con animación
            if (counter) {
                counter.textContent = `${length}/8`;
                counter.classList.add('updating');
                setTimeout(() => counter.classList.remove('updating'), 300);
            }
            
            if (dniHint) {
                const icon = dniHint.querySelector('.validation-icon i');
                const label = dniHint.querySelector('.validation-label');
                const value = dniHint.querySelector('.validation-value');
                
                if (length === 8) {
                    // DNI completo y válido
                    dniHint.className = 'validation-message valid';
                    if (icon) icon.className = 'bi bi-check-circle-fill';
                    if (label) label.textContent = 'DNI válido';
                    if (value) value.textContent = 'Formato correcto';
                } else if (length > 0 && length < 8) {
                    // DNI incompleto
                    dniHint.className = 'validation-message warning';
                    if (icon) icon.className = 'bi bi-exclamation-triangle';
                    if (label) label.textContent = 'Faltan dígitos';
                    if (value) value.textContent = `${remaining} dígito${remaining !== 1 ? 's' : ''} restante${remaining !== 1 ? 's' : ''}`;
                } else if (length > 8) {
                    // DNI excede el límite (no debería pasar por maxlength, pero por si acaso)
                    dniHint.className = 'validation-message error';
                    if (icon) icon.className = 'bi bi-x-circle-fill';
                    if (label) label.textContent = 'Excede el límite';
                    if (value) value.textContent = 'Máximo 8 dígitos';
                } else {
                    // DNI vacío
                    dniHint.className = 'validation-message';
                    if (icon) icon.className = 'bi bi-info-circle';
                    if (label) label.textContent = 'Formato requerido';
                    if (value) value.textContent = '8 dígitos numéricos';
                }
            }
        });
    }
    
    // Validar solo números en Celular con contador mejorado
    if (celularInput) {
        celularInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            const length = this.value.length;
            const remaining = 9 - length;
            
            const counter = document.getElementById('celularCounter');
            
            // Actualizar contador con animación
            if (counter) {
                counter.textContent = `${length}/9`;
                counter.classList.add('updating');
                setTimeout(() => counter.classList.remove('updating'), 300);
            }
            
            if (celularHint) {
                const icon = celularHint.querySelector('.validation-icon i');
                const label = celularHint.querySelector('.validation-label');
                const value = celularHint.querySelector('.validation-value');
                
                if (length === 9) {
                    // Celular completo y válido
                    celularHint.className = 'validation-message valid';
                    if (icon) icon.className = 'bi bi-check-circle-fill';
                    if (label) label.textContent = 'Celular válido';
                    if (value) value.textContent = 'Formato correcto';
                } else if (length > 0 && length < 9) {
                    // Celular incompleto pero válido
                    celularHint.className = 'validation-message';
                    if (icon) icon.className = 'bi bi-info-circle';
                    if (label) label.textContent = 'Formato requerido';
                    if (value) value.textContent = `Máximo 9 dígitos (${length} ingresado${length !== 1 ? 's' : ''})`;
                } else if (length > 9) {
                    // Celular excede el límite
                    celularHint.className = 'validation-message error';
                    if (icon) icon.className = 'bi bi-x-circle-fill';
                    if (label) label.textContent = 'Excede el límite';
                    if (value) value.textContent = 'Máximo 9 dígitos permitidos';
                } else {
                    // Celular vacío
                    celularHint.className = 'validation-message';
                    if (icon) icon.className = 'bi bi-info-circle';
                    if (label) label.textContent = 'Formato requerido';
                    if (value) value.textContent = 'Máximo 9 dígitos numéricos';
                }
            }
        });
    }

    inputs.forEach((input) => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute("required")) {
                if (this.type === 'checkbox') {
                    if (!this.checked) {
                        showFieldError(this);
                    } else {
                        clearFieldError(this);
                    }
                } else if (this.type === 'email') {
                    if (!isValidEmail(this.value.trim())) {
                        showFieldError(this);
                    } else {
                        clearFieldError(this);
                    }
                } else if (this.type === 'text' && this.id === 'Celular') {
                    if (!isValidPhone(this.value.trim())) {
                        showFieldError(this);
                        if (celularHint) {
                            const icon = celularHint.querySelector('.validation-icon i');
                            const label = celularHint.querySelector('.validation-label');
                            const value = celularHint.querySelector('.validation-value');
                            celularHint.className = 'validation-message error';
                            if (icon) icon.className = 'bi bi-x-circle-fill';
                            if (label) label.textContent = 'Error de formato';
                            if (value) value.textContent = 'Máximo 9 dígitos (solo números)';
                        }
                    } else {
                        clearFieldError(this);
                    }
                } else if (this.id === 'Numero_Documento') {
                    if (!isValidDNI(this.value.trim())) {
                        showFieldError(this);
                        if (dniHint) {
                            const icon = dniHint.querySelector('.validation-icon i');
                            const label = dniHint.querySelector('.validation-label');
                            const value = dniHint.querySelector('.validation-value');
                            dniHint.className = 'validation-message error';
                            if (icon) icon.className = 'bi bi-x-circle-fill';
                            if (label) label.textContent = 'Error de formato';
                            if (value) value.textContent = 'Debe tener exactamente 8 dígitos';
                        }
                    } else {
                        clearFieldError(this);
                    }
                } else if (this.value.trim() === "" || this.value === "") {
                    showFieldError(this);
                } else {
                    clearFieldError(this);
                }
            }
        });
    });

    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        
        let valid = true;
        let firstInvalidField = null;
        const errorMessages = [];

        // Limpiar errores previos
        inputs.forEach((input) => {
            clearFieldError(input);
        });

        // Validar todos los campos requeridos
        inputs.forEach((input) => {
            if (input.hasAttribute("required")) {
                // Validar checkbox
                if (input.type === 'checkbox') {
                    if (!input.checked) {
                        valid = false;
                        showFieldError(input);
                        if (!firstInvalidField) firstInvalidField = input;
                        errorMessages.push('Debe aceptar la confidencialidad de datos para continuar');
                    } else {
                        clearFieldError(input);
                    }
                }
                // Validar campos deshabilitados (microred y establecimiento deben estar seleccionados)
                else if ((input.id === 'codigoMicrored' || input.id === 'idEstablecimiento')) {
                    if (input.disabled || input.value === "" || input.value === null) {
                        valid = false;
                        showFieldError(input);
                        if (!firstInvalidField) firstInvalidField = input;
                        const fieldName = input.id === 'codigoMicrored' ? 'Microred' : 'Establecimiento';
                        errorMessages.push(`Debe seleccionar una ${fieldName}`);
                    }
                }
                // Validar email
                else if (input.type === 'email') {
                    if (input.value.trim() === "") {
                        valid = false;
                        showFieldError(input);
                        if (!firstInvalidField) firstInvalidField = input;
                        errorMessages.push('El correo electrónico es obligatorio');
                    } else if (!isValidEmail(input.value.trim())) {
                        valid = false;
                        showFieldError(input);
                        if (!firstInvalidField) firstInvalidField = input;
                        errorMessages.push('Debe ingresar un correo electrónico válido');
                    }
                }
                // Validar DNI
                else if (input.id === 'Numero_Documento') {
                    if (input.value.trim() === "") {
                        valid = false;
                        showFieldError(input);
                        if (!firstInvalidField) firstInvalidField = input;
                        errorMessages.push('El número de DNI es obligatorio');
                    } else if (!isValidDNI(input.value.trim())) {
                        valid = false;
                        showFieldError(input);
                        if (!firstInvalidField) firstInvalidField = input;
                        errorMessages.push('El número de DNI debe tener exactamente 8 dígitos');
                    }
                }
                // Validar teléfono
                else if (input.id === 'Celular') {
                    if (input.value.trim() === "") {
                        valid = false;
                        showFieldError(input);
                        if (!firstInvalidField) firstInvalidField = input;
                        errorMessages.push('El celular es obligatorio');
                    } else if (!isValidPhone(input.value.trim())) {
                        valid = false;
                        showFieldError(input);
                        if (!firstInvalidField) firstInvalidField = input;
                        errorMessages.push('El celular debe tener máximo 9 dígitos (solo números)');
                    }
                }
                // Validar selects
                else if (input.tagName === 'SELECT') {
                    if (input.value === "" || input.value === null) {
                        valid = false;
                        showFieldError(input);
                        if (!firstInvalidField) firstInvalidField = input;
                        errorMessages.push(`Debe seleccionar ${input.options[0].text}`);
                    }
                }
                // Validar inputs de texto normales
                else {
                    if (input.value.trim() === "") {
                        valid = false;
                        showFieldError(input);
                        if (!firstInvalidField) firstInvalidField = input;
                        errorMessages.push(`El campo ${input.placeholder || 'requerido'} es obligatorio`);
                    }
                }
            }
        });

        if (!valid) {
            // Mostrar modal con todos los errores
            showErrorModal(errorMessages);
            
            // Hacer scroll al primer campo con error
            if (firstInvalidField) {
                setTimeout(() => {
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalidField.focus();
                }, 100);
            }
            return;
        }

        // Deshabilitar botón y cambiar texto
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Enviando...';

        try {
            // Obtener el token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                || document.querySelector('input[name="_token"]')?.value;

            // Crear FormData
            const formData = new FormData(form);
            
            // Asegurar que el checkbox acceptTerms esté incluido si está marcado
            const acceptTermsCheckbox = document.getElementById('acceptTerms');
            if (acceptTermsCheckbox && acceptTermsCheckbox.checked) {
                formData.set('acceptTerms', '1');
            }
            
            // Asegurar que el token CSRF esté incluido
            if (!formData.has('_token') && csrfToken) {
                formData.append('_token', csrfToken);
            }

            // Enviar formulario con fetch
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || ''
                }
            });

            if (response.ok) {
                const responseData = await response.json();
                
                // Mostrar modal de éxito
                modal.style.display = "flex";
                
                // Actualizar contenido del modal
                const modalContent = modal.querySelector(".modal-content");
                const modalTitle = modalContent.querySelector("h2");
                const modalText = modalContent.querySelector("p");
                const modalLoader = modalContent.querySelector(".loader");
                const countdownElement = modalContent.querySelector(".countdown");
                
                // Mostrar mensaje de éxito
                modalTitle.textContent = "¡Solicitud Enviada!";
                modalText.innerHTML = 
                    "Tu solicitud ha sido registrada correctamente.<br /><br />" +
                    "Serás redirigido al inicio de sesión en <span class='countdown-number'>3</span> segundos...";
                
                // Iniciar contador regresivo
                let countdown = 3;
                const countdownInterval = setInterval(() => {
                    countdown--;
                    const countdownSpan = modalText.querySelector('.countdown-number');
                    if (countdownSpan) {
                        countdownSpan.textContent = countdown;
                    }
                    
                    if (countdown <= 0) {
                        clearInterval(countdownInterval);
                        if (modalLoader) modalLoader.style.display = "none";
                        modalText.innerHTML = "Redirigiendo al inicio de sesión...";
                    }
                }, 1000);

                // Redirigir automáticamente al login después de 3 segundos
                setTimeout(() => {
                    window.location.href = "/login";
                }, 3000);
            } else {
                // Manejar errores del servidor
                const data = await response.json().catch(() => ({}));
                let errorMsg = "Hubo un error al enviar el formulario. Por favor, intente nuevamente.";
                
                if (data.errors) {
                    const firstError = Object.values(data.errors)[0];
                    errorMsg = Array.isArray(firstError) ? firstError[0] : firstError;
                } else if (data.message) {
                    errorMsg = data.message;
                }

                // Mostrar errores en modal si hay múltiples errores
                if (data.errors && typeof data.errors === 'object') {
                    const errorList = [];
                    Object.values(data.errors).forEach(err => {
                        if (Array.isArray(err)) {
                            errorList.push(...err);
                        } else {
                            errorList.push(err);
                        }
                    });
                    if (errorList.length > 0) {
                        showErrorModal(errorList);
                    } else {
                        showErrorModal([errorMsg]);
                    }
                } else {
                    showErrorModal([errorMsg]);
                }
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-send-fill"></i> Solicitar';
            }
        } catch (error) {
            console.error('Error al enviar formulario:', error);
            showErrorModal(["Error de conexión. Por favor, verifique su conexión a internet e intente nuevamente."]);
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-send-fill"></i> Solicitar';
        }
    });
});

