<?php

namespace NookPlus;

use Fig\Http\Message\StatusCodeInterface as Status;
use Predis\Client;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RemoveValueAction
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
        $id = $args['id']; 
        if (!$this->data->userExists($id)) {
            return $response->withStatus(Status::STATUS_NOT_FOUND);
        }

        $key = $args['key'];
        if (!$this->data->keyExists($key)) {
            return $response->withStatus(Status::STATUS_NOT_FOUND);
        }

        $value = $args['value'];

        $this->data->removeValue($id, $key, $value);

        return $response->withStatus(Status::STATUS_NO_CONTENT);
    }
}
