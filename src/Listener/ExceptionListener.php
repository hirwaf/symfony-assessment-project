<?php

namespace App\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        // Check if the request is expecting a JSON response
        $isJsonRequest = $request->getContentType() === 'json'
            || $request->getAcceptableContentTypes() === ['application/json'];

        if ($isJsonRequest) {
            $response = new JsonResponse();

            if ($exception instanceof HttpExceptionInterface) {
                $response->setStatusCode($exception->getStatusCode());
                $response->headers->replace($exception->getHeaders());
            } else {
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $response->setData([
                'error' => [
                    'code'    => $response->getStatusCode(),
                    'message' => $exception->getMessage()
                ]
            ]);

            if ($_ENV[ 'APP_ENV' ] === 'dev') {
                $response->setData($response->getData() + [
                        'file'  => $exception->getFile(),
                        'line'  => $exception->getLine(),
                        'trace' => $exception->getTraceAsString()
                    ]);
            }

            $event->setResponse($response);
        }
    }
}
