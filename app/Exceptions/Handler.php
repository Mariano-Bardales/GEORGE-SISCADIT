<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Session\TokenMismatchException;
use Throwable;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Log todas las excepciones importantes
            if ($this->shouldReport($e)) {
                Log::error('Excepción no manejada', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                    'user_id' => auth()->id(),
                ]);
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        // Manejar excepciones de validación
        if ($e instanceof ValidationException) {
            return $this->handleValidationException($request, $e);
        }

        // Manejar excepciones de autenticación
        if ($e instanceof AuthenticationException) {
            return $this->handleAuthenticationException($request, $e);
        }

        // Manejar excepciones de modelo no encontrado
        if ($e instanceof ModelNotFoundException) {
            return $this->handleModelNotFoundException($request, $e);
        }

        // Manejar excepciones 404
        if ($e instanceof NotFoundHttpException) {
            return $this->handleNotFoundHttpException($request, $e);
        }

        // Manejar excepciones 403
        if ($e instanceof AccessDeniedHttpException || (method_exists($e, 'getStatusCode') && $e->getStatusCode() === 403)) {
            return $this->handleAccessDeniedException($request, $e);
        }

        // Manejar excepciones de token CSRF expirado (419)
        if ($e instanceof TokenMismatchException) {
            return $this->handleTokenMismatchException($request, $e);
        }

        // Para peticiones AJAX/API, retornar JSON
        if ($request->expectsJson() || $request->ajax() || $request->is('api/*')) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Manejar excepciones de validación
     */
    protected function handleValidationException($request, ValidationException $e)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }

        return parent::render($request, $e);
    }

    /**
     * Manejar excepciones de autenticación
     */
    protected function handleAuthenticationException($request, AuthenticationException $e)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado',
            ], 401);
        }

        return redirect()->route('login');
    }

    /**
     * Manejar excepciones de modelo no encontrado
     */
    protected function handleModelNotFoundException($request, ModelNotFoundException $e)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Recurso no encontrado',
            ], 404);
        }

        return parent::render($request, $e);
    }

    /**
     * Manejar excepciones 404
     */
    protected function handleNotFoundHttpException($request, NotFoundHttpException $e)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Página no encontrada',
            ], 404);
        }

        return parent::render($request, $e);
    }

    /**
     * Manejar excepciones 403
     */
    protected function handleAccessDeniedException($request, $e)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene permisos para acceder a esta sección',
            ], 403);
        }

        return parent::render($request, $e);
    }

    /**
     * Manejar excepciones de token CSRF expirado (419)
     */
    protected function handleTokenMismatchException($request, TokenMismatchException $e)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Su sesión ha expirado. Por favor, recargue la página e intente nuevamente.',
                'error' => 'token_expired',
            ], 419);
        }

        // Redirigir a la página anterior con un mensaje de error
        return redirect()->back()
            ->withInput($request->except('password', '_token'))
            ->withErrors(['csrf' => 'Su sesión ha expirado. Por favor, recargue la página e intente nuevamente.']);
    }

    /**
     * Manejar excepciones de API
     */
    protected function handleApiException($request, Throwable $e)
    {
        $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
        
        // En producción, no mostrar detalles del error
        $message = config('app.debug') 
            ? $e->getMessage() 
            : 'Error interno del servidor';

        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => config('app.debug') ? [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ] : null,
        ], $statusCode);
    }
}
