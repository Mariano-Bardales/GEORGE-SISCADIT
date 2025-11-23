// Toggle de mostrar/ocultar contraseña
document.addEventListener('DOMContentLoaded', function () {
  const togglePasswordBtn = document.querySelector('.password-toggle');
  const passwordInput = document.querySelector('.password-input-container input');

  if (togglePasswordBtn && passwordInput) {
    togglePasswordBtn.addEventListener('click', () => {
      const isHidden = passwordInput.type === 'password';
      passwordInput.type = isHidden ? 'text' : 'password';

      // Cambiar el ícono del ojo
      togglePasswordBtn.innerHTML = isHidden
        ? `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
             viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="lucide lucide-eye-off w-4 h-4" aria-hidden="true">
          <path d="m2 2 20 20"></path>
          <path d="M10.73 5.08A10.45 10.45 0 0 1 12 5c5.25 0 9.27 3.11 10.94 7
                   a10.52 10.52 0 0 1-1.62 2.68"></path>
          <path d="M6.12 6.12A10.52 10.52 0 0 0 1.06 12
                   a10.45 10.45 0 0 0 9.21 5.92
                   10.65 10.65 0 0 0 4.3-.88"></path>
          <path d="M9.88 9.88A3 3 0 0 0 14.12 14.12"></path>
        </svg>`
        : `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
             viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="lucide lucide-eye w-4 h-4" aria-hidden="true">
          <path d="M2.062 12.348a1 1 0 0 1 0-.696
                   10.75 10.75 0 0 1 19.876 0
                   1 1 0 0 1 0 .696
                   10.75 10.75 0 0 1-19.876 0"></path>
          <circle cx="12" cy="12" r="3"></circle>
        </svg>`;
    });
  }
});



