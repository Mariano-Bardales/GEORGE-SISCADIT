<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>SISCADIT - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('Css/Login.css') }}">
    @stack('styles')
</head>
<body>
    <div class="auth-container">
        <!-- Fondo con íconos médicos -->
        <div class="auth-background">
            <div class="auth-overlay"></div>
            <div class="medical-icons">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-stethoscope medical-icon" aria-hidden="true">
                    <path d="M11 2v2"></path>
                    <path d="M5 2v2"></path>
                    <path d="M5 3H4a2 2 0 0 0-2 2v4a6 6 0 0 0 12 0V5a2 2 0 0 0-2-2h-1"></path>
                    <path d="M8 15a6 6 0 0 0 12 0v-3"></path>
                    <circle cx="20" cy="10" r="2"></circle>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-heart medical-icon pulse" aria-hidden="true">
                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3
                   c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5
                   c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-activity medical-icon" aria-hidden="true">
                    <path d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0
                   L9.24 2.18a.25.25 0 0 0-.48 0L6.41 10.54A2 2 0 0 1 4.49 12H2"></path>
                </svg>
            </div>
        </div>

        <div class="auth-content">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="auth-logo">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-building2 lucide-building-2 auth-logo-icon" aria-hidden="true">
                            <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path>
                            <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path>
                            <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path>
                            <path d="M10 6h4"></path>
                            <path d="M10 10h4"></path>
                            <path d="M10 14h4"></path>
                            <path d="M10 18h4"></path>
                        </svg>
                        <div>
                            <h1 class="auth-title">SISCADIT</h1>
                            <p class="auth-subtitle">Sistema de Control y Alerta </p>
                            <p class="auth-description">Etapa de vida del niño</p>
                        </div>
                    </div>
                </div>

                <div dir="ltr" data-orientation="horizontal" class="auth-tabs">
                    <div data-state="active" data-orientation="horizontal" role="tabpanel"
                        aria-labelledby="radix-:r0:-trigger-login" id="radix-:r0:-content-login" tabindex="0"
                        class="mt-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 auth-form-content"
                        style="animation-duration: 0s;">
                        <form class="auth-form" method="POST" action="{{ route('login') }}">
                            @csrf

                            @if($errors->any())
                                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                                    <ul class="list-disc list-inside">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="form-label">Email Institucional</label>
                                <input
                                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm form-input"
                                    placeholder="usuario@diresa.gob.pe" required type="email" name="email" value="{{ old('email') }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Contraseña</label>
                                <div class="password-input-container">
                                    <input
                                        class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm form-input"
                                        placeholder="Ingrese su contraseña" required type="password" name="password">
                                    <button
                                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs password-toggle"
                                        type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-eye w-4 h-4" aria-hidden="true">
                                            <path
                                                d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0">
                                            </path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <button
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 auth-submit-btn"
                                type="submit">Iniciar Sesión</button>

                            <h4 class="mt-4 text-center text-sm text-muted-foreground">¿No tienes una cuenta?</h4>
                            <div class="demo-accounts text-center text-xs text-muted-foreground">
                                <a href="{{ route('formulario') }}" class="text-primary font-medium hover:underline">
                                    Solicítala a través del área de sistemas.
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('JS/login-Contraseña.js') }}"></script>
</body>
</html>



