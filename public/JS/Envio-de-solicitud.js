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

    // Función para validar teléfono (solo números y algunos caracteres especiales)
    function isValidPhone(phone) {
        const phoneRegex = /^[0-9+\-() ]+$/;
        return phoneRegex.test(phone) && phone.trim().length >= 9;
    }

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
                        errorMessages.push('Ingrese un número de celular válido (mínimo 9 dígitos)');
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
            // Mostrar mensaje de error
            const errorMsg = errorMessages.length > 0 
                ? errorMessages[0] 
                : "Por favor, complete todos los campos requeridos.";
            alert(errorMsg);
            
            // Hacer scroll al primer campo con error
            if (firstInvalidField) {
                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalidField.focus();
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

                alert(errorMsg);
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-send-fill"></i> Solicitar';
            }
        } catch (error) {
            console.error('Error al enviar formulario:', error);
            alert("Error de conexión. Por favor, verifique su conexión a internet e intente nuevamente.");
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-send-fill"></i> Solicitar';
        }
    });
});

