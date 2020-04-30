<?php

namespace NookPlus;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateUserAction
{
    /**
     * @var Data
     */
    private $data;

    public function __construct(Data $data)
    {
        $this->data = $data;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $uuid = $this->data->createUser();
        $response = $response->withHeader(
            'Content-Type',
            'application/json'
        );
        $response->getBody()->write(json_encode($uuid));
        return $response;
    }
}
