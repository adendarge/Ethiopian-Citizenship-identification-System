<?php

namespace App\Exceptions;

use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use App\Http\Controllers\UserRedirectionController;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];


    public function report(Exception $exception){
        parent::report($exception);
    }


    public function render($request, Exception $exception){
      if($exception instanceof NotFoundHttpException){
      //   // if (Auth::guard($guard)->check()) {
      //   //   $route = new UserRedirectionController(auth()->user());
      //   //   return response()->view('error');
      //   // }
        return response()->view('error');
      //
      }
      // }else if($exception instanceof NetworkException  ){
      //   echo 'network error happens';
      // }else if($excepion instanceof GuzzleHttp\Exception\ConnectException){
      //   echo 'guzzel exception';
      // }else if($exception instanceof RuntimeException){
      //   echo 'runtime excepion';
      // }
      return parent::render($request, $exception);
    }


    protected function unauthenticated($request, AuthenticationException $exception)    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('cis.login_form'));
    }
}
