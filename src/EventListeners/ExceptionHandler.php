<?php

namespace App\EventListeners;

use Psr\Log\LoggerInterface;
use App\Entity\HypermidiaResponse;
use App\Helper\EntityFactoryException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionHandler implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handleEntityExpection', 1],
                ['handle404Expection', 0],
                ['handleGenericException', -1]
            ],
        ];
    }

    public function handle404Expection(ExceptionEvent $event)
    {
        if ($event->getThrowable() instanceof NotFoundHttpException) {
            $response = HypermidiaResponse::fromError($event->getThrowable())
                ->getResponse();
            $response->setStatusCode(Response::HTPP_NOT_FOUND);
            $event->setResponse($response);
        }
    }

    public function handleEntityExpection(ExceptionEvent $event)
    {
        if ($event->getThrowable() instanceof EntityFactoryException) {
            $response = HypermidiaResponse::fromError($event->getThrowable())
                ->getResponse();
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $event->setResponse($response);
        }
    }

    public function handleGenericException(ExceptionEvent $event)
    {
        $this->logger->notice('Uma exceção ocorreu {stack} ', [
            'stack' => $event->getThrowable()->getTraceAsString()
        ]);
        $response = HypermidiaResponse::fromError($event->getThrowable());
        $event->setResponse($response->getResponse());
    }
}