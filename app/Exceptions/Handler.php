<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof NotFoundHttpException) {

            if($exception->getStatusCode() == 404)
                if( $request->header('Content-Type') == 'application/json'){
                    //  print_r($request->json());die();
                    if ($request->json()) {
                        return response()->json(['status' => 404, 'message' => 'Page not found'], 404);
                    }
                }
            else{
                return response()->view('error',['status' => 404, 'message' => 'Page not found']);
            }
        }
        elseif ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json(['status' => 405, 'message' => $exception->getMessage()], 405);
        }

        return parent::render($request, $exception);
    }
}
