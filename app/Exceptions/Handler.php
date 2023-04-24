<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => __('admin_labels.the_specified_data_is_invalid'),
                    'errors' => $e->validator->getMessageBag()
                ], 422);
            } elseif (config('app.validation_toastr', false)) {
                toastr()->error($e->validator->getMessageBag()->first());
            }
        }

        return parent::render($request, $e);
    }
}
