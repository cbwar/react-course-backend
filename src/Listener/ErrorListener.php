<?php

namespace App\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ErrorListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => ['onKernelException', 0]];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $error = $event->getThrowable();
        if ($error instanceof HttpException) {
            $status = $error->getStatusCode();
        } else {
            $status = 500;
        }

        $response = new JsonResponse([
            'errorCode' => $error->getCode(),
            'errorMessage' => $error->getMessage()
        ], $status);

        $event->setResponse($response);
    }
}