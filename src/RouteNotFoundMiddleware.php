<?php

namespace NookPlus;

use Fig\Http\Message\StatusCodeInterface as Status;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;

class RouteNotFoundMiddleware implements MiddlewareInterface
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        try {
            return $handler->handle($request);
        } catch (HttpNotFoundException $e) {
            return $this->responseFactory->createResponse(Status::STATUS_NOT_FOUND);
        } catch (HttpMethodNotAllowedException $e) {
            return $this->responseFactory->createResponse(Status::STATUS_METHOD_NOT_ALLOWED);
        }
    }
}
