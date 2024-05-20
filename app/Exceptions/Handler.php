<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;

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
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson()) {
            // Define uma estrutura de resposta JSON padrão para exceções
            $status = 500;
            $response = [
                'message' => 'Erro interno do servidor',
                'error' => $exception->getMessage()
            ];

            if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                $status = 404;
                $response['message'] = 'Recurso não encontrado';
            } elseif ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                $status = 401;
                $response['message'] = 'Não autenticado';
            } elseif ($exception instanceof \Illuminate\Validation\ValidationException) {
                $status = 422;
                $response['message'] = 'Erro de validação';
                $response['errors'] = $exception->errors();
            }

            return response()->json($response, $status);
        }

        return parent::render($request, $exception);
    }
}
